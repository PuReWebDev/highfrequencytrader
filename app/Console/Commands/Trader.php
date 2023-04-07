<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Token;
use App\Services\AdminService;
use App\Services\OrderService;
use App\Services\PriceService;
use App\TDAmeritrade\MarketHours;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;

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
        $code = $this->argument('code');

        // Ensure we are logged in
        $authentication = collect([
            'grant_type' => config("tdameritrade.grant_type"),
            'access_type' => config('tdameritrade.access_type'),
            'code' => config('tdameritrade.code'),
            'client_id' => config('tdameritrade.client_id'),
            'redirect_uri' => config('tdameritrade.redirect_uri')
            ]);

        $authResponse = AdminService::login($authentication->toArray());

        $token = Token::updateOrCreate(
            ['id' => 1],
            [
                'token' => $authResponse->token,
                'refresh_token' => $authResponse->refresh_token
            ]
        );

        // Get stock symbol from command argument
        $symbol = $this->argument('symbol');


        if (MarketHours::isMarketOpen("EQUITY")) {
            // Loop until all orders have completed
            while (true) {
                // Check for existing orders
                $orders = OrderService::getOrders($token->access_token, $symbol);

                // If all orders have completed, place a new OTO order
                if (empty($orders)) {
                    // Get current price
                    $price = PriceService::getPrice($symbol);

//                    dd($price);
                    // Place OTO order
//                    OrderService::placeOtoOrder($token->access_token, $symbol, $price);
                    $OrderResponse = OrderService::placeOtoOrder($symbol);


                }
            }
        }

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
}
