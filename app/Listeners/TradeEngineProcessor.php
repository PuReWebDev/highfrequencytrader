<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Order;
use App\Models\Position;
use App\Models\Quote;
use App\Models\WatchList;
use App\Services\OrderService;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TradeEngineProcessor
{
//    protected array $tradeSymbols = ['RBLX', 'XOM', 'WMT', 'WMG','JPM', 'ARKK','UBER','SPOT','PG','CLX','TSLA','CVX','CSX','COIN','TEAM','META',
//        'DASH', 'SBUX', 'SQ', 'AAPL', 'V','CRM', 'CSCO', 'LOW', 'Z', 'GIS', 'VZ','MSFT', 'AMZN', 'GOOGL','BA', 'ABNB', 'GD', 'NVDA', 'DIS', 'BIDU', 'UPS','MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'NFLX', 'SPG', 'FDX', 'BAH', 'VWM', 'RTX', 'KO', ];
    protected array $tradeSymbols = ['TSLA','DASH', 'SBUX','NFLX','RBLX','LOW','CRM','SQ','AAPL','ARKK','UBER','SPOT','MSFT', 'AMZN', 'GOOGL','NVDA', 'ABNB', 'DIS','BIDU', 'UPS','MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'SPG', 'FDX','TEAM','META'];
//    protected array $tradeSymbols = ['RBLX', 'XOM', 'WMT', 'WMG','JPM', 'ARKK','UBER','SPOT','PG','CLX','TSLA','CVX','CSX','COIN','TEAM','META',
//        'DASH', 'SBUX', 'SQ', 'AAPL', 'V','CRM', 'CSCO', 'LOW', 'Z', 'GIS', 'VZ','MSFT', 'AMZN', 'GOOGL','BA', 'ABNB', 'GD', 'NVDA', 'DIS', 'BIDU', 'UPS','MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'NFLX', 'SPG', 'FDX', 'BAH', 'VWM', 'RTX', 'KO','AMD', 'ADBE', 'ABNB', 'ALGN', 'AMZN', 'AMGN', 'AEP', 'ADI', 'ANSS', 'AAPL', 'AMAT', 'ASML', 'TEAM', 'ADSK', 'ATVI', 'ADP', 'AZN', 'BKR', 'AVGO', 'BIIB', 'BMRN', 'BKNG', 'CDNS', 'CHTR', 'CPRT', 'CSGP', 'CRWD', 'CTAS', 'CSCO', 'CMCSA', 'COST', 'CSX', 'CTSH', 'DDOG', 'DXCM', 'FANG', 'DLTR', 'EA', 'EBAY', 'ENPH', 'EXC', 'FAST', 'GFS', 'META', 'FISV', 'FTNT', 'GILD', 'GOOG', 'GOOGL', 'HON', 'ILMN', 'INTC', 'INTU', 'ISRG', 'MRVL', 'IDXX', 'JD', 'KDP', 'KLAC', 'KHC', 'LRCX', 'LCID', 'LULU', 'MELI', 'MAR', 'MCHP', 'MDLZ', 'MRNA', 'MNST', 'MSFT', 'MU', 'NFLX', 'NVDA', 'NXPI', 'ODFL', 'ORLY', 'PCAR', 'PANW', 'PAYX', 'PDD', 'PYPL', 'PEP', 'QCOM', 'REGN', 'RIVN', 'ROST', 'SIRI', 'SGEN', 'SBUX', 'SNPS', 'TSLA', 'TXN', 'TMUS', 'VRSK', 'VRTX', 'WBA', 'WBD', 'WDAY', 'XEL', 'ZM', 'ZS',];

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
     * @param mixed $quote
     */
    private static function updateWatchList(mixed $quote): void
    {
        WatchList::updateOrCreate([
            'user_id' => Auth::id(),
            'symbol' => $quote->symbol
        ], [
            'user_id' => Auth::id(),
            'symbol' => $quote->symbol,
            'enabled' => true,
        ]);
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

//        Accounts::updateAccountData();
        // TODO Can user Trade??

        $runningCounts = [];
        $stoppedCounts = [];
        $tradeHalted = [];
        $goodSymbols = [];

        // Check for existing orders
        $orders = Order::where([
            ['user_id', '=', Auth::id()],
            ['tag', '=', 'AA_PuReWebDev'],
//            ['instruction', '=', 'SELL'],
        ])->whereIn('instruction',['SELL','BUY'])->whereNotNull('instruction')->whereNotNull('positionEffect')->whereNotNull('price')->whereIn('status',['WORKING','PENDING_ACTIVATION'])->whereDate('created_at', Carbon::today())->get();

        $stoppedOrders = Order::where([
            ['user_id', '=', Auth::id()],
            ['status', '=', 'FILLED'],
            ['tag', '=', 'AA_PuReWebDev'],
//                ['created_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString()],
        ])->whereDate('created_at', Carbon::today())->whereNotNull('stopPrice')->get();

//        TDAmeritrade::updateMovers();
//
//        $movers = Mover::whereDate('created_at', Carbon::today())
//            ->orderBy('change', 'desc')->limit(1)->get();

        $stockPositions = Position::where([
            ['user_id', '=', Auth::id()],
            ['currentDayProfitLoss', '>', 0],
        ])->get();

        foreach ($stockPositions as $stockPosition) {
            array_unshift($this->tradeSymbols, $stockPosition['symbol']);
        }

//        foreach ($movers as $mover) {
//            array_unshift($this->tradeSymbols, $mover['symbol']);
//        }

        $this->tradeSymbols = array_unique($this->tradeSymbols);

        foreach ($this->tradeSymbols as $tradeSymbol) {
            $stoppedCounts[$tradeSymbol] = $stoppedOrders->where('symbol','=',$tradeSymbol)->count();
            $runningCounts[$tradeSymbol] = $orders->where('symbol','=', $tradeSymbol)->count();
        }

        foreach ($this->tradeSymbols as $tradeSymbol) {
            // Set Some Default Values
            $this->shareQuantityPerTrade[$tradeSymbol] = 2;
            $tradeHalted[$tradeSymbol] = false;

            if (empty($this->consecutiveTrades[$tradeSymbol])) {
                $this->consecutiveTrades[$tradeSymbol] = 0;
            }

            if ($stoppedCounts[$tradeSymbol] >= 1) {

                $this->shareQuantityPerTrade[$tradeSymbol] = 10;

                // TODO place a trade that recovers the loss, based onincreasing the quantity
                Log::info("Symbol $tradeSymbol been stopped out. Halting Trading For It");
            }

            if ($stoppedCounts[$tradeSymbol] >= 1) {
                $tradeHalted[$tradeSymbol] = true; // TODO break even -
                // 1:1 once recovered, restart trade quantity
                // trade post limits open cancel/replace
                $this->shareQuantityPerTrade[$tradeSymbol] = 2;
                Log::info("Symbol $tradeSymbol been stopped out. Halting Trading For It");
            }
//            if ($runningCounts[$tradeSymbol] >= 5) {
//            if ($runningCounts[$tradeSymbol] >= 3) {
//                $this->shareQuantityPerTrade[$tradeSymbol] = 5;
////                $tradeHalted[$tradeSymbol] = true;
//            }
            if ($runningCounts[$tradeSymbol] >= 1) {
                $this->shareQuantityPerTrade[$tradeSymbol] = 2;
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

            $previousQuote = Quote::where('symbol', $quote->symbol)->orderBy('id', 'desc')
                ->first();

            if ($currentStockPrice < $previousQuote['lastPrice']) {
                // Only buy when he price is otw up
                Log::info('Skipping Buy of'. $quote->symbol .' Previous Price of '
                    .$previousQuote['lastPrice'].' is Higher Than Current Price: '. $currentStockPrice);
                continue;
            }

            if ($currentStockPrice > 600) {
                Log::info('Stock Symbol '. $quote->symbol .' Too Expensive Right Now At: '. $quote->lastPrice . ' Skipping Orders');
                continue;
            }

//            foreach ($movers as $mover) {
//                if ($quote->symbol === $mover['symbol']) {
//                    if (($quote->highPrice - 0.50) > ($currentStockPrice + 1.00)) {
//
//                        OrderService::placeOtoOrder(
//                            number_format($currentStockPrice, 2, '.', ''),
//                            number_format($currentStockPrice + 1.00,2, '.', ''),
//                            number_format($currentStockPrice - 3.00, 2, '.', ''),
//                            $quote->symbol, 5);
//
//                        $message = "Mover Order placed: Buy ".number_format
//                            ($currentStockPrice, 2, '.',
//                                '').", Sell Price: " . number_format($currentStockPrice + .10, 2,
//                                '.', '') . ", Stop Price: " . number_format($currentStockPrice -
//                                3.00, 2, '.', '') . "
//                       Symbol: $quote->symbol, Quantity: ".$this->shareQuantityPerTrade[$quote->symbol];
//
//                        Log::debug($message);
////                        usleep(500000);
//
//                    }
//                }
//            }

            if (($quote->highPrice - .40) > ($currentStockPrice + .10)) {
                OrderService::placeOtoOrder(
                    number_format($currentStockPrice, 2, '.', ''),
                    number_format($currentStockPrice + .10,2, '.', ''),
                    number_format($currentStockPrice - 0.80, 2, '.', ''),
                    $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]);

                $message = "Order placed: Buy ".number_format($currentStockPrice, 2, '.',
                        '').", Sell Price: " . number_format($currentStockPrice + .10, 2,
                        '.', '') . ", Stop Price: " . number_format($currentStockPrice -
                        0.80, 2, '.', '') . "
                       Symbol: $quote->symbol, Quantity: ".$this->shareQuantityPerTrade[$quote->symbol];

                Log::debug($message);
//                usleep(500000);
            }

//            foreach ($movers as $mover) {
//                if ($quote->symbol === $mover['symbol']) {
//                    if (($quote->highPrice - .60) > ($currentStockPrice + 1.00)) {
//                        OrderService::placeOtoOrder(
//                            number_format($currentStockPrice, 2, '.', ''),
//                            number_format($currentStockPrice + 1.00,2, '.', ''),
//                            number_format($currentStockPrice - 2.00, 2, '.', ''),
//                            $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]);
//                    }
//                }
//            }

            self::updateWatchList($quote);




//            $this->consecutiveTrades[$quote->symbol]++;


        } // end for each quote. Now take a moment
    }
}
