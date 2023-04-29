<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\OrdersProcessed;
use App\Models\Order;
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

        if (strtoupper($status) === 'ALL') {
            $status = '';
        }

        while (true) {
            Accounts::tokenPreFlight();
            TDAmeritrade::getOrders($status);
            $this->info($status.' Orders Retrieved. '.Carbon::now());
            usleep(500000);
            usleep(500000);

            if (empty($status)) {
                sleep(5);
            }
            // Splitting The Workload for basic clean up of stale orders
            $pendingCancels = Order::where([
                ['user_id', '=', Auth::id()],
                ['tag', '=', 'AA_PuReWebDev'],
                ['instruction', '=', 'BUY'],
                ['created_at', '<=', Carbon::now()->subMinutes(2)
                    ->toDateTimeString()],
            ])->whereIn('status',['WORKING','PENDING_ACTIVATION'])->get();

            foreach ($pendingCancels as $pendingCancel) {
                try {
                    TDAmeritrade::cancelOrder($pendingCancel['orderId']);
                } catch (GuzzleException $e) {
                    Log::debug('Attempted To Cancel Already Cancelled Order',['success' => false,'error' => $e->getMessage()]);
                }

                Log::info('Stale Buy Order Cancelled: '.$pendingCancel['orderId']);
                $this->info('Stale Buy Order Cancelled: '.$pendingCancel['orderId']);
            }

//            $orders = Order::where([
//                ['user_id', '=', Auth::id()],
//                ['tag', '=', 'AA_PuReWebDev'],
//                ['instruction', '=', 'SELL'],
//            ])->whereNotNull('instruction')->whereNotNull('positionEffect')->whereNotNull('price')->whereIn('status',['WORKING','PENDING_ACTIVATION'])->whereDate('created_at', Carbon::today())->get();

            $yesterday = Carbon::yesterday();
            $now = Carbon::now();

            $orders = Order::where([
                ['user_id','=', Auth::id()],
                ['tag', '=', 'AA_PuReWebDev'],
            ])->whereNotNull('instruction')->whereNotNull('positionEffect')
                ->whereBetween('created_at', [$yesterday, $now])->orderBy('orderId', 'DESC')->get();

            OrdersProcessed::dispatch($orders);
        }

        $this->info('Trade Orders Retrieval Gracefully Exiting');
        return 0;
    }
}
