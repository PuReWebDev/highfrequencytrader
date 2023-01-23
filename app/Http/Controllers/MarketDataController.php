<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\TDAmeritrade\Admin;
use App\TDAmeritrade\AcctActivity;
use App\TDAmeritrade\ChartHistory;
use App\TDAmeritrade\LevelOne;
use App\TDAmeritrade\Order;

class MarketDataController extends Controller
{
    /**
     * The Admin instance.
     *
     * @var \App\TDAmeritrade\Admin
     */
    protected $admin;

    /**
     * The AcctActivity instance.
     *
     * @var \App\TDAmeritrade\AcctActivity
     */
    protected $acctActivity;

    /**
     * The ChartHistory instance.
     *
     * @var \App\TDAmeritrade\ChartHistory
     */
    protected $chartHistory;

    /**
     * The LevelOne instance.
     *
     * @var \App\TDAmeritrade\LevelOne
     */
    protected $levelOne;

    /**
     * The Order instance.
     *
     * @var \App\TDAmeritrade\Order
     */
    protected $order;

    /**
     * Create a new MarketDataController instance.
     *
     * @param \App\TDAmeritrade\Admin  $admin
     * @param \App\TDAmeritrade\AcctActivity  $acctActivity
     * @param \App\TDAmeritrade\ChartHistory  $chartHistory
     * @param \App\TDAmeritrade\LevelOne  $levelOne
     * @param \App\TDAmeritrade\Order  $order
     * @return void
     */
    public function __construct(Admin $admin, AcctActivity $acctActivity, ChartHistory $chartHistory, LevelOne $levelOne, Order $order)
    {
        $this->admin = $admin;
        $this->acctActivity = $acctActivity;
        $this->chartHistory = $chartHistory;
        $this->levelOne = $levelOne;
        $this->order = $order;
    }

    /**
     * Check the status of the WebSocket connection.
     *
     * @return \Illuminate\Http\Response
     */
    public function heartbeat(): \Illuminate\Http\Response
    {
        $status = $this->admin->heartbeat();

        return response()->json($status);
    }

    /**
     * Get account activity data for the authenticated user.
     *
     * @param  string  $accountId
     * @param  string  $activityType
     * @param  string  $startDate
     * @param  string  $endDate
     * @return \Illuminate\Http\Response
     */
    public function acctActivity(string $accountId, string $activityType = '', string $startDate = '', string $endDate = ''): \Illuminate\Http\Response
    {
        $data = $this->acctActivity->get($accountId, $activityType, $startDate, $endDate);

        return response()->json($data);
    }

    /**
     * Get chart history data for a given symbol.
     *
     * @param  string  $symbol
     * @param  string  $periodType
     * @param  int  $period
     * @param  string  $frequencyType
     * @param  int  $frequency
     * @param  string  $startDate
     * @param  string  $endDate
     * @param  bool  $needExtendedHoursData
     * @return \Illuminate\Http\Response
     */
    public function chartHistory(string $symbol, string $periodType = '', int $period = 1, string $frequencyType = '', int $frequency = 1, string $startDate = '', string $endDate = '', bool $needExtendedHoursData = false): \Illuminate\Http\Response
    {
        $data = $this->chartHistory->get($symbol, $periodType, $period, $frequencyType, $frequency, $startDate, $endDate, $needExtendedHoursData);

        return response()->json($data);
    }

    /**
     * Get level one data for a given symbol.
     *
     * @param  string  $symbol
     * @return \Illuminate\Http\Response
     */
    public function levelOne(string $symbol): \Illuminate\Http\Response
    {
        $data = $this->levelOne->get($symbol);

        return response()->json($data);
    }

    /**
     * Place an order for the authenticated user.
     *
     * @param  string  $accountId
     * @param  array  $order
     * @return \Illuminate\Http\Response
     */
    public function placeOrder(string $accountId, array $order): \Illuminate\Http\Response
    {
        $response = $this->order->place($accountId, $order);

        return response()->json($response);
    }

}
