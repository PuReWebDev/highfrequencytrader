<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Quote;
use App\Http\Requests\{ QuoteIndexRequest, QuoteQueryRequest };
use App\TDAmeritrade\TDAmeritrade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QuoteApiController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param QuoteIndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(QuoteIndexRequest $request) : JsonResponse
    {
        try {
            return $this->success(
                TDAmeritrade::quotes($request->input('symbols'))
            );
        } catch(\Exception $e)
        {
            return $this->error($e);
        }
    }

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
            return $this->success(
                TDAmeritrade::quote($symbol)
            );
        } catch(\Exception $e)
        {
            return $this->error($e);
        }
    }

    public function search(QuoteQueryRequest $request) : JsonResponse
    {
        $symbols = explode(",", $request->symbols);
        $query = Quote::query();
        $query->whereIn("symbol", $symbols);
        if ($request->has('startDate')) {
            $request->whereDate('created_at' > Carbon::parse($request->input('startDate'))->timestamp);
        }

        if ($request->has('endDate')) {
            $request->whereDate('created_at' < Carbon::parse($request->input('endDate'))->timestamp);
        }

        return $this->success($query->get());
    }
}