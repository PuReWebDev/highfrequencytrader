<?php

namespace App\Http\Controllers;

use App\TDAmeritrade\TDAmeritrade;

class MoverController extends Controller
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function index()
    {
        TDAmeritrade::getMovers('$COMPX');
    }
}
