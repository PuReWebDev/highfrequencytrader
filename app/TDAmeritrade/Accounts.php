<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use App\Models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * The Accounts class handles requests for account information.
 *
 * $account = new Account();
$accountId = '123456';
$orders = $account->getOrdersByPath($accountId);
 */
class Accounts
//class Accounts extends BaseClass
{
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
     * Get the account information for an account.
     *
     * @param string $accountId
     * @return array
     * @throws \JsonException
     */
    public function getAccount(string $accountId): array
    {
        // check the cache for the account information
        if (Cache::has('account-' . $accountId)) {
            return Cache::get('account-' . $accountId);
        }

        // make a request to the TD Ameritrade API if the data is not in the cache
        $data = $this->get('/accounts/' . $accountId);

        // store the data in the cache for 5 minutes
        Cache::put('account-' . $accountId, $data, 5);

        return $data;
    }

    /**
     * Get the orders for an account by path.
     *
     * @param string $accountId
     * @param string $path
     * @return array
     * @throws \JsonException
     */
    public function getOrdersByPath(string $accountId, string $path): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/' . $accountId . '/orders/' . $path);
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
     * Cancel an existing order for a given symbol.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function cancelOrder(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->delete("/v1/accounts/{$parameters['accountId']}/orders/{$parameters['orderId']}", [
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
     * Get an order for an account.
     *
     * @param string $accountId
     * @param string $orderId
     * @return array
     * @throws \JsonException
     */
    public function getOrderold(string $accountId, string $orderId): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/'.$accountId.'/orders/'.$orderId);
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
     * Get a single order for a given account.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function getOrder(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();
        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/accounts/{$parameters['accountId']}/orders/{$parameters['orderId']}", [
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
     * Replace an order for an account.
     *
     * @param  string  $accountId
     * @param  string  $orderId
     * @param  array  $orderData
     * @return array
     */
    public function replaceOrderold(string $accountId, string $orderId, array
    $orderData): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->put('/accounts/'.$accountId.'/orders/'.$orderId, $orderData);
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
     * Replace an existing order for a given symbol.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function replaceOrder(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->put("/v1/accounts/{$parameters['accountId']}/orders/{$parameters['orderId']}", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'json' => $parameters,
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
     * Create a saved order for an account.
     *
     * @param string $accountId
     * @param array $orderData
     * @return array
     * @throws \JsonException
     */
    public function createSavedOrder(string $accountId, array $orderData): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->post('/accounts/'.$accountId.'/savedorders', $orderData);
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
     * Delete a saved order for an account.
     *
     * @param  string  $accountId
     * @param  string  $savedOrderId
     * @return bool
     */
    public function deleteSavedOrder(string $accountId, string $savedOrderId): bool
    {
        try {
            // make the request to the TD Ameritrade API
            $this->delete('/accounts/'.$accountId.'/savedorders/'.$savedOrderId);
            return true;
        } catch (ClientException $e) {
            // handle the error if the request fails
            return false;
        }
    }

    /**
     * Get a saved order for an account.
     *
     * @param string $accountId
     * @param string $savedOrderId
     * @return array
     * @throws \JsonException
     */
    public function getSavedOrder(string $accountId, string $savedOrderId): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/'.$accountId.'/savedorders/'.$savedOrderId);
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
     * Get the saved orders for an account by path.
     *
     * @param string $accountId
     * @param string $path
     * @return array
     * @throws \JsonException
     */
    public function getSavedOrdersByPath(string $accountId, string $path): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->get('/accounts/'.$accountId.'/savedorders/'.$path);
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
     * Replace a saved order for an account.
     *
     * @param  string  $accountId
     * @param  string  $savedOrderId
     * @param  array  $orderData
     * @return array
     */
    public function replaceSavedOrder(string $accountId, string $savedOrderId, array $orderData): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = $this->put('/accounts/'.$accountId.'/savedorders/'.$savedOrderId, $orderData);
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
     * Get a list of accounts.
     *
     * @return array
     * @throws \JsonException
     */
    public static function getAccounts(): array
    {
        try {
            // make the request to the TD Ameritrade API
            $data = self::get('v1/accounts');
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
     * Make a GET request to the TD Ameritrade API.
     *
     * @param  string  $uri
     * @param  array  $query
     * @return array
     */
    public static function get(string $uri, array $query = ['fields' => 'positions,orders']):
    array
    {
        $token = Token::where('user_id', Auth::id())->get();
        $client = new Client([
            'base_uri' => "https://api.tdameritrade.com/v1",
        ]);
        $response = $client->get($uri, [
            'headers' => [
                'Authorization' => 'Bearer '.$token['0']['access_token'],
            ],
            'query' => $query,
        ]);

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

}
