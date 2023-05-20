<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Order;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
            $from_date = Carbon::today();
        }

        if (empty($to_date)) {
//            $to_date = Carbon::today();
            $to_date = Carbon::now();
            $to_date = $to_date->toDateTimeString();
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

        if ($orders->count() > 1) {
            dd(self::buildStatistics($orders));
        }


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
            'statistics' => self::buildStatistics($orders),
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

    /**
     * @param Collection $orders
     * @return array
     */
    private static function buildStatistics(Collection $orders): array
    {
        $statistics = [];
        $filteredOrders = $orders->unique('symbol');

        $filteredOrders->values()->all();

        foreach ($filteredOrders as $symbol) {
            $filtered = $orders->where('symbol', '=', $symbol['symbol']);
            $stats = TDAmeritrade::extracted($filtered);
            dd($stats);
            $statistics[$symbol['symbol']] = [
                'workingCount' => $stats['0'],
                'filledCount' => $stats['1'],
                'rejectedCount' =>$stats['2'],
                'cancelledCount' => $stats['3'],
                'expiredCount' => $stats['4'],
                'stoppedCount' => $stats['5'],
                'stoppedTotalCount' => $stats['6'],
            ];
        }

        return $statistics;
    }
}
