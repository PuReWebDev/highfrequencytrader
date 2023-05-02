<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\WatchList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class WatchlistSeeder extends Seeder
{
    protected array $tradeSymbols = ['TSLA', 'MSFT', 'AMZN', 'GOOGL','BA',
        'CRM', 'ABNB', 'DASH', 'UBER', 'AAPL', 'GD', 'NVDA', 'DIS', 'BIDU', 'UPS', 'SBUX', 'MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'V', 'NFLX', 'SPG', 'FDX', 'LOW', 'BAH', 'SPOT', 'CLX', 'VWM', 'GIS', 'RTX', 'PG', 'KO', 'Z', 'VZ', 'SQ'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Auth::loginUsingId(4, $remember = true);

        foreach ($this->tradeSymbols as $tradeSymbol) {
            WatchList::updateOrCreate([
                'user_id' => Auth::id(),
                'symbol' => $tradeSymbol
            ],[
                'user_id' => Auth::id(),
                'symbol' => $tradeSymbol,
                'enabled' => true,
            ]);
        }
    }
}
