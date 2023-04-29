<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderService;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TradeEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trader:trade {symbol}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initiate Trade Engine For Client';

    protected array $tradeSymbols = ['TSLA', 'MSFT', 'GOOGL','BA', 'CRM', 'ABNB', 'DASH', 'UBER', 'AAPL', 'GD', 'NVDA', 'DIS', 'BIDU', 'UPS', 'SBUX', 'MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'V', 'NFLX', 'SPG', 'FDX'];

    protected array $shareQuantityPerTrade = [];

    protected array $consecutiveTrades = [];

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
        $symbol = $this->argument('symbol');

        $sharesPerTrade = 5;
        $consecutiveTrades = 0;

        Auth::loginUsingId(4, $remember = true);

        $this->info('Trade Engine Starting');
        // Loop until all orders have completed
        while (true) {
            Accounts::tokenPreFlight();

            // TODO Can user Trade??

            $runningCounts = [];
            $stoppedCounts = [];
            $tradeHalted = [];
            $goodSymbols = [];


            // Check for existing orders
            $orders = Order::where([
                ['user_id', '=', Auth::id()],
                ['tag', '=', 'AA_PuReWebDev'],
                ['instruction', '=', 'SELL'],
            ])->whereNotNull('instruction')->whereNotNull('positionEffect')->whereNotNull('price')->whereIn('status',['WORKING','PENDING_ACTIVATION'])->whereDate('created_at', Carbon::today())->get();

            $stoppedOrders = Order::where([
                ['user_id', '=', Auth::id()],
                ['status', '=', 'FILLED'],
                ['tag', '=', 'AA_PuReWebDev'],
//                ['created_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString()],
            ])->whereDate('created_at', Carbon::today())->whereNotNull('stopPrice')->get();

            foreach ($this->tradeSymbols as $tradeSymbol) {
                $stoppedCounts[$tradeSymbol] = $stoppedOrders->where('symbol','=',$tradeSymbol)->count();
                $runningCounts[$tradeSymbol] = $orders->where('symbol','=', $tradeSymbol)->count();
            }

            foreach ($this->tradeSymbols as $tradeSymbol) {
                // Set Some Default Values
                $this->shareQuantityPerTrade[$tradeSymbol] = 5;
                $tradeHalted[$tradeSymbol] = false;

                if (empty($this->consecutiveTrades[$tradeSymbol])) {
                    $this->consecutiveTrades[$tradeSymbol] = 0;
                }

                if ($stoppedCounts[$tradeSymbol] >= 1) {
                    $this->shareQuantityPerTrade[$tradeSymbol] = 2;
                    // TODO place a trade that recovers the loss, based onincreasing the quantity
                    Log::info("Symbol $tradeSymbol been stopped out. Halting Trading For It");
                }

                if ($stoppedCounts[$tradeSymbol] >= 15) {
                    $tradeHalted[$tradeSymbol] = true; // TODO break even -
                    // 1:1 once recovered, restart trade quantity
                    // trade post limits open cancel/replace
                    $this->shareQuantityPerTrade[$tradeSymbol] = 2;
                    Log::info("Symbol $tradeSymbol been stopped out. Halting Trading For It");
                }
                if ($runningCounts[$tradeSymbol] >= 5) {
                    $tradeHalted[$tradeSymbol] = true;
                }
            }


            foreach ($tradeHalted as $haltedSymbol => $haltedValue) {
                if ($haltedValue === false) {
                    array_push($goodSymbols, $haltedSymbol);
                }
            }

            Log::debug('Good symbols: ', $goodSymbols);
            Log::debug('Trading Halted: ' ,$tradeHalted);
            Log::debug('Sales Counts: ',$runningCounts);
            Log::debug('Stopped Counts: ',$stoppedCounts);

            // If all orders have completed, place a new OTO order
            if (count($goodSymbols) > 1) {
                $this->info('We have '. $orders->count() .' working orders. $consecutiveTrades is: '. $consecutiveTrades . ' and $sharesPerTrades is:'. $sharesPerTrade);
                // Grab The Current Price
                $quotes = TDAmeritrade::quotes($goodSymbols);

                // Place The Trades
                $this->getOrderResponse($quotes);

            } else {
                $this->info('Maximum Orders Placed, Waiting 10 seconds');
                sleep(10);// TODO event based
            }
        }
        $this->info('Trade Engine Gracefully Exiting');

        return 0;
    }

    /**
     * @param mixed $quotes
     * @return void
     * @throws \JsonException
     */
    private function getOrderResponse(mixed $quotes): void
    {
        foreach ($quotes as $quote) {

//            foreach ($this->consecutiveTrades as $consecutiveTrade) {
//                if ($consecutiveTrade[$quote->symbol] >= 10) {
//                    $this->shareQuantityPerTrade[$quote->symbol]++;
//                        $this->info('10 Successful Consecutive Trades, Increasing Trade Share Quantity To: '. $this->shareQuantityPerTrade[$quote->symbol]);
//
//                        if ($this->shareQuantityPerTrade[$quote->symbol] >= 10) {
//                            $this->shareQuantityPerTrade[$quote->symbol] = 10; // Fixed Quantity for now
//                        }
//                }
//            }

                $currentStockPrice = $quote->lastPrice;
//                $endPrice = $currentStockPrice - .01;
//                $endPrice = $currentStockPrice - .04;
//                for ($x = $currentStockPrice;
//                     $x >= $endPrice;
//                     $x -= 0.01) {

//                    OrderService::placeOtoOrder(
//                        number_format($x, 2, '.', ''),
//                        number_format($x + .05,2, '.', ''),
//                        number_format($x - 1.00, 2, '.', ''),
//                        $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]);
//                    OrderService::placeOtoOrder(
//                        number_format($x, 2, '.', ''),
//                        number_format($x + .10,2, '.', ''),
//                        number_format($x - 1.00, 2, '.', ''),
//                        $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]);
                    OrderService::placeOtoOrder(
                        number_format($currentStockPrice, 2, '.', ''),
                        number_format($currentStockPrice + .05,2, '.', ''),
                        number_format($currentStockPrice - 1.00, 2, '.', ''),
                        $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]);
                    OrderService::placeOtoOrder(
                        number_format($currentStockPrice, 2, '.', ''),
                        number_format($currentStockPrice + .10,2, '.', ''),
                        number_format($currentStockPrice - 1.00, 2, '.', ''),
                        $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]);


                    $message = "Order placed: Buy ".number_format($currentStockPrice, 2, '.',
                            '').", Sell Price: " . number_format($currentStockPrice + .10, 2,
                            '.', '') . ", Stop Price: " . number_format($currentStockPrice -
                            1.00, 2, '.', '') . "
                       Symbol: $quote->symbol, Quantity: ".$this->shareQuantityPerTrade[$quote->symbol];

                    $this->consecutiveTrades[$quote->symbol]++;
//                    Log::debug($message);
                    $this->info($message);
//                    usleep(500000);
//                }
//            }
        } // end for each quote. Now take a moment
        sleep(30);// TODO event based
    }

}
