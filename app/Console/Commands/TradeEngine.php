<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Token;
use App\Services\OrderService;
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

        Auth::loginUsingId(4, $remember = true);

//        if (MarketHours::isMarketOpen("EQUITY")) {
        // Loop until all orders have completed
        while (true) {
            usleep(500000);
            sleep(4);
            // Retrieve The Account Information
            $accountResponse = Accounts::getAccounts();

            Accounts::saveAccountInformation($accountResponse);

            // Check for existing orders
            $orders = Order::where('user_id', Auth::id())->orderBy('enteredTime', 'DESC')->get();
            list($workingCount, $filledCount, $rejectedCount, $cancelledCount,
                $expiredCount) = TDAmeritrade::extracted($orders);

            // If all orders have completed, place a new OTO order
            if (empty($workingCount['WORKING'])) {

                $token = Token::where('user_id', Auth::id())->get();

                Log::info('Trying to get the orders');
                if (TDAmeritrade::isAccessTokenExpired
                    ($token['0']['updated_at']) === true) {
                    // Time To Refresh The Token
                    TDAmeritrade::saveTokenInformation(TDAmeritrade::refreshToken($token['0']['refresh_token']));
                    Log::info('The Token Was Refreshed During This Process');
                }

                $quotes = TDAmeritrade::quotes([$symbol,'AMZN', 'GOOGL', 'VZ']);

                // Place The Trades
                $this->getOrderResponse($quotes);



            }
        }
//        }

        return 0;
    }

    /**
     * @param mixed $quotes
     * @return array
     * @throws \JsonException
     */
    private function getOrderResponse(mixed $quotes): array
    {
        foreach ($quotes as $quote) {
            if ($quote->symbol == 'TSLA') {
                $currentStockPrice = $quote->lastPrice;
                $endPrice = $currentStockPrice - .04;
                for ($x = $currentStockPrice;
                     $x >= $endPrice;
                     $x -= 0.01) {

//                    echo $x .' and '. $x +.20 ."\n";
                    $OrderResponse = OrderService::placeOtoOrder
                    (number_format($x, 2, '.', ''), number_format($x + .10,
                        2, '.', ''),number_format($x - 1.00, 2, '.', ''),
                        $quote->symbol, 1);

                    Log::debug("Order placed: Buy ".number_format($x, 2, '.',
                            '')."," . number_format($x + .10, 2, '.', '') . ",
                        $quote->symbol, 1", $OrderResponse);
                    usleep(500000);
                }
            }
        }
        return $OrderResponse;
    }

}
