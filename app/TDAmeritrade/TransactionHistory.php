<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Cache\Repository;

class TransactionHistory
{
    /**
     * The Guzzle client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected Client $client;

    /**
     * The base URI for the TD Ameritrade API.
     *
     * @var string
     */
    protected string $baseUri = 'https://api.tdameritrade.com';

    /**
     * The access token for the application.
     *
     * @var string
     */
    protected string $accessToken;

    /**
     * The cache manager instance.
     *
     * @var Repository
     */
    protected Repository|Cache $cache;

    /**
     * The cache key prefix.
     *
     * @var string
     */
    protected string $cacheKeyPrefix = 'td_ameritrade_transaction_history_';

    /**
     * Create a new instance of the TransactionHistory class.
     *
     * @param string $accessToken
     * @param Cache $cache
     */
    public function __construct(string $accessToken, Cache $cache)
    {
        $this->accessToken = $accessToken;
        $this->cache = $cache;

        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Authorization' => 'Bearer '.$this->accessToken,
            ],
        ]);
    }

    /**
     * Get a transaction from the specified account.
     *
     * @param  string  $accountId
     * @param  string  $transactionId
     * @return array
     */
    public function getTransaction(string $accountId, string $transactionId): array
    {
        // check the cache for the transaction data
        $data = $this->cache->get($this->cacheKeyPrefix.$transactionId);

        if ($data !== null) {
            // return the cached data if it exists
            return $data;
        }

        try {
            // make the request to the TD Ameritrade API
            $response = $this->client->get('/accounts/'.$accountId.'/transactions/'.$transactionId);

            $data = json_decode((string) $response->getBody(), true);

            // store the data in the cache
            $this->cache->put($this->cacheKeyPrefix.$transactionId, $data, 60);
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
     * Get all transactions from the specified account.
     *
     * @param  string  $accountId
     * @return array
     */
    public function getTransactions(string $accountId): array
    {
        // check the cache for the transaction data
        $data = $this->cache->get($this->cacheKeyPrefix.$accountId);

        if ($data !== null) {
            // return the cached data if it exists
            return $data;
        }

        try {
            // make the request to the TD Ameritrade API
            $response = $this->client->get('/accounts/'.$accountId.'/transactions');

            $data = json_decode((string) $response->getBody(), true);

            // store the data in the cache
            $this->cache->put($this->cacheKeyPrefix.$accountId, $data, 60);
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
