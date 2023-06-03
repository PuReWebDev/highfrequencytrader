<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\TDAmeritrade\TDAmeritrade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PriceHistoryApiController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param string $symbol
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $symbol) : JsonResponse
    {
        try {
            return $this->success(TDAmeritrade::getPriceHistory( 
                $symbol, 
                'day',
                1,
                'minute',
                1,
                '',
                '',
                'true'
            )['candles']);
        } catch(\Exception $e) {
            return $this->error($e);
        }
    }
}