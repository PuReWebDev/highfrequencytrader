<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Services\OrderService;
use App\TDAmeritrade\TDAmeritrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = Token::where('user_id', Auth::id())->get();

        Log::info('Trying to get the orders');
        if (TDAmeritrade::isAccessTokenExpired
            ($token['0']['updated_at']) === true) {
            // Time To Refresh The Token
            TDAmeritrade::saveTokenInformation(TDAmeritrade::refreshToken($token['0']['refresh_token']));
            Log::info('The Token Was Refreshed During This Process');
        }

        $quotes = TDAmeritrade::quotes(['TSLA','AMZN', 'GOOGL', 'VZ']);
        $numberOfTrades = 10;

        foreach ($quotes as $quote) {
            if ($quote->symbol == 'TSLA') {
                $bottomPrice = $quote->lastPrice - $numberOfTrades;
                for ($x = 0; $x <= 10; $x++) {
                    echo $quote->lastPrice - 0.01 ."<br>";
                }
                dd($quote->lastPrice);
            }
        }
        dd($quotes);

        $OrderResponse = OrderService::placeOtoOrder('180.00','190.00',
            'TSLA', 5);

        Log::debug('Order Response', $OrderResponse);

        dd($OrderResponse);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
