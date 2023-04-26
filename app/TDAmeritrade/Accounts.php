<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use App\Models\Account;
use App\Models\Balance;
use App\Models\Order;
use App\Models\Position;
use App\Models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * The Accounts class handles requests for account information.
 *
 * $account = new Account();
$accountId = '123456';
$orders = $account->getOrdersByPath($accountId);
 */
class Accounts
//class Accounts extends BaseClass
{
    /**
     * Initialize the Guzzle client instance.
     *
     * @return void
     */
    protected static function initClient()
    {
        if (self::$client === null) {
            self::$client = new Client([
                'base_uri' => self::$baseUri,
            ]);
        }
    }

    public static function tokenPreFlight(): void
    {
        $token = Token::where('user_id', Auth::id())->get();

        if (TDAmeritrade::isAccessTokenExpired
            ($token['0']['updated_at']) === true) {
            // Time To Refresh The Token
            TDAmeritrade::saveTokenInformation(TDAmeritrade::refreshToken($token['0']['refresh_token']));
            Log::info('The Token Was Refreshed During This Process');
        }
    }

    /**
     * @param $orderStrategies
     */
    public static function processIncomingOrders($orderStrategies): void
    {
        foreach ($orderStrategies as
                 $orders) {
            self::saveOrdersInformation($orders);

            if (!empty($orders['childOrderStrategies'])) {
                foreach ($orders['childOrderStrategies'] as
                         $childOrder) {

                    if (!empty($childOrder['childOrderStrategies'])) {
                        self::parseChildOrders($childOrder['childOrderStrategies'], $orders['orderId']);
                    }

                    if (!empty($childOrder['orderStrategyType'])) {
                        if ($childOrder['orderStrategyType'] === 'SINGLE') {
                            self::parseChildOrders($childOrder, $orders['orderId']);
                        }
                    }

                }
            }

        }
    }

    /**
     * @param $childOrderStrategies
     * @param $orderId
     */
    public static function parseChildOrders($childOrderStrategies, $orderId): void
    {
        foreach ($childOrderStrategies as
                 $ocoOrder) {
//                            Log::info($ocoOrder['orderStrategyType']);

            if (!empty($ocoOrder['orderStrategyType'])) {
                if ($ocoOrder['orderStrategyType'] === 'SINGLE') {
                    $ocoOrder['parentOrderId'] = $orderId;
                }
            }


            self::saveOrdersInformation($ocoOrder);
        }
    }

    /**
     * Get the account information for an account.
     *
     * @param string $accountId
     * @return array
     * @throws \JsonException
     */
    public function getAccount(string $accountId): array
    {
        // check the cache for the account information
        if (Cache::has('account-' . $accountId)) {
            return Cache::get('account-' . $accountId);
        }

        // make a request to the TD Ameritrade API if the data is not in the cache
        $data = $this->get('/accounts/' . $accountId);

        // store the data in the cache for 5 minutes
        Cache::put('account-' . $accountId, $data, 5);

        return $data;
    }

    /**
     * Get the orders for an account by path.
     *
     * @param string $accountId
     * @param string $path
     * @return array
     * @throws \JsonException
     */
    public function getOrdersByPath(string $accountId, string $path): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/' . $accountId . '/orders/' . $path);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Cancel an existing order for a given symbol.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function cancelOrder(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->delete("/v1/accounts/{$parameters['accountId']}/orders/{$parameters['orderId']}", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }


    /**
     * Get an order for an account.
     *
     * @param string $accountId
     * @param string $orderId
     * @return array
     * @throws \JsonException
     */
    public function getOrderold(string $accountId, string $orderId): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/'.$accountId.'/orders/'.$orderId);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Get a single order for a given account.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function getOrder(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();
        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/accounts/{$parameters['accountId']}/orders/{$parameters['orderId']}", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }


    /**
     * Replace an order for an account.
     *
     * @param  string  $accountId
     * @param  string  $orderId
     * @param  array  $orderData
     * @return array
     */
    public function replaceOrderold(string $accountId, string $orderId, array
    $orderData): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->put('/accounts/'.$accountId.'/orders/'.$orderId, $orderData);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Replace an existing order for a given symbol.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function replaceOrder(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->put("/v1/accounts/{$parameters['accountId']}/orders/{$parameters['orderId']}", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'json' => $parameters,
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }


    /**
     * Create a saved order for an account.
     *
     * @param string $accountId
     * @param array $orderData
     * @return array
     * @throws \JsonException
     */
    public function createSavedOrder(string $accountId, array $orderData): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->post('/accounts/'.$accountId.'/savedorders', $orderData);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Delete a saved order for an account.
     *
     * @param  string  $accountId
     * @param  string  $savedOrderId
     * @return bool
     */
    public function deleteSavedOrder(string $accountId, string $savedOrderId): bool
    {
        try {
            // make the request to the TD Ameritrade API
            $this->delete('/accounts/'.$accountId.'/savedorders/'.$savedOrderId);
            return true;
        } catch (ClientException $e) {
            // handle the error if the request fails
            return false;
        }
    }

    /**
     * Get a saved order for an account.
     *
     * @param string $accountId
     * @param string $savedOrderId
     * @return array
     * @throws \JsonException
     */
    public function getSavedOrder(string $accountId, string $savedOrderId): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/'.$accountId.'/savedorders/'.$savedOrderId);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Get the saved orders for an account by path.
     *
     * @param string $accountId
     * @param string $path
     * @return array
     * @throws \JsonException
     */
    public function getSavedOrdersByPath(string $accountId, string $path): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/'.$accountId.'/savedorders/'.$path);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Replace a saved order for an account.
     *
     * @param  string  $accountId
     * @param  string  $savedOrderId
     * @param  array  $orderData
     * @return array
     */
    public function replaceSavedOrder(string $accountId, string $savedOrderId, array $orderData): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->put('/accounts/'.$accountId.'/savedorders/'.$savedOrderId, $orderData);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Get a list of accounts.
     *
     * @return array
     * @throws \JsonException
     */
    public static function getAccounts(): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = self::get('v1/accounts');
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    /**
     * Make a GET request to the TD Ameritrade API.
     *
     * @param  string  $uri
     * @param  array  $query
     * @return array
     */
    public static function get(string $uri, array $query = ['fields' => 'positions,orders']):
    array
    {
        $token = Token::where('user_id', Auth::id())->get();
//        $token = Token::where('user_id', Auth::id())->get();
        $client = new Client([
            'base_uri' => "https://api.tdameritrade.com/v1",
        ]);
        $response = $client->get($uri, [
            'headers' => [
                'Authorization' => 'Bearer '.$token['0']['access_token'],
            ],
            'query' => $query,
        ]);

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

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
                'parentOrderId' => $orderStrategies['parentOrderId'] ?? null,
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
                'stopPrice' => $orderStrategies['stopPrice'] ?? null,
                'stopPriceLinkBasis' => $orderStrategies['stopPriceLinkBasis'] ?? null,
                'stopPriceLinkType' => $orderStrategies['stopPriceLinkType'] ?? null,
                'stopPriceOffset' => $orderStrategies['stopPriceOffset'] ?? null,
                'orderDuration' => $orderStrategies['orderDuration'] ?? null,
                'stopType' => $orderStrategies['stopType'] ?? null,
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

            Log::info('Starting To Process Incoming Orders');
            if (!empty($value['securitiesAccount']['orderStrategies'])) {
                self::processIncomingOrders($value['securitiesAccount']['orderStrategies']);
            }
            Log::info('Finished Processing Incoming Orders');

            self::saveInitialBalanceInformation($account->accountId, $value['securitiesAccount']['initialBalances']);
            self::saveCurrentBalancesInformation($account->accountId, $value['securitiesAccount']['currentBalances']);
            self::saveProjectedBalancesInformation($account->accountId, $value['securitiesAccount']['projectedBalances']);
        }
    }

    public static function updateAccountData(): void
    {
        self::tokenPreFlight();

        // Retrieve The Account Information
        $accountResponse = Accounts::getAccounts();
        Accounts::saveAccountInformation($accountResponse);
    }

}
