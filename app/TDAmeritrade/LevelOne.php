<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

/**
 * The LevelOne class handles subscription and retrieval of level one data.
 */
class LevelOne extends BaseClass
{
    /**
     * Subscribe to level one data for a symbol.
     *
     * @param string $symbol
     * @return bool
     * @throws \JsonException
     */
    public function subscribe(string $symbol): bool
    {
        return $this->post('/marketdata/'.$symbol.'/levelone');
    }

    /**
     * Unsubscribe from level one data for a symbol.
     *
     * @param  string  $symbol
     * @return bool
     */
    public function unsubscribe(string $symbol): bool
    {
        return $this->delete('/marketdata/'.$symbol.'/levelone');
    }

    /**
     * Get the level one data for a symbol.
     *
     * @param  string  $symbol
     * @return array
     */
    public function getData(string $symbol): array
    {
        return $this->get('/marketdata/'.$symbol.'/levelone');
    }

    /**
     * Send a request for level one data.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function getLevelOneold(string $credential, array
    $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/marketdata/{$parameters['symbol']}/quotes", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            // handle any exceptions thrown during the request
            self::handleException($e);
        }

        return $data;
    }

    /**
     * Send a request for level one data for the specified symbol.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function getLevelOne(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        $symbol = $parameters['symbol'];
        $bids = $parameters['bids'];
        $asks = $parameters['asks'];
        $trades = $parameters['trades'];
        $systemEvent = $parameters['systemEvent'];

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/marketdata/{$symbol}/quotes", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'query' => [
                    'bids' => $bids,
                    'asks' => $asks,
                    'trades' => $trades,
                    'systemEvent' => $systemEvent,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
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
