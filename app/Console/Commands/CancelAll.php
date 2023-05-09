<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CancelAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trader:cancelall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Auth::loginUsingId(4, $remember = true);
        Accounts::tokenPreFlight();

        $pendingCancels = Order::where([
            ['user_id', '=', Auth::id()],
            ['tag', '=', 'AA_PuReWebDev'],
            ['instruction', '=', 'SELL'],
//            ['created_at', '<=', Carbon::now()->setTimezone('America/New_York')->subMinutes(15)
//            ['created_at', '>=', Carbon::now()->subMinutes(15)
//                ->toDateTimeString()],
        ])->whereIn('status', ['WORKING'])->whereDate('created_at', Carbon::today())->get();

        foreach ($pendingCancels as $pendingCancel) {
            try {
                TDAmeritrade::cancelOrder($pendingCancel['orderId']);
                sleep(5);
                usleep(5000000);
                usleep(5000000);
            } catch (GuzzleException $e) {
                Log::debug('Attempted To Cancel Already Cancelled Order', ['success' => false, 'error' => $e->getMessage()]);
            }

            Log::info('Stale Buy Order Cancelled: ' . $pendingCancel['orderId'] .' '. Carbon::now());
            $this->info('Stale Buy Order Cancelled: ' . $pendingCancel['orderId'] .' '. Carbon::now());
        }
        return 0;
    }
}
