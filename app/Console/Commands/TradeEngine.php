<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
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
    protected $signature = 'trader {symbol}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initiate Trade Engine For Client';

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
        $tradeQuantity = 5;
        $sharesPerTrade = 2;

        Auth::loginUsingId(4, $remember = true);

        $this->info('Trade Engine Starting');
        // Loop until all orders have completed
        while (true) {
            // Retrieve The Account Information
            Accounts::updateAccountData();
            // TODO Can user Trade??

            // Check for existing orders
            $orders = Order::where([
                ['user_id', '=', Auth::id()],
                ['status', '=', 'WORKING'],
                ['tag', '=', 'AA_PuReWebDev'],
            ])->get();

            $stoppedOrders = Order::where([
                ['user_id', '=', Auth::id()],
                ['status', '=', 'FILLED'],
                ['tag', '=', 'AA_PuReWebDev'],
                ['created_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString()],
            ])->whereNotNull('stopPrice')->get();

            if ($stoppedOrders->count() >= 5) {
                Log::info("We've been stopped out");
                sleep(180);
            }
            // If all orders have completed, place a new OTO order
            if ($orders->count() <= $tradeQuantity) {

                // Grab The Current Price
                $quotes = TDAmeritrade::quotes([$symbol,'AMZN']);

                // Place The Trades
                $this->getOrderResponse($quotes,$sharesPerTrade);
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
            if ($quote->symbol == 'TSLA') {
                $currentStockPrice = $quote->lastPrice;
                $endPrice = $currentStockPrice - .04;
                for ($x = $currentStockPrice;
                     $x >= $endPrice;
                     $x -= 0.01) {

//                    OrderService::placeOtoOrder(
//                        number_format($x, 2, '.', ''),
//                        number_format($x + .10,2, '.', ''),
//                        number_format($x - 1.00, 2, '.', ''),
//                        $quote->symbol, $sharesPerTrade);

                    $message = "Order placed: Buy ".number_format($x, 2, '.',
                            '').", Sell Price: " . number_format($x + .10, 2,
                            '.', '') . ", Stop Price: " . number_format($x -
                            1.00, 2, '.', '') . "
                       Symbol: $quote->symbol, $sharesPerTrade";

                    Log::debug($message);
                    $this->info($message);
                    usleep(500000);
                }
            }
        }
    }

}
