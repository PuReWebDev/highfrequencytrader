<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Order;
use App\Services\OrderService;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TradeEngineProcessor
{
    protected array $tradeSymbols = ['TSLA', 'MSFT', 'GOOGL','BA', 'CRM', 'ABNB', 'DASH', 'UBER', 'AAPL', 'GD', 'NVDA', 'DIS', 'BIDU', 'UPS', 'SBUX', 'MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'V', 'NFLX', 'SPG', 'FDX', 'LOW'];

    protected array $shareQuantityPerTrade = [];

    protected array $consecutiveTrades = [];
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        Auth::loginUsingId(4, $remember = true);
    }

    /**
     * Handle the event.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function handle()
    {
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

                if ($stoppedCounts[$tradeSymbol] >= 12) {
                    $this->shareQuantityPerTrade[$tradeSymbol] = 2;
                }

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
            // Grab The Current Price
            $quotes = TDAmeritrade::quotes($goodSymbols);

            // Place The Trades
            $this->getOrderResponse($quotes);
        }
    }

    /**
     * @param mixed $quotes
     * @return void
     * @throws \JsonException
     */
    private function getOrderResponse(mixed $quotes): void
    {
        foreach ($quotes as $quote) {

            $currentStockPrice = $quote->lastPrice;

            OrderService::placeOtoOrder(
                number_format($currentStockPrice, 2, '.', ''),
                number_format($currentStockPrice + .05,2, '.', ''),
                number_format($currentStockPrice - 1.00, 2, '.', ''),
                $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]
                * 2);
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
                    Log::debug($message);

        } // end for each quote. Now take a moment
    }
}
