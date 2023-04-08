<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Admin;
use App\Models\Token;
use App\TDAmeritrade\Admin as AdminAPI;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    /**
     * @var Client
     */
    private static Client $client;

    /**
     * @return Client
     */
    public static function getClient(): Client
    {
        return self::$client;
    }

    /**
     * @param Client $client
     */
    public static function setClient(Client $client): void
    {
        self::$client = $client;
    }

    /**
     * @return Token
     */
    public function getToken(): Token
    {
        return $this->token;
    }

    /**
     * @param Token $token
     */
    public function setToken(Token $token): void
    {
        $this->token = $token;
    }

    /**
     * @var Token
     */
    private static Token $token;

    /**
     * AdminService constructor.
     *
     * @param Client $client
     * @param Token $token
     */
    public function __construct(Client $client, Token $token)
    {
        self::setClient($client);
        self::setToken($token);
    }

    /**
     * @param array $request
     * @return array
     * @throws GuzzleException|\JsonException
     */
    public static function login(array $request): array
    {
        $response = self::$client->post('https://api.tdameritrade.com/v1/oauth2/token', [
            RequestOptions::JSON => $request
        ]);

        $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        self::$token->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'token' => $response['access_token'], // TODO change
                // this back to access_token
                'refresh_token' => $response['refresh_token'],
                'expires_in' => $response['expires_in'],
                'refresh_token_expires_in' => $response['refresh_token_expires_in'],
//                'token_type' => $response['token_type'], // TODO uncomment
                'scope' => $response['scope'],
            ]
        );

        return $response;
    }

    /**
     * @param array $request
     * @return array
     * @throws GuzzleException
     */
    public function logout(array $request): array
    {
        $response = $this->client->post('https://api.tdameritrade.com/v1/logout', [
            RequestOptions::JSON => $request,
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$this->token->access_token,
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Ensure we are logged in, else login and save our token for later use.
     *
     * @return bool
     */
    public function ensureLogin()
    {
        $admin = Admin::first();

        if ($admin && $admin->is_logged_in) {
            return true;
        }

        $response = AdminAPI::login();

        if ($response['success']) {
            $admin->token = $response['token'];
            $admin->is_logged_in = true;
            $admin->save();

            return true;
        }

        return false;
    }

    /**
     * Place a One Triggers Another order.
     *
     * @param string $symbol
     * @return bool
     */
    public function placeOrder($symbol)
    {
        // Ensure we are logged in
        if (! $this->ensureLogin()) {
            return false;
        }

        // Check for existing orders
        $orders = AdminAPI::getOrdersByPath($symbol);
        if (! empty($orders)) {
            return false;
        }

        // Get current price
        $price = AdminAPI::getLevelOne($symbol);
        if (! $price) {
            return false;
        }

        // Place OTO order
        $response = AdminAPI::placeOrder([
            'orderType' => 'OTO',
            'session' => 'NORMAL',
            'price' => $price,
            'orderStrategyType' => 'SINGLE',
            'orderLegCollection' => [
                [
                    'instruction' => 'BUY',
                    'quantity' => 1,
                    'instrument' => [
                        'symbol' => $symbol,
                        'assetType' => 'EQUITY',
                    ],
                ],
                [
                    'instruction' => 'SELL',
                    'quantity' => 1,
                    'instrument' => [
                        'symbol' => $symbol,
                        'assetType' => 'EQUITY',
                    ],
                    'orderLegType' => 'EQUITY',
                    'price' => $price + 1,
                ],
            ],
        ]);

        if ($response['success']) {
            return true;
        }

        return false;
    }

    public function placeOtoOrderold(string $symbol, float $trailStopPrice)
    {
        $params = [
            'orderType' => 'OTO',
            'session' => 'NORMAL',
            'duration' => 'GTC',
            'orderStrategyType' => 'SINGLE',
            'orderLegCollection' => [
                [
                    'instruction' => 'BUY',
                    'quantity' => 1,
                    'instrument' => [
                        'symbol' => $symbol,
                        'assetType' => 'EQUITY'
                    ]
                ],
                [
                    'instruction' => 'SELL',
                    'quantity' => 1,
                    'instrument' => [
                        'symbol' => $symbol,
                        'assetType' => 'EQUITY'
                    ],
                    'orderType' => 'TRAILING_STOP',
                    'trailStopPrice' => $trailStopPrice
                ]
            ]
        ];

        return $this->client->placeOrder($params);
    }

    public function placeOtoOrder(string $symbol, string $orderType, float $price, int $quantity, string $duration, string $stopPriceType, float $stopPrice, string $orderStrategyType)
    {
        $response = $this->client->request('POST', 'https://api.tdameritrade.com/v1/accounts/{accountId}/orders', [
            'headers' => [
                'Authorization' => 'Bearer '.Token::first()->access_token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'orderType' => $orderType,
                'session' => 'NORMAL',
                'price' => $price,
                'symbol' => $symbol,
                'duration' => $duration,
                'orderStrategyType' => $orderStrategyType,
                'orderLegCollection' => [
                    [
                        'instruction' => 'BUY',
                        'quantity' => $quantity,
                        'orderLegType' => 'EQUITY',
                    ],
                    [
                        'instruction' => 'SELL',
                        'quantity' => $quantity,
                        'orderLegType' => 'EQUITY',
                        'trailingStopPrice' => $stopPrice,
                        'stopPriceType' => $stopPriceType,
                    ],
                ],
            ],
        ]);

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
