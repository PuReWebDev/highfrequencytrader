<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\OrdersProcessed;
use App\Models\Order;
use App\Models\WatchList;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RetrieveOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trader:orders {status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rapid Retrieve Trade Order Information';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get stock symbol from command argument
        $status = $this->argument('status');

        Auth::loginUsingId(4, $remember = true);

        $symbols = WatchList::where('user_id', Auth::id())->get();

        foreach ($symbols as $symbol) {
            dd($symbol['symbol']);
        }

        if (strtoupper($status) === 'ALL') {
            $status = '';
        }

        while (true) {
            Accounts::tokenPreFlight();

            $this->info('Starting To Retrieved Orders. '.Carbon::now());
            TDAmeritrade::getOrders($status);
            $this->info($status.' Orders Retrieved. '.Carbon::now());

            if ($status === 'WORKING') {
                $this->cancelStaleOrders();

                $this->info('Dispatching To Trade Engine Processor '.Carbon::now());
                OrdersProcessed::dispatch();
                $this->info('Trade Engine Processor Completed'.Carbon::now());
            }
        }

        $this->info('Trade Orders Retrieval Gracefully Exiting');
        return 0;
    }

    /**
     * cancelStaleOrders
     * Cancel Stale Buy Orders
     * @throws \JsonException
     */
    public function cancelStaleOrders(): void
    {
        $pendingCancels = Order::where([
            ['user_id', '=', Auth::id()],
            ['tag', '=', 'AA_PuReWebDev'],
            ['instruction', '=', 'BUY'],
            ['created_at', '<=', Carbon::now()->subMinutes(3)
                ->toDateTimeString()],
        ])->whereIn('status', ['WORKING', 'PENDING_ACTIVATION'])->get();

        foreach ($pendingCancels as $pendingCancel) {
            try {
                TDAmeritrade::cancelOrder($pendingCancel['orderId']);
                sleep(2);
                usleep(500000);
                usleep(500000);
            } catch (GuzzleException $e) {
                Log::debug('Attempted To Cancel Already Cancelled Order', ['success' => false, 'error' => $e->getMessage()]);
            }

            Log::info('Stale Buy Order Cancelled: ' . $pendingCancel['orderId']);
            $this->info('Stale Buy Order Cancelled: ' . $pendingCancel['orderId']);
        }
    }
}
