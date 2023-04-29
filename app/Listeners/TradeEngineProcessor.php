<?php

namespace App\Listeners;

use App\Events\OrdersProcessed;
use Illuminate\Support\Facades\Auth;

class TradeEngineProcessor
{
    protected array $tradeSymbols = ['TSLA', 'MSFT', 'GOOGL','BA', 'CRM', 'ABNB', 'DASH', 'UBER', 'AAPL', 'GD', 'NVDA', 'DIS', 'BIDU', 'UPS', 'SBUX', 'MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'V', 'NFLX', 'SPG', 'FDX', 'LOW'];

    protected array $shareQuantityPerTrade = [];

    protected array $consecutiveTrades = [];
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        Auth::loginUsingId(4, $remember = true);
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrdersProcessed  $event
     * @return void
     */
    public function handle(OrdersProcessed $event)
    {
        dd($event);
    }
}
