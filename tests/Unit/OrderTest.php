<?php

namespace Tests\Unit;

use App\TDAmeritrade\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Test the placeOrder method.
     *
     * @return void
     */
    public function testPlaceOrder()
    {
        $mock = new MockHandler([
            new Response(200, [], '{"success": true}'),
            new Response(400, [], '{"success": false, "error": "Invalid request"}'),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $order = new Order($client);

        $data = [
            'accountId' => '12345',
            'orderType' => 'MARKET',
            'session' => 'NORMAL',
            'duration' => 'DAY',
            'orderStrategyType' => 'SINGLE',
            'orderLegCollection' => [
                [
                    'instruction' => 'BUY',
                    'quantity' => 100,
                    'instrument' => [
                        'symbol' => 'AAPL',
                        'assetType' => 'EQUITY',
                    ],
                ],
            ],
        ];

        // test a successful request
        $response = $order->placeOrder($data);
        $this->assertEquals($response, ['success' => true]);

        // test a failed request
        $response = $order->placeOrder($data);
        $this->assertEquals($response, ['success' => false, 'error' => 'Invalid request']);
    }
}
