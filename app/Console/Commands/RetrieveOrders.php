<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
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

        if ($status === strtoupper('all')) {
            $status = '';
        }

        while (true) {
            TDAmeritrade::getOrders($status);
            $this->info($status.' Orders Retrieved. '.Carbon::now());
            usleep(500000);

            if (empty($status)) {
                $this->info('Set Sleep is ideal');
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
                TDAmeritrade::cancelOrder($pendingCancel['orderId']);
                Log::info('Stale Buy Order Cancelled: '.$pendingCancel['orderId']);
                $this->info('Stale Buy Order Cancelled: '.$pendingCancel['orderId']);
            }
        }

        $this->info('Trade Orders Retrieval Gracefully Exiting');
        return 0;
    }
}
