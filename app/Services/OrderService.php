<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Account;
use App\Models\Order;
use App\Models\Price;
use App\Models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Place an order.
     *
     * @param Request $request
     * @return OrderResource
     */
    public function placeOrderold(Request $request)
    {
        // Validate the request data
        $request->validate([
            'symbol' => 'required|string',
            'orderType' => 'required|string',
            'quantity' => 'required|integer',
            'priceType' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Place the order
        $order = Order::create($request->all());

        // Return the order resource
        return new OrderResource($order);
    }

    /**
     * Place an order.
     *
     * @param string $symbol
     * @param string $orderType
     * @param int $quantity
     * @param float $price
     * @param string $duration
     * @param string $orderStrategyType
     * @return array
     */
    public function placeOrder(string $symbol, string $orderType, int $quantity, float $price, string $duration, string $orderStrategyType): array
    {
        $client = new Client();

        try {
            $response = $client->post(config('services.tdameritrade.base_url') . '/v1/accounts/' . config('services.tdameritrade.account_id') . '/orders', [
                'headers' => [
                    'Authorization' => 'Bearer ' . Token::getLatest()->access_token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'orderType' => $orderType,
                    'session' => 'NORMAL',
                    'duration' => $duration,
                    'orderStrategyType' => $orderStrategyType,
                    'orderLegCollection' => [
                        [
                            'instruction' => 'BUY',
                            'quantity' => $quantity,
                            'instrument' => [
                                'symbol' => $symbol,
                                'assetType' => 'EQUITY',
                            ],
                            'orderLegType' => 'EQUITY',
                            'price' => $price,
                        ],
                    ],
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            return json_decode($e->getResponse()->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        }
    }

    public static function placeOtoOrder(string $symbol): array
    {
        // Set up the request body
        $order = [
            'orderType' => 'OTO',
            'session' => 'NORMAL',
//            'priceType' => $priceType,
            'duration' => 'GOOD_TILL_CANCEL',
            'complexOrderStrategyType' => 'NONE',
            'orderLegCollection' => [
                [
                    'instruction' => 'BUY',
                    'quantity' => config("tdameritrade.quantity"),
                    'instrument' => [
                        'symbol' => $symbol,
                    ],
                ],
                [
                    'instruction' => 'SELL',
                    'quantity' => config("tdameritrade.quantity"),
                    'instrument' => [
                        'symbol' => $symbol,
                    ],
                    'orderLegType' => 'TRAILING_STOP',
//                    'trailingStopPrice' => $stopPrice,
                    'trailingStopPriceType' => 'ACTIVE_TRAIL',
//                    'trailingPercent' => $limitPrice,
                ],
            ],
        ];

        // Set up the request body
        $neworder = [
            'orderType' => 'MARKET',
            'session' => 'NORMAL',
//            'priceType' => $priceType,
            'duration' => 'GOOD_TILL_CANCEL',
            'complexOrderStrategyType' => 'NONE',
            'orderStrategyType' => 'TRIGGER',
            'orderLegCollection' => [
                'instruction' => 'BUY',
                'quantity' => 1,
                'instrument' => [
                    'symbol' => $symbol,
                ],
            ],
            'childOrderStrategies' => [
                'complexOrderStrategyType' => 'NONE',
                'orderType' => 'TRAILING_STOP',
                'session' => 'NORMAL',
                'stopPriceLinkBasis' => 'BID',
                'stopPriceLinkType' => 'VALUE',
                'stopPriceOffset' => 3.00,
                'duration' => 'GOOD_TILL_CANCEL',
                'orderStrategyType' => 'SINGLE',
                // 'trailingStopPriceType' => 'ACTIVE_TRAIL',
                'orderLegCollection' => [
                    'instruction' => 'SELL',
                    'quantity' => 1,
                    'instrument' => [
                        'symbol' => $symbol,
                        'assetType' => 'EQUITY'
                    ]
                ]
            ]
        ];

        $account = Account::where('user_id', Auth::id())->get();

        // Send the request and get the response
//        $ordersEndpointUrl = config('tdameritrade.base_url') . '/v1/accounts/' . config('tdameritrade.client_id') . '/orders';
        $ordersEndpointUrl = config('tdameritrade.base_url') . '/v1/accounts/' . $account['0']['accountId'] . '/orders';
        $response = self::sendRequest('POST', $ordersEndpointUrl, $neworder);
        dd($response);
//        $response = self::sendRequest('POST', $ordersEndpointUrl, $order);
        Log::debug('Order Response', $response);
        // Return the response as an array
        return json_decode((string)$response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Cancel an order.
     *
     * @param Order $order
     * @return OrderResource
     */
    public function cancelOrder(Order $order)
    {
        // Cancel the order
        $order->delete();

        // Return the order resource
        return new OrderResource($order);
    }

    /**
     * Get an order.
     *
     * @param Order $order
     * @return OrderResource
     */
    public function getOrder(Order $order)
    {
        // Return the order resource
        return new OrderResource($order);
    }

    /**
     * Replace an order.
     *
     * @param Order $order
     * @param Request $request
     * @return OrderResource
     */
    public function replaceOrder(Order $order, Request $request)
    {
        // Validate the request data
        $request->validate([
            'symbol' => 'required|string',
            'orderType' => 'required|string',
            'quantity' => 'required|integer',
            'priceType' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Replace the order
        $order->update($request->all());

        // Return the order resource
        return new OrderResource($order);
    }

    /**
     * Get all orders.
     *
     * @return AnonymousResourceCollection
     */
    public function getOrders(): AnonymousResourceCollection
    {
        // Cache the orders for 5 minutes
        $orders = Cache::remember('orders', 5, function () {
            return Order::all();
        });

        // Return the order resources
        return OrderResource::collection($orders);
    }

    /**
     * Send a request to the TD Ameritrade API
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @return array
     */
    private static function sendRequest(string $method, string $url, array $data = [])
    {
        $client = new \GuzzleHttp\Client();
        $token = Token::where('user_id', Auth::id())->get();

        try {
            $response = $client->request($method, $url, [
                'headers' => [
//                    'Authorization' => 'Bearer ' . self::getAccessToken(),
                    'Authorization' => 'Bearer ' . $token['0']['access_token'],
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);

            dd($response);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
            'data' => json_decode($response->getBody()->getContents())
        ];
    }
}
