<?php

namespace App\Services;

use App\Models\Price;
use App\Traits\HasCaching;
use App\Traits\HasErrorHandling;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class PriceService
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected static Client $client;

    /**
     * @param Client $client
     */
    public static function setClient(Client $client): void
    {
        self::$client = $client;
    }

    /**
     * The API endpoint.
     *
     * @var string
     */
    protected static string $endpoint = 'https://api.tdameritrade.com/v1/marketdata';

    /**
     * Create a new service instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        self::setClient($client);
//        $this->client = $client;
    }

    /**
     * Get the current price for a given symbol.
     *
     * @param  string  $symbol
     * @return \App\Models\Price
     */
    public static function getPrice(string $symbol): Price
    {
        self::setClient(new Client());
        $url = "https://api.tdameritrade.com/v1/marketdata/{$symbol}/pricehistory";

        try {
            $response = self::$client->get($url);
        } catch (ClientException $e) {
            return Log::debug('Exception Thrown:', $e);
        } catch (GuzzleException $e) {
            return Log::debug('Exception Thrown:', $e);
        }

        $data = json_decode($response->getBody(), true);

        return Price::updateOrCreate(
            [
                'symbol' => $symbol,
                'price' => $data['last']['price'],
//                'timestamp' => $data['last']['timestamp'],
            ],
            [
                'symbol' => $symbol,
                'price' => $data['last']['price'],
            ]
        );
    }
}
