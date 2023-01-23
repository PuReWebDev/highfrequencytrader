<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use App\TDAmeritrade\Base;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;

/**
 * The Order class handles placing orders with the TD Ameritrade API.
 */
class Order extends BaseClass
{
    /**
     * Send a request to place a new order.
     *
     * @param string $credential
     * @param array $parameters
     * @param Cache $cache
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function placeOrder(string $credential, array $parameters, Cache $cache):array
    {
        // validate the input data

        $validator = Validator::make($parameters, [
            'accountId' => 'required|string',
            'orderType' => 'required|string',
            'session' => 'required|string',
            'duration' => 'required|string',
            'orderStrategyType' => 'required|string',
            'orderLegCollection' => 'required|array',
        ]);

        if ($validator->fails()) {
            // return an error if validation fails
            return [
                'success' => false,
                'error' => $validator->errors()->first(),
            ];
        }

        // make the request to the TD Ameritrade API if validation passes

        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->post("/v1/accounts/{$parameters['accountId']}/orders", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'json' => [
                    'orderType' => $parameters['orderType'],
                    'session' => $parameters['session'],
                    'duration' => $parameters['duration'],
                    'orderStrategyType' => $parameters['orderStrategyType'],
                    'orderLegCollection' => $parameters['orderLegCollection'],
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            // handle any exceptions thrown during the request
            self::handleException($e);
        }

        return $data;
    }
}
