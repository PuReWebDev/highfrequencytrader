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

    protected array $tradeSymbols = ['TSLA', 'MSFT', 'GOOGL','BA', 'CRM'];

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

        $sharesPerTrade = 2;
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
                $tradeHalted[$tradeSymbol] = false;

                if ($stoppedCounts[$tradeSymbol] >= 5) {
                    $tradeHalted[$tradeSymbol] = true;
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


            // If all orders have completed, place a new OTO order
            if (count($goodSymbols) > 1) {
                $this->info('We have '. $orders->count() .' working orders. $consecutiveTrades is: '. $consecutiveTrades . ' and $sharesPerTrades is:'. $sharesPerTrade);
                // Grab The Current Price
                $quotes = TDAmeritrade::quotes($goodSymbols);

                if ($consecutiveTrades >= 10) {
                    $sharesPerTrade++;
                    $this->info('10 Successful Consecutive Trades, Increasing Trade Share Quantity To: '. $sharesPerTrade);
                    $consecutiveTrades = 0;

                    if ($sharesPerTrade >= 10) {
                        $sharesPerTrade = 10; // Fixed Quantity for now
                    }

                }
                // Place The Trades
                $this->getOrderResponse($quotes,$sharesPerTrade);
                $consecutiveTrades++;
            } else {
                $this->info('Maximum Orders Placed, Waiting 10 seconds');
                sleep(10);
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
    private function getOrderResponse(mixed $quotes,int $sharesPerTrade): void
    {
        foreach ($quotes as $quote) {
//            if ($quote->symbol == 'TSLA') {
                $currentStockPrice = $quote->lastPrice;
                $endPrice = $currentStockPrice - .02;
//                $endPrice = $currentStockPrice - .04;
                for ($x = $currentStockPrice;
                     $x >= $endPrice;
                     $x -= 0.01) {

                    OrderService::placeOtoOrder(
                        number_format($x, 2, '.', ''),
                        number_format($x + .05,2, '.', ''),
                        number_format($x - 1.00, 2, '.', ''),
                        $quote->symbol, $sharesPerTrade);
                    OrderService::placeOtoOrder(
                        number_format($x, 2, '.', ''),
                        number_format($x + .10,2, '.', ''),
                        number_format($x - 1.00, 2, '.', ''),
                        $quote->symbol, $sharesPerTrade);


                    $message = "Order placed: Buy ".number_format($x, 2, '.',
                            '').", Sell Price: " . number_format($x + .10, 2,
                            '.', '') . ", Stop Price: " . number_format($x -
                            1.00, 2, '.', '') . "
                       Symbol: $quote->symbol, Quantity: $sharesPerTrade";

//                    Log::debug($message);
                    $this->info($message);
//                    usleep(500000);
                }
//            }
        }
    }

}
