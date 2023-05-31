<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Account;
use App\Models\Balance;
use App\Models\Order;
use App\Models\Position;
use App\Models\Token;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class PriceHistoryApiController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $symbol)
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