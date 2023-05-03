<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use App\TDAmeritrade\Accounts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SellOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trader:sellout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(): int
    {
        Auth::loginUsingId(4, $remember = true);
        Accounts::tokenPreFlight();

        $symbols = [

            ['symbol' => 'UBER', 'longQuantity' => 5],
            ['symbol' => 'AAPL', 'longQuantity' => 5],
            ['symbol' => 'GD', 'longQuantity' => 2],
            ['symbol' => 'DIS', 'longQuantity' => 5],
            ['symbol' => 'MSFT', 'longQuantity' => 2],
            ['symbol' => 'NFLX', 'longQuantity' => 4],
            ['symbol' => 'RTX', 'longQuantity' => 4],
            ['symbol' => 'AMZN', 'longQuantity' => 5],
            ['symbol' => 'GOOGL', 'longQuantity' => 5],
        ];

        foreach ($symbols as $symbol) {

            OrderService::sellOutMarket($symbol['symbol'], $symbol['longQuantity']);

            $message = "Order placed: Sell Out Symbol: ".$symbol['symbol'].",
            Quantity: ".$symbol['longQuantity'];

            Log::debug($message);

            sleep(5);
        }

        $this->info('Trade Completed');
        return 0;
    }
}
