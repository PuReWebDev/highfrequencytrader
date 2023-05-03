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
    public function handle()
    {
        Auth::loginUsingId(4, $remember = true);
        Accounts::tokenPreFlight();



        $symbols = Position::where([
            ['user_id','=', Auth::id()],
            ['enabled','=', 1],
        ])->get();

        foreach ($symbols as $symbol) {
            $symbolGroup = [];
//            TDAmeritrade::getPriceHistory($symbol['symbol']);

            array_push($symbolGroup, $symbol['symbol']);

            $quotes = TDAmeritrade::quotes($symbolGroup);

            foreach ($quotes as $quote) {

                $currentStockPrice = $quote->lastPrice;

                OrderService::sellOutMarket($quote->symbol, $symbol['longQuantity']);

//                usleep(500000);


                $message = "Order placed: Sell Out ".number_format
                    ($currentStockPrice, 2, '.',
                        '').", Sell Price: " . number_format($currentStockPrice + .10, 2,
                        '.', '') . ", Symbol: $quote->symbol, Quantity: ".$symbol['longQuantity'];

                Log::debug($message);

                sleep(5);
            }
        }

        $this->info('Trade Completed');
        return 0;
    }
}
