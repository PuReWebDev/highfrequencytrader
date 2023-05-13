<?php

namespace App\Console\Commands;

use App\Models\Position;
use App\Services\OrderService;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
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

//        $symbols = [


//            ['symbol' => 'AAPL', 'longQuantity' => 5],
//            ['symbol' => 'GD', 'longQuantity' => 2],
//            ['symbol' => 'DIS', 'longQuantity' => 5],
//            ['symbol' => 'MSFT', 'longQuantity' => 2],


//            ['symbol' => 'AMZN', 'longQuantity' => 5],
//            ['symbol' => 'GOOGL', 'longQuantity' => 5],
//            ['symbol' => 'UBER', 'longQuantity' => 5],
//        ];

        $symbols = Position::where('user_id', Auth::id())->get();

        foreach ($symbols as $symbol) {
            $goodSymbols = [];

            array_push($goodSymbols, $symbol['symbol']);

            $quotes = TDAmeritrade::quotes($goodSymbols);

            foreach ($quotes as $quote) {

//                OrderService::sellOutLimit($quote->symbol, $symbol['longQuantity'], $quote->lastPrice);
                if ($quote->symbol !== 'EATR') {
                    OrderService::sellOutMarket($quote->symbol, $symbol['longQuantity'],
                        $quote->lastPrice);

                    $message = "Order placed: Sell Out Symbol: ".$symbol['symbol'].",
            Quantity: ".$symbol['longQuantity']. " Stop Price: $quote->lastPrice";

                    Log::debug($message);

                    sleep(5);
                }


            }
//            OrderService::sellOutMarket($symbol['symbol'], $symbol['longQuantity']);
//            OrderService::sellOutMarket($symbol['symbol'], $symbol['longQuantity'], $quote->price);


        }

        $this->info('Trade Completed');
        return 0;
    }
}
