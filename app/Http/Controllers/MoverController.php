<?php

namespace App\Http\Controllers;

use App\Models\Mover;
use App\TDAmeritrade\TDAmeritrade;

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

        dd($movers);
//        $movers = TDAmeritrade::getMovers('$SPX.X');
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
