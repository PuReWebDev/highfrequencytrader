<?php

namespace App\TDAmeritrade;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class PriceHistory
{
    /**
     * The Guzzle client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected static $client;

    /**
     * The base URI for the TD Ameritrade API.
     *
     * @var string
     */
    protected static $baseUri = 'https://api.tdameritrade.com';

    /**
     * The cache manager instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected static $cache;

    /**
     * The cache key prefix.
     *
     * @var string
     */
    protected static $cacheKeyPrefix = 'td_ameritrade_price_history_';

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

    /**
     * Get the price history for the specified symbol.
     *
     * @param  string  $symbol
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @param  array  $options
     * @return array
     */
    public static function getPriceHistory(string $symbol, Cache $cache, array $options = []): array
    {
        self::$cache = $cache;

        // check the cache for the price history data
        $data = self::$cache->get(self::$cacheKeyPrefix.$symbol.json_encode($options));

        if ($data !== null) {
            // return the cached data if it exists
            return $data;
        }

        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get('/marketdata/'.$symbol.'/pricehistory', [
                'query' => $options,
            ]);

            $data = json_decode((string) $response->getBody(), true);

            // store the data in the cache
            self::$cache->put(self::$cacheKeyPrefix.$symbol.json_encode($options), $data, 60);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }
}
