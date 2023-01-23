<?php

namespace App\Commands;

use App\Models\Admin;
use App\Models\Order;
use App\Services\AdminService;
use App\Services\OrderService;
use App\Services\PriceService;
//use App\TDAmeritrade\Order;
use App\TDAmeritrade\MarketHours;
use Illuminate\Console\Scheduling\Schedule;
//use LaravelZero\Framework\Commands\Command;
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handleOld()
    {
        $symbol = $this->argument('symbol');

        // Ensure we are logged in
        $adminService = new AdminService();
        if (!$adminService->isLoggedIn()) {
            $adminService->login();
            $admin = Admin::first();
            $admin->access_token = $adminService->getAccessToken();
            $admin->save();
        }

        while (true) {
            // Check for existing orders
            $orderService = new OrderService();
            $orders = $orderService->getOrders();

            // If all orders have completed, place a new One Triggers Another order
            if ($orders->allCompleted()) {
                $priceService = new PriceService();
                $price = $priceService->getPrice($symbol);

                $order = new Order();
                $order->symbol = $symbol;
                $order->price = $price;
                $order->profit = 1;
                $order->type = Order::TYPE_OTA;
                $order->save();

                $response = $orderService->placeOrder($order);

                if ($response->isSuccessful()) {
                    $this->info("Successfully placed One Triggers Another order for {$symbol} with profit of {$order->profit}");
                } else {
                    $this->error("Error placing One Triggers Another order for {$symbol}: " . $response->getErrorMessage());

                    // Check for rate limit error and sleep for appropriate amount of time if necessary
                    if ($response->isRateLimitError()) {
                        $waitTime = $response->getRateLimitResetTime() - time();
                        $this->info("Rate limit reached, sleeping for {$waitTime} seconds");
                        sleep($waitTime);
                    }
                }
            } else {
                $this->info("Waiting for orders to complete");
                sleep(60); // Sleep for 1 minute before checking orders again
            }
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function handle()
    {
        // Ensure we are logged in
        $token = AdminService::login();

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
                    OrderService::placeOtoOrder($token->access_token, $symbol, $price);
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
