<?php

namespace App\Services;

use App\Models\Price;
use App\Traits\HasErrorHandling;
use App\Traits\HasCaching;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class PriceService
{
    use HasErrorHandling, HasCaching;

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
    protected $endpoint = 'https://api.tdameritrade.com/v1/marketdata';

    /**
     * Create a new service instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the current price for a given symbol.
     *
     * @param  string  $symbol
     * @return \App\Models\Price
     */
    public function getPrice(string $symbol): Price
    {
        $url = "{$this->endpoint}/{$symbol}/pricehistory";

        try {
            $response = $this->client->get($url);
        } catch (ClientException $e) {
            return $this->handleError($e);
        }

        $data = json_decode($response->getBody(), true);

        return Price::create([
            'symbol' => $symbol,
            'price' => $data['last']['price'],
            'timestamp' => $data['last']['timestamp'],
        ]);
    }
}
