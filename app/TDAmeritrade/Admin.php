<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use Illuminate\Contracts\Cache\Repository as Cache;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

/**
 * $admin = new Admin();
$username = 'myUsername';
$password = 'myPassword';
$authToken = 'myAuthToken';
$loginRequest = $admin->login($username, $password, $authToken);

 */
class Admin
{
    /**
     * The Guzzle HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected static $client;

    /**
     * The cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected static $cache;

    /**
     * Initialize the Guzzle HTTP client.
     *
     * @return void
     */
    protected static function initClient(): void
    {
        self::$client = new GuzzleClient([
            'base_uri' => config('td_ameritrade.streaming_api_url'),
        ]);
    }

    /**
     * Send a login request to the TD Ameritrade API.
     *
     * @param  string  $credential
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public static function login(string $credential, Cache $cache): void
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            self::$client->post('/v1/userprincipals', [
                'query' => [
                    'apikey' => config('td_ameritrade.api_key'),
                ],
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
            ]);
        } catch (\Exception $e) {
            // handle any exceptions thrown during the request
            self::handleException($e);
        }
    }

    /**
     * Send a logout request to the TD Ameritrade API.
     *
     * @param  string  $credential
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public static function logout(string $credential, Cache $cache): void
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            self::$client->delete('/v1/userprincipals', [
                'query' => [
                    'apikey' => config('td_ameritrade.api_key'),
                ],
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
            ]);
        } catch (\Exception $e) {
            // handle any exceptions thrown during the request
            self::handleException($e);
        }
    }


    /**
     * Send a QOS request to the streaming API.
     *
     * @param string $credential
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function qos(string $credential, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/qos", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
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

    /**
     * Parse the QOS response from the streaming API.
     *
     * @param array $response
     * @return array
     */
    public static function parseQosResponse(array $response): array
    {
        // initialize the data array
        $data = [];
        // parse the response data
        foreach ($response as $key => $value) {
            $data[$key] = [
                'current_rate' => $value['currentRate'],
                'max_rate' => $value['maxRate'],
                'period' => $value['period'],
                'type' => $value['type'],
            ];
        }

        return $data;
    }

}
