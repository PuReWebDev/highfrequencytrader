<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Token;
use App\Services\OrderService;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Trader extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'trader {symbol}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Trader command to place One Triggers Another orders';

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function handle()
    {
        // Get stock symbol from command argument
        $symbol = $this->argument('symbol');

        Auth::loginUsingId(4, $remember = true);

//        if (MarketHours::isMarketOpen("EQUITY")) {
            // Loop until all orders have completed
            while (true) {
                // Check for existing orders
                $orders = Order::where('user_id', Auth::id())->orderBy('enteredTime', 'DESC')->get();
                list($workingCount, $filledCount, $rejectedCount, $cancelledCount,
                    $expiredCount) = TDAmeritrade::extracted($orders);

                // If all orders have completed, place a new OTO order
                if ($workingCount > 0) {

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

                    usleep(500000);
                    // Retrieve The Account Information
                    $accountResponse = Accounts::getAccounts();

                    Accounts::saveAccountInformation($accountResponse);

                }
            }
//        }

        return 0;
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        //
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
                        2, '.', ''),
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
