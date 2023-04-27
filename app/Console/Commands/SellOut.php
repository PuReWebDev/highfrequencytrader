<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use App\TDAmeritrade\Accounts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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

        $x = 160.00;
        OrderService::placeOtoOrder(
            number_format($x, 2, '.', ''),
            number_format($x + .10,2, '.', ''),
            number_format($x - 1.00, 2, '.', ''),
            'TSLA', 129);

        $this->info('Trade Completed');
        return 0;
    }
}
