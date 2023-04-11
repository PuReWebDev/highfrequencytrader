<?php

namespace App\Services;

use App\Models\Price;
use App\Traits\HasErrorHandling;
use App\Traits\HasCaching;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class PriceService
{
    // use HasErrorHandling, HasCaching;

    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The API endpoint.
     *
     * @var string
     */
    protected static $endpoint = 'https://api.tdameritrade.com/v1/marketdata';

    /**
     * Get the current price for a given symbol.
     *
     * @param  string  $symbol
     * @return \App\Models\Price
     */
    public static function getPrice(string $symbol): Price
    {
        $endpoint = self::$endpoint;
        $apiKey = env('TDAMERITRADE_APP_KEY');
        $url = "{$endpoint}/{$symbol}/quotes?apikey=$apiKey";
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);

        $data = json_decode($response->getBody(), true);
        return Price::create([
            'symbol' => $symbol,
            'price' => $data[$symbol]['askPrice'],
            'timestamp' => time(),
        ]);
    }
}
