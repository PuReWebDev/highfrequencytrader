<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Account;
use App\Models\Order;
use App\Models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

    /**
     * @throws \JsonException
     */
    public static function placeOtoOrder(string $symbol): array
    {
        // Set up the request body
        $newnew = '{
	"orderType": "LIMIT",
	"session": "SEAMLESS",
	"price": "182.45",
	"duration": "DAY",
	"orderStrategyType": "TRIGGER",
	"orderLegCollection": [{
		"instruction": "BUY",
		"quantity": 50,
		"instrument": {
			"symbol": "TSLA",
			"assetType": "EQUITY"
		}
	}],
	"childOrderStrategies": [{
		"orderType": "LIMIT",
		"session": "SEAMLESS",
		"price": "184.00",
		"duration": "DAY",
		"orderStrategyType": "SINGLE",
		"orderLegCollection": [{
			"instruction": "SELL",
			"quantity": 50,
			"instrument": {
				"symbol": "TSLA",
				"assetType": "EQUITY"
			}
		}]
	}]
}';

$theChildren = '{
  "orderType": "LIMIT",
  "session": "SEAMLESS",
  "price": "170.45",
  "duration": "DAY",
  "orderStrategyType": "TRIGGER",
  "orderLegCollection": [
    {
      "instruction": "BUY",
      "quantity": 10,
      "instrument": {
        "symbol": "TSLA",
        "assetType": "EQUITY"
      }
    }
  ],
  "childOrderStrategies": [
    {
      "complexOrderStrategyType": "NONE",
      "orderType": "TRAILING_STOP",
      "session": "SEAMLESS",
      "stopPriceLinkBasis": "BASE",
      "stopPriceLinkType": "VALUE",
      "stopPriceOffset": "0.21",
      "duration": "DAY",
      "orderStrategyType": "SINGLE",
      "orderLegCollection": [
        {
          "instruction": "SELL",
          "quantity": 10,
          "instrument": {
            "symbol": "TSLA",
            "assetType": "EQUITY"
          }
        }
      ]
    }
  ]
}';

        $account = Account::where('user_id', Auth::id())->get();
        $ordersEndpointUrl = config('tdameritrade.base_url') . '/v1/accounts/' . $account['0']['accountId'] . '/orders';

//        return self::sendRequest($ordersEndpointUrl, $newnew);
        return self::sendRequest($ordersEndpointUrl, $theChildren);
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
     * @param string $url
     * @param string $json
     * @return array
     * @throws \JsonException
     */
    private static function sendRequest(string $url, string
                                                   $json): array
    {
        $client = new Client();
        $token = Token::where('user_id', Auth::id())->get();

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token['0']['access_token'],
                    'Content-Type' => 'application/json'
                ],
                'body' => $json,
            ]);

        } catch (GuzzleException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
            'data' => json_decode($response->getBody()->getContents(), true, 512),
            'status' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase(),
        ];
    }
}
