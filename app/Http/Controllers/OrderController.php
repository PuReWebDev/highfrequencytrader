<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        Accounts::tokenPreFlight();
        TDAmeritrade::getOrders();

        $orders = Order::where('user_id', Auth::id())->orderBy('orderId', 'DESC')->get();

        $orders->each(function ($item, $key) {
            // Readable time vs raw timestamp
            $dt = Carbon::parse($item['enteredTime']);
            $item['enteredTime'] = $dt->toDateTimeString();

            // Now if this is a sell order, let's grab the buy and calculate
            // profit
            $item['tradeProfit'] = '';
            if (!empty($item['parentOrderId'])) {
                $order = Order::where('orderId', $item['parentOrderId'])->get();
                $item['tradeProfit'] = $order['0']['price'] - $item['price'];
            }

            return $item;
        });

        list($workingCount, $filledCount, $rejectedCount, $cancelledCount,
            $expiredCount) = TDAmeritrade::extracted($orders);

        return View::make('order', [
            'orders' => $orders,
            'filledCount' => $filledCount,
            'workingCount' => $workingCount,
            'rejectedCount' => $rejectedCount,
            'cancelledCount' => $cancelledCount,
            'expiredCount' => $expiredCount,
        ]);
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
