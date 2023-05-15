<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Strategy;
use App\Models\WatchList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class StrategyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $strategies = Strategy::where('user_id', Auth::id())->get();

        return view('strategy', ['strategies' => $strategies]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create()
    {
        $symbols = WatchList::where('user_id', Auth::id())->get();

        return view('strategy-create', ['symbols' => $symbols]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \JsonException
     */
    public function store(Request $request)
    {
        $inputData = json_decode($request->getContent(), true, 512,
            JSON_THROW_ON_ERROR);
        $strategy_name = $inputData['strategy_name'];
        $enabled = $inputData['enabled'];
        $trade_quantity = $inputData['trade_quantity'];
        $number_of_trades = $inputData['number_of_trades'];
        $running_counts = $inputData['running_counts'];
        $max_stock_price = $inputData['max_stock_price'];
        $max_stops_allowed = $inputData['max_stops_allowed'];
        $change_quantity_after_stops = $inputData['change_quantity_after_stops'];
        $quantity_after_stop = $inputData['quantity_after_stop'];
        $stop_price = $inputData['stop_price'];
        $limit_price = $inputData['limit_price'];
        $limit_price_offset = $inputData['limit_price_offset'];
        $high_price_buffer= $inputData['high_price_buffer'];
        $profit = $inputData['profit'];

        $data = [
            'strategy_name' => $strategy_name,
            'enabled' => $enabled,
            'trade_quantity' => $trade_quantity,
            'number_of_trades' => $number_of_trades,
            'running_counts' => $running_counts,
            'max_stock_price' => $max_stock_price,
            'max_stops_allowed' => $max_stops_allowed,
            'change_quantity_after_stops' => $change_quantity_after_stops,
            'quantity_after_stop' => $quantity_after_stop,
            'stop_price' => $stop_price,
            'limit_price' => $limit_price,
            'limit_price_offset' => $limit_price_offset,
            'high_price_buffer' => $high_price_buffer,
            'profit' => $profit,
        ];


        Log::debug('New Strategy Posted', json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        Log::debug('New Strategy Posted', $data);
        return response()->json([
            'success' => true,
            'data' => $request->toArray(),
        ]);
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
