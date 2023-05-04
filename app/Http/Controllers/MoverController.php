<?php

namespace App\Http\Controllers;

use App\Models\Mover;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;

class MoverController extends Controller
{
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

        $movers = Mover::whereDate('created_at', Carbon::today())->get();

        dd($movers);
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
