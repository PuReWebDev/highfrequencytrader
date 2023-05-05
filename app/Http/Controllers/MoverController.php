<?php

namespace App\Http\Controllers;

use App\Models\Mover;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class MoverController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function index(): \Illuminate\Contracts\View\View
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
