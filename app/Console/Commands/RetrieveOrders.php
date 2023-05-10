<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Events\OrdersProcessed;
use App\Models\Order;
use App\Models\WatchList;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JsonException;

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
     * @throws JsonException
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

            if ($status != 'INDIVIDUAL') {
                $this->info('Starting To Retrieved Orders. '.Carbon::now()->setTimezone('America/New_York')->format('Y-m-d g:i A'));
                TDAmeritrade::getOrders($status);
                $this->info($status.' Orders Retrieved. '.Carbon::now()->setTimezone('America/New_York')->format('Y-m-d g:i A'));
            }

            if ($status === 'WORKING') {
                $this->cancelStaleOrders();

                $this->info('Dispatching To Trade Engine Processor '.Carbon::now()->setTimezone('America/New_York')->format('Y-m-d g:i A'));
                OrdersProcessed::dispatch();
                $this->info('Trade Engine Processor Completed'.Carbon::now()->setTimezone('America/New_York')->format('Y-m-d g:i A'));

//                $orders = Order::where([
//                    ['user_id', '=', Auth::id()],
//                    ['tag', '=', 'AA_PuReWebDev'],
////            ['instruction', '=', 'SELL'],
//                ])->whereIn('instruction',['SELL','BUY'])->whereNotNull('instruction')->whereNotNull('positionEffect')->whereNotNull('price')->whereIn('status',['WORKING'])->whereDate('created_at', Carbon::today())->get();
//
//                foreach ($orders as $order) {
//                    TDAmeritrade::getOrder($order['orderId']);
//                    Log::info('Individual Order Retrieved and Updated: '. $order['orderId']);
//                    usleep(5000000);
//                }

            }

            if ($status === 'INDIVIDUAL' || empty($status)) {

                $orders = Order::where([
                    ['user_id', '=', Auth::id()],
                    ['tag', '=', 'AA_PuReWebDev'],
//            ['instruction', '=', 'SELL'],
//                ])->whereIn('instruction',['SELL','BUY'])->whereNotNull('instruction')->whereNotNull('positionEffect')->whereNotNull('price')->whereIn('status',['WORKING'])->whereDate('created_at', Carbon::today())->get();
                ])->whereIn('instruction',['SELL','BUY'])->whereNotNull('instruction')->whereNotNull('positionEffect')->whereIn('status',['WORKING'])->get();

                $count = $orders->count();
                Log::info('Start With Total Count of: '. $count);
                foreach ($orders as $order) {
                    TDAmeritrade::getOrder($order['orderId']);
                    Log::info('Individual Order Retrieved and Updated: '. $order['orderId']);
                    $this->info('Individual Order Retrieved and Updated: '.
                        $order['orderId']. ' And '. $count-- .' remaining');
                    usleep(5000000);
                    if ($count === 1) {
                        break;
                    }
                }
            }

            if ($status === 'FILLED' || empty($status)) {
//                try {
//                    self::getCandleSticks();
//                } catch (GuzzleException $e) {
//                    Log::error('Guzzle Exception Thrown', ['error' =>
//                        $e->getMessage()]);
//                } catch (JsonException $e) {
//                    Log::error('Json Exception Thrown', ['error' =>
//                        $e->getMessage()]);
//                }
            }

            if ($status === 'FULL') {
                try {
                    self::getCandleSticks();
                } catch (GuzzleException $e) {
                    Log::error('Guzzle Exception Thrown', ['error' =>
                    $e->getMessage()]);
                } catch (JsonException $e) {
                    Log::error('Json Exception Thrown', ['error' =>
                        $e->getMessage()]);
                }
                break;
            }
        }

        $this->info('Trade Orders Retrieval Gracefully Exiting');
        return 0;
    }

    /**
     * cancelStaleOrders
     * Cancel Stale Buy Orders
     * @throws JsonException
     */
    public function cancelStaleOrders(): void
    {
        $date = new DateTime;
        $date->modify('-3 minutes');
        $formatted = $date->format('Y-m-d H:i:s');


        $pendingCancels = Order::where([
            ['user_id', '=', Auth::id()],
            ['tag', '=', 'AA_PuReWebDev'],
            ['instruction', '=', 'BUY'],
//            ['created_at', '<=', Carbon::now()->setTimezone('America/New_York')->subMinutes(15)
//            ['created_at', '<=', Carbon::now()->subMinutes(5)
            ['created_at', '<=', $formatted],
            ['status', '=', 'WORKING'],
        ])->get();
//        ])->whereIn('status', ['WORKING'])->get();

        foreach ($pendingCancels as $pendingCancel) {
            try {
                TDAmeritrade::cancelOrder($pendingCancel['orderId']);
                // TODO perform an order update of this order. Boom!!
                sleep(5);
//                usleep(500000);
                usleep(5000000);
            } catch (GuzzleException $e) {
                Log::debug('Attempted To Cancel Already Cancelled Order', ['success' => false, 'error' => $e->getMessage()]);
            }

            Log::info('Stale Buy Order Cancelled: ' . $pendingCancel['orderId']);
            $this->info('Stale Buy Order Cancelled: ' . $pendingCancel['orderId']);
        }
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public static function getCandleSticks(): void
    {
        $symbols = WatchList::where([
            ['user_id','=', Auth::id()],
            ['enabled','=', 1],
        ])->get();

        foreach ($symbols as $symbol) {
            Log::info('Fetching Candle for: '. $symbol['symbol']);
            TDAmeritrade::getPriceHistory($symbol['symbol']);
            usleep(500000);
        }
    }
}
