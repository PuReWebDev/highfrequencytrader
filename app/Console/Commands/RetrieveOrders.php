<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class RetrieveOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trader:orders {status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rapid Retrieve Trade Order Information';

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
        $status = $this->argument('status');

        Auth::loginUsingId(4, $remember = true);

        while (true) {
            TDAmeritrade::getOrders($status);
            $this->info($status.' Orders Retrieved. '.Carbon::now());
            usleep(500000);
        }

        $this->info('Trade Orders Retrieval Gracefully Exiting');
        return 0;
    }
}
