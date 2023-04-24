<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

class AccountController extends Controller
{
    /**
     * @param $securitiesAccount
     * @return mixed
     */
    private static function storeAccountInfo($securitiesAccount): mixed
    {
        return Account::updateOrCreate(
            ['user_id' => Auth::id(), 'accountId' => $securitiesAccount['accountId']],
            [
                'user_id' => Auth::id() ?? null,
                'accountId' => $securitiesAccount['accountId'] ?? null,
                'type' => $securitiesAccount['type'] ?? null,
                'roundTrips' => $securitiesAccount['roundTrips'],
                'isDayTrader' => $securitiesAccount['isDayTrader'],
                'isClosingOnlyRestricted' => $securitiesAccount['isClosingOnlyRestricted'],
            ]
        );
    }

    /**
     * @param $accountId
     * @param mixed $projectedBalancesValue
     */
    private static function saveProjectedBalancesInformation($accountId, mixed $projectedBalancesValue): void
    {
        Balance::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'accountId' => $accountId,
                'balanceType' => 'projectedBalances'
            ],
            [
                'user_id' => Auth::id(),
                'accountId' => $accountId,
                'balanceType' => 'projectedBalances',
                'availableFunds' => $projectedBalancesValue['availableFunds'] ?? null,
                'availableFundsNonMarginableTrade' => $projectedBalancesValue['availableFundsNonMarginableTrade'] ?? null,
                'buyingPower' => $projectedBalancesValue['buyingPower'] ?? null,
                'dayTradingBuyingPower' => $projectedBalancesValue['dayTradingBuyingPower'] ?? null,
                'dayTradingBuyingPowerCall' => $projectedBalancesValue['dayTradingBuyingPowerCall'] ?? null,
                'maintenanceCall' => $projectedBalancesValue['maintenanceCall'] ?? null,
                'regTCall' => $projectedBalancesValue['regTCall'] ?? null,
                'isInCall' => $projectedBalancesValue['isInCall'] ?? null,
                'stockBuyingPower' => $projectedBalancesValue['stockBuyingPower'] ?? null,
            ]
        );
    }

    /**
     * @param $accountId
     * @param mixed $currentBalanceValue
     */
    private static function saveCurrentBalancesInformation($accountId, mixed $currentBalanceValue): void
    {
        Balance::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'accountId' => $accountId,
                'balanceType' => 'currentBalances'
            ],
            [
                'user_id' => Auth::id(),
                'accountId' => $accountId,
                'balanceType' => 'currentBalances',
                'accruedInterest' => $currentBalanceValue['accruedInterest']
                    ?? null,
                'cashBalance' => $currentBalanceValue['cashBalance'] ?? null,
                'cashReceipts' => $currentBalanceValue['cashReceipts'] ?? null,
                'longOptionMarketValue' => $currentBalanceValue['longOptionMarketValue'] ?? null,
                'liquidationValue' =>
                    $currentBalanceValue['liquidationValue'] ?? null,
                'longMarketValue' => $currentBalanceValue['longMarketValue']
                    ?? null,
                'moneyMarketFund' => $currentBalanceValue['moneyMarketFund']
                    ?? null,
                'savings' => $currentBalanceValue['savings'] ?? null,
                'shortMarketValue' =>
                    $currentBalanceValue['shortMarketValue'] ?? null,
                'pendingDeposits' => $currentBalanceValue['pendingDeposits']
                    ?? null,
                'availableFunds' => $currentBalanceValue['availableFunds'] ??
                    null,
                'availableFundsNonMarginableTrade' => $currentBalanceValue['availableFundsNonMarginableTrade'] ?? null,
                'buyingPower' => $currentBalanceValue['buyingPower'] ?? null,
                'buyingPowerNonMarginableTrade' => $currentBalanceValue['buyingPowerNonMarginableTrade'] ?? null,
                'dayTradingBuyingPower' => $currentBalanceValue['dayTradingBuyingPower'] ?? null,
                'equity' => $currentBalanceValue['equity'] ?? null,
                'equityPercentage' =>
                    $currentBalanceValue['equityPercentage'] ?? null,
                'longMarginValue' => $currentBalanceValue['longMarginValue']
                    ?? null,
                'maintenanceCall' => $currentBalanceValue['maintenanceCall']
                    ?? null,
                'maintenanceRequirement' => $currentBalanceValue['maintenanceRequirement'] ?? null,
                'marginBalance' => $currentBalanceValue['marginBalance'] ??
                    null,
                'regTCall' => $currentBalanceValue['regTCall'] ?? null,
                'shortBalance' => $currentBalanceValue['shortBalance'] ?? null,
                'shortMarginValue' =>
                    $currentBalanceValue['shortMarginValue'] ?? null,
                'shortOptionMarketValue' => $currentBalanceValue['shortOptionMarketValue'] ?? null,
                'sma' => $currentBalanceValue['sma'] ?? null,
                'mutualFundValue' => $currentBalanceValue['mutualFundValue']
                    ?? null,
            ]
        );
    }

    /**
     * @param $accountId
     * @param mixed $initialBalance_value
     */
    private static function saveInitialBalanceInformation($accountId, mixed $initialBalance_value): void
    {
        Balance::updateOrCreate(
            ['user_id' => Auth::id(), 'accountId' => $accountId, 'balanceType' => 'initialBalances'],
            [
                'user_id' => Auth::id(),
                'accountId' => $accountId,
                'balanceType' => 'initialBalances',
                'accruedInterest' => $initialBalance_value['accruedInterest']?? null,
                'availableFundsNonMarginableTrade' => $initialBalance_value['availableFundsNonMarginableTrade'] ?? null,
                'bondValue' => $initialBalance_value['bondValue'] ?? null,
                'buyingPower' => $initialBalance_value['buyingPower'] ?? null,
                'cashBalance' => $initialBalance_value['cashBalance'] ?? null,
                'cashAvailableForTrading' => $initialBalance_value['cashAvailableForTrading'] ?? null,
                'cashReceipts' => $initialBalance_value['cashReceipts'] ?? null,
                'dayTradingBuyingPower' => $initialBalance_value['dayTradingBuyingPower'] ?? null,
                'dayTradingBuyingPowerCall' => $initialBalance_value['dayTradingBuyingPowerCall'] ?? null,
                'dayTradingEquityCall' => $initialBalance_value['dayTradingEquityCall'] ?? null,
                'equity' => $initialBalance_value['equity'] ?? null,
                'equityPercentage' => $initialBalance_value['equityPercentage'] ?? null,
                'liquidationValue' => $initialBalance_value['liquidationValue'] ?? null,
                'longMarginValue' => $initialBalance_value['longMarginValue']
                    ?? null,
                'longOptionMarketValue' => $initialBalance_value['longOptionMarketValue'] ?? null,
                'longStockValue' => $initialBalance_value['longStockValue']
                    ?? null,
                'maintenanceCall' => $initialBalance_value['maintenanceCall']
                    ?? null,
                'maintenanceRequirement' => $initialBalance_value['maintenanceRequirement'] ?? null,
                'margin' => $initialBalance_value['margin'] ?? null,
                'marginEquity' => $initialBalance_value['marginEquity'] ?? null,
                'moneyMarketFund' => $initialBalance_value['moneyMarketFund']
                    ?? null,
                'mutualFundValue' => $initialBalance_value['mutualFundValue']
                    ?? null,
                'regTCall' => $initialBalance_value['regTCall'] ?? null,
                'shortMarginValue' => $initialBalance_value['shortMarginValue'] ?? null,
                'shortOptionMarketValue' => $initialBalance_value['shortOptionMarketValue'] ?? null,
                'shortStockValue' => $initialBalance_value['shortStockValue']
                    ?? null,
                'totalCash' => $initialBalance_value['totalCash'] ?? null,
                'isInCall' => $initialBalance_value['isInCall'] ?? null,
                'pendingDeposits' => $initialBalance_value['pendingDeposits']
                    ?? null,
                'marginBalance' => $initialBalance_value['marginBalance'] ??
                    null,
                'shortBalance' => $initialBalance_value['shortBalance'] ?? null,
                'accountValue' => $initialBalance_value['accountValue'] ?? null,
            ]
        );
    }

    /**
     * @param $orderStrategies
     */
    private static function saveOrdersInformation($orderStrategies): void
    {
        Order::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'accountId' => $orderStrategies['accountId'],
                'orderId' => $orderStrategies['orderId'],
            ],
            [
                'session' => $orderStrategies['session'] ?? null,
                'averagePrice' => $orderStrategies['averagePrice'] ?? null,
                'currentDayCost' => $orderStrategies['currentDayCost'] ?? null,
                'currentDayProfitLoss' => $orderStrategies['currentDayProfitLoss'] ?? null,
                'duration' => $orderStrategies['duration'] ?? null,
                'orderType' => $orderStrategies['orderType'] ?? null,
                'cancelTime' => $orderStrategies['cancelTime'] ?? null,
                'complexOrderStrategyType' => $orderStrategies['complexOrderStrategyType'] ?? null,
                'quantity' => $orderStrategies['quantity'] ?? null,
                'filledQuantity' => $orderStrategies['filledQuantity'] ?? null,
                'remainingQuantity' => $orderStrategies['remainingQuantity'] ?? null,
                'requestedDestination' => $orderStrategies['requestedDestination'] ?? null,
                'destinationLinkName' => $orderStrategies['destinationLinkName'] ?? null,
                'price' => $orderStrategies['price'] ?? null,
                'orderLegType' => $orderStrategies['orderLegCollection']['orderLegType'] ?? null,
                'legId' => $orderStrategies['orderLegCollection']['0']['legId']
                ?? null,
                'cusip' => $orderStrategies['orderLegCollection']['0']['instrument']['cusip'] ?? null,
                'symbol' => $orderStrategies['orderLegCollection']['0']['instrument']['symbol'] ?? 'None',
                'instruction' => $orderStrategies['orderLegCollection']['0']['instruction'] ?? null,
                'positionEffect' => $orderStrategies['orderLegCollection']['0']['positionEffect'] ?? null,
                'orderStrategyType' => $orderStrategies['orderStrategyType'] ?? null,
                'orderId' => $orderStrategies['orderId'] ?? null,
                'cancelable' => $orderStrategies['cancelable'] ?? null,
                'editable' => $orderStrategies['editable'] ?? null,
                'status' => $orderStrategies['status'] ?? null,
                'statusDescription' => $orderStrategies['statusDescription'] ?? null,
                'enteredTime' => $orderStrategies['enteredTime'] ?? null,
                'tag' => $orderStrategies['tag'] ?? null,
                'accountId' => $orderStrategies['accountId'] ?? null,
            ]
        );
    }

    /**
     * @param mixed $position_value
     * @param $accountId
     */
    private static function savePositionInformation(mixed $position_value,
                                                    $accountId): void
    {
        Position::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'symbol' => $position_value['instrument']['symbol'],
                'accountId' => $accountId,
            ],
            [
                'shortQuantity' => $position_value['shortQuantity'] ?? null,
                'averagePrice' => $position_value['averagePrice'] ?? null,
                'currentDayCost' => $position_value['currentDayCost'] ?? null,
                'currentDayProfitLoss' => $position_value['currentDayProfitLoss'] ?? null,
                'currentDayProfitLossPercentage' => $position_value['currentDayProfitLossPercentage'] ?? null,
                'longQuantity' => $position_value['longQuantity'] ?? null,
                'settledLongQuantity' => $position_value['settledLongQuantity'] ?? null,
                'settledShortQuantity' => $position_value['settledShortQuantity'] ?? null,
                'assetType' => $position_value['instrument']['assetType'] ?? null,
                'cusip' => $position_value['instrument']['cusip'] ?? null,
                'symbol' => $position_value['instrument']['symbol'] ?? null,
                'marketValue' => $position_value['marketValue'] ?? null,
                'maintenanceRequirement' => $position_value['maintenanceRequirement'] ?? null,
                'previousSessionLongQuantity' => $position_value['previousSessionLongQuantity'] ?? null,
            ]
        );
    }

    /**
     * @param mixed $authResponse
     */
    public static function saveTokenInformation(mixed $authResponse): void
    {
        Token::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'access_token' => $authResponse['access_token'] ?? null,
                'refresh_token' => $authResponse['refresh_token'] ?? null,
                'scope' => $authResponse['scope'] ?? null,
                'expires_in' => $authResponse['expires_in'] ?? null,
                'refresh_token_expires_in' => $authResponse['refresh_token_expires_in'] ?? null,
                'token_type' => $authResponse['token_type'] ?? null,
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = Token::where('user_id', Auth::id())->get();

        if (empty($token['0']['code'])) {
            return redirect('/dashboard');
        }

        if (empty($token['0']['refresh_token']) && TDAmeritrade::isAccessTokenExpired
            ($token['0']['updated_at']) === true) {
            return redirect('/dashboard');
        }

        if (empty($token['0']['refresh_token'])) {

            if (!empty($token['0']['code'])) {
                $authResponse = TDAmeritrade::createAccessToken($token['0']['code']);
            }

            if (!empty($authResponse)) {
                self::saveTokenInformation($authResponse);
                return redirect('/account');
            }
        }

        if (!empty($token['0']['access_token'])) {
            if (TDAmeritrade::isAccessTokenExpired
            ($token['0']['updated_at']) === true) {
                // Time To Refresh The Token
                self::saveTokenInformation(TDAmeritrade::refreshToken($token['0']['refresh_token']));
                Log::info('The Token Was Refreshed During This Process');
            }

            // Retrieve The Account Information
            $accountResponse = Accounts::getAccounts();
//            dd($accountResponse['0']['securitiesAccount']['orderStrategies']['100']);

            if (!empty($accountResponse['error'])) {
                self::saveTokenInformation(TDAmeritrade::refreshToken($token['0']['refresh_token']));
                return redirect('/account');
            }

            self::saveAccountInformation($accountResponse);

//            dd($accountResponse['0']['securitiesAccount']['orderStrategies']['10']);

            $account = Account::where('user_id', Auth::id())->get();
            $positions = Position::where('user_id', Auth::id())->get();
            $Balance = Balance::where('user_id', Auth::id())->get();
            $order = Order::where([
                ['user_id', '=', Auth::id()],
                ['created_at', '=', Carbon::today()],
            ])->get();

            $data = [
                'name' => Auth::user()->name,
                'account'  => $account,
                'positions'   => $positions,
                'balance' => $Balance,
                'order' => $order,
                'count' => 1
            ];

            return View::make('account')->with($data);
        }

    }

    /**
     * @param mixed $accountResponse
     */
    public static function saveAccountInformation(mixed $accountResponse): void
    {
        foreach ($accountResponse as $key => $value) {
            $account = self::storeAccountInfo($value['securitiesAccount']);

            if (!empty($value['securitiesAccount']['positions'])) {
                foreach ($value['securitiesAccount']['positions'] as
                         $position_value) {
                    self::savePositionInformation($position_value, $account->accountId);
                }
            }

            if (!empty($value['securitiesAccount']['orderStrategies'])) {
                foreach ($value['securitiesAccount']['orderStrategies'] as
                         $orders) {
                    self::saveOrdersInformation($orders);

                    if (!empty($orders['childOrderStrategies']['childOrderStrategies'])) {
                        foreach ($orders['childOrderStrategies']['childOrderStrategies'] as
                                 $childOrder) {
                            self::saveOrdersInformation($childOrder);
                        }
                    }

                }
            }

            self::saveInitialBalanceInformation($account->accountId, $value['securitiesAccount']['initialBalances']);
            self::saveCurrentBalancesInformation($account->accountId, $value['securitiesAccount']['currentBalances']);
            self::saveProjectedBalancesInformation($account->accountId, $value['securitiesAccount']['projectedBalances']);
        }
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
