<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Balance;
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
//        TDAmeritrade::getOrders();
        Accounts::updateAccountData();

        $orders = Order::where([
            ['user_id','=', Auth::id()],
            ['tag', '=', 'AA_PuReWebDev'],
        ])->whereDate('created_at', Carbon::today())->orderBy('orderId', 'DESC')->get();

        $orders->each(function ($item, $key) {
            // Readable time vs raw timestamp
            $dt = Carbon::parse($item['enteredTime']);
            $item['enteredTime'] = $dt->toDateTimeString();

            // Now if this is a sell order, let's grab the buy and calculate
            // profit
            $item['tradeProfit'] = '';
            if (!empty($item['parentOrderId'])) {
                $order = Order::where('orderId', $item['parentOrderId'])->get();
                if (!empty($item['price']) && !empty($order['0']['price'])) {
                    $item['tradeProfit'] = number_format((float)$item['price'], 2, '.', '') - number_format((float)$order['0']['price'], 2, '.', '');
                    $item['tradeProfit'] = number_format((float)$item['tradeProfit'], 2, '.', '');
                    $item['tradeProfit'] = '$'.number_format((float)$item['tradeProfit'], 2, '.', '') * $order['0']['quantity'];
                }

                if (!empty($item['stopPrice']) && !empty($order['0']['price'])) {
                    $item['tradeProfit'] = number_format((float)$item['price'], 2, '.', '') - number_format((float)$order['0']['stopPrice'], 2, '.', '');
                    $item['tradeProfit'] = number_format((float)$item['tradeProfit'], 2, '.', '');
                    $item['tradeProfit'] = '$'.number_format((float)$item['tradeProfit'], 2, '.', '') * $order['0']['quantity'];
                }
            }

            return $item;
        });

        list($workingCount, $filledCount, $rejectedCount, $cancelledCount,
            $expiredCount, $stoppedCount,$stoppedTotalCount) = TDAmeritrade::extracted($orders);

        $Balance = Balance::where('user_id', Auth::id())->get();

        return View::make('order', [
            'orders' => $orders,
            'filledCount' => $filledCount,
            'workingCount' => $workingCount,
            'rejectedCount' => $rejectedCount,
            'cancelledCount' => $cancelledCount,
            'expiredCount' => $expiredCount,
            'stoppedCount' => $stoppedCount,
            'balance' => $Balance,
            'stoppedTotalCount' => $stoppedTotalCount,
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
