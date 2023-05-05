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
    private float $profitsTotal = 0.00;
    private float $lossTotal = 0.00;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
//        $prices = TDAmeritrade::getPriceHistory('TSLA');
//        dd($prices);

//        Accounts::tokenPreFlight();
//        TDAmeritrade::getOrders();
//        TDAmeritrade::getOrders('FILLED');
        Accounts::updateAccountData();

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        if (empty($from_date)) {
//            $from_date = Carbon::yesterday();
            $from_date = Carbon::now();
        }

        if (empty($to_date)) {
            $to_date = Carbon::now();
        }


        $orders = Order::where([
            ['user_id','=', Auth::id()],
            ['tag', '=', 'AA_PuReWebDev'],
        ])->whereNotNull('instruction')->whereNotNull('positionEffect')
            ->whereBetween('created_at', [$from_date, $to_date])->orderBy('orderId', 'DESC')->get();
//            ->whereDate('created_at', Carbon::today())->orderBy('orderId', 'DESC')->get();

        $orders->each(function ($item, $key) {

            if (!empty($item['parentOrderId']) && $item['status'] === 'FILLED') {
                if (!empty($item['actualProfit']) && empty($item['stopPrice'])) {
                    $this->profitsTotal = (float) $item['actualProfit'] +
                        $this->profitsTotal;
                }

                if (!empty($item['stopPrice'])) {
                    $this->lossTotal = (float) $item['actualProfit'] +
                        $this->lossTotal;
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
            'profitsTotal' => $this->profitsTotal,
            'lossTotal' => $this->lossTotal,
            'pl' => $this->profitsTotal - $this->lossTotal,
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
