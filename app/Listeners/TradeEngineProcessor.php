<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Order;
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

//        $stockPositions = Position::where([
//            ['user_id', '=', Auth::id()],
//            ['currentDayProfitLoss', '>', 0],
//        ])->get();
//
//        foreach ($stockPositions as $stockPosition) {
//            array_unshift($this->tradeSymbols, $stockPosition['symbol']);
//        }

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
//            $this->shareQuantityPerTrade[$tradeSymbol] = 2;
            $this->shareQuantityPerTrade[$tradeSymbol] = self::quantityOverTime();
            $tradeHalted[$tradeSymbol] = false;

            if (empty($this->consecutiveTrades[$tradeSymbol])) {
                $this->consecutiveTrades[$tradeSymbol] = 0;
            }

            if ($stoppedCounts[$tradeSymbol] >= 1) {

                if ($stoppedCounts[$tradeSymbol] >= 3) {
                    $this->shareQuantityPerTrade[$tradeSymbol] = 2;
                }
                if ($stoppedCounts[$tradeSymbol] >= 5) {
                    $this->shareQuantityPerTrade[$tradeSymbol] = 2;
                    $tradeHalted[$tradeSymbol] = true;
                    Log::info("Symbol $tradeSymbol been stopped out 5x Halting Trading For It");
                }

                if (self::stoppedInLastFive($stoppedOrders,
                    $tradeSymbol)) {
                    $tradeHalted[$tradeSymbol] = true;
                    Log::info("Symbol $tradeSymbol been stopped out in Last 5 Minutes Halting Trading For It");
                }

                // TODO place a trade that recovers the loss, based onincreasing the quantity
            }

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

        // TODO Price Count touches what the most in a given time frame
        // If all orders have completed, place a new OTO order
        if (count($goodSymbols) > 1) {
            // Grab The Current Price
            $quotes = TDAmeritrade::quotes($goodSymbols);

            $now = Carbon::now()->setTimezone('America/New_York');

            if ($now->isBetween('09:30 AM', '04:00 PM')) {
                // Place The Trades
                $this->getOrderResponse($quotes);
            }

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

//            $previousQuote = Quote::where([
//                ['symbol','=', $quote->symbol],
//                ['id','<', $quote->id],
//                ])
//                ->orderBy('id', 'desc')
//                ->first();
//
//            if ($currentStockPrice < $previousQuote['lastPrice']) {
//                // Only buy when he price is otw up
//                Log::info('Skipping Buy of'. $quote->symbol .' Previous Price of '
//                    .$previousQuote['lastPrice'].' is Higher Than Current Price: '. $currentStockPrice);
//                continue;
//            }

            if ($currentStockPrice > 600) {
                Log::info('Stock Symbol '. $quote->symbol .' Too Expensive Right Now At: '. $quote->lastPrice . ' Skipping Orders');
                continue;
            }

            if (($quote->highPrice - .30) > ($currentStockPrice + .10)) {
                OrderService::placeOtoOrder(
                    number_format($currentStockPrice, 2, '.', ''),
                    number_format($currentStockPrice + .10,2, '.', ''),
                    number_format($currentStockPrice - 0.95, 2, '.', ''),
                    $quote->symbol, $this->shareQuantityPerTrade[$quote->symbol]);

                $message = "Order placed: Buy ".number_format($currentStockPrice, 2, '.',
                        '').", Sell Price: " . number_format($currentStockPrice + .10, 2,
                        '.', '') . ", Stop Price: " . number_format($currentStockPrice -
                        0.80, 2, '.', '') . "
                       Symbol: $quote->symbol, Quantity: ".$this->shareQuantityPerTrade[$quote->symbol];

                Log::debug($message);
                usleep(500000);
            }

            self::updateWatchList($quote);
        } // end for each quote. Now take a moment
    }

    /**
     * @return int
     */
    private static function quantityOverTime(): int
    {
        $timeQuantities = [
            ['start' => '09:30 AM', 'end' => '10:00 AM', 'quantity' => 1,],
            ['start' => '10:00 AM', 'end' => '10:30 AM', 'quantity' => 2,],
            ['start' => '10:30 AM', 'end' => '11:00 AM', 'quantity' => 3,],
            ['start' => '11:00 AM', 'end' => '11:30 AM', 'quantity' => 4,],
            ['start' => '11:30 AM', 'end' => '12:00 PM', 'quantity' => 5,],
            ['start' => '12:00 PM', 'end' => '12:30 PM', 'quantity' => 6,],
            ['start' => '12:30 PM', 'end' => '01:00 PM', 'quantity' => 7,],
            ['start' => '01:00 PM', 'end' => '01:30 PM', 'quantity' => 8,],
            ['start' => '01:30 PM', 'end' => '02:00 PM', 'quantity' => 9,],
            ['start' => '02:00 PM', 'end' => '02:30 PM', 'quantity' => 10,],
            ['start' => '02:30 PM', 'end' => '03:00 PM', 'quantity' => 11,],
            ['start' => '03:30 PM', 'end' => '04:00 PM', 'quantity' => 12,],
            ['start' => '02:00 PM', 'end' => '02:30 PM', 'quantity' => 13,],
            ['start' => '02:30 PM', 'end' => '02:45 PM', 'quantity' => 14,],
            ['start' => '02:45 PM', 'end' => '04:00 PM', 'quantity' => 2,],
        ];

        $now = Carbon::now()->setTimezone('America/New_York');

        foreach ($timeQuantities as $timeQuantity) {
            $start = Carbon::createFromFormat('H:i a', $timeQuantity['start']);
            $end =  Carbon::createFromFormat('H:i a', $timeQuantity['end']);
            if ($now->isBetween($start, $end)) {
                return $timeQuantity['quantity'];
            }
        }

        return 0;
    }

    /**
     * stoppedInLastFive
     * Check To See If The Symbol Has Been Stopped Out In The Last Five Minutes
     * @param mixed $stops
     * @param string $symbol
     * @return bool
     */
    private static function stoppedInLastFive(mixed $stops, string $symbol):
    bool
    {
        foreach ($stops as $stop) {
            if (!empty($stop['stopPrice']) && $stop['status'] === 'FILLED' &&
            $stop['symbol'] === $symbol) {
                $then = Carbon::createFromFormat('Y-m-d H:i:s', $stop['created_at']);
                if($then->diffInMinutes(Carbon::now()) < 5)
                {
                    return true;
                }
            }
        }

        return false;
    }
}
