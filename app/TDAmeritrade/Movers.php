<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Movers
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
    protected static $cacheKeyPrefix = 'td_ameritrade_movers_';

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
     * Get the movers for the specified index.
     *
     * @param  string  $index
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return array
     */
    public static function getMovers(string $index, Cache $cache): array
    {
        self::$cache = $cache;

        // check the cache for the movers data
        $data = self::$cache->get(self::$cacheKeyPrefix.$index);

        if ($data !== null) {
            // return the cached data if it exists
            return $data;
        }

        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get('/marketdata/'.$index.'/movers');

            $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            // store the data in the cache
            self::$cache->put(self::$cacheKeyPrefix.$index, $data, 60);
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
