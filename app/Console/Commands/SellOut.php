<?php

namespace App\Console\Commands;

use App\Models\Position;
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
    public function handle()
    {
        Auth::loginUsingId(4, $remember = true);
        Accounts::tokenPreFlight();

        $symbols = Position::where([
            ['user_id','=', Auth::id()],
        ])->get();

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
