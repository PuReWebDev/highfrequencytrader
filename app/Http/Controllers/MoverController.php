<?php

namespace App\Http\Controllers;

use App\Models\Mover;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class MoverController extends Controller
{
    protected array $tradeSymbols = ['UBER','SPOT', 'PG', 'CLX','TSLA', 'DASH', 'SBUX', 'SQ', 'AAPL', 'V','CRM', 'CSCO', 'LOW', 'Z', 'GIS', 'VZ','MSFT', 'AMZN', 'GOOGL','BA', 'ABNB', 'GD', 'NVDA', 'DIS', 'BIDU', 'UPS','MCD', 'MMM', 'CSCO', 'CVS', 'WM', 'NFLX', 'SPG', 'FDX', 'BAH', 'VWM', 'RTX', 'KO', ];

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function index()
    {
        $movers = TDAmeritrade::getMovers('$COMPX');
        foreach ($movers as $mover) {
            self::saveMovers($mover);
        }

        usleep(500000);
        $spxMovers = TDAmeritrade::getMovers('$SPX.X');
        foreach ($spxMovers as $spxMover) {
            self::saveMovers($spxMover);
        }

        $movers = Mover::whereDate('created_at', Carbon::today())
            ->orderBy('change', 'desc')->get();

        $symbols = [];

        foreach ($movers as $mover) {
            array_unshift($this->tradeSymbols, $mover['symbol']);
        }

        $this->tradeSymbols = array_unique($this->tradeSymbols);
        dd($this->tradeSymbols);

        return View::make('movers', [
            'movers' => $movers,
        ]);
    }

    private static function saveMovers(array $data)
    {
        Mover::updateOrCreate([
            'symbol' => $data['symbol'],
        ],[
            'change' => $data['change'],
            'description' => $data['description'],
            'direction' => $data['direction'],
            'last' => $data['last'],
            'symbol' => $data['symbol'],
            'totalVolume' => $data['totalVolume'],
        ]);
    }
}
