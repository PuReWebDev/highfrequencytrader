<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| TD Ameritrade API EndPoints Configuration
|--------------------------------------------------------------------------
|
| The following array contains TD Ameritrade Specific endpoint information
| @url https://api.tdameritrade.com/v1/accounts/{accountId}/orders
|
*/
return [
    'api_endpoints' => [
        'getOrders' => [
            'base_url' => 'https://api.tdameritrade.com/',
            'url' => '/v1/accounts/{accountId}/orders',
        ],
    ],
    'api_key' => env('TD_API_CLIENT_ID'),
    'auth_url' => 'https://api.tdameritrade.com/v1/oauth2/token',
    'base_url' => 'https://api.tdameritrade.com',
    'account_id' => 12345,
    // @doc: https://developer.tdameritrade.com/authentication/apis/post/token-0
    'grant_type' => 'authorization_code',
    'refresh_token' => '',
    'access_type' => 'offline',
    'code' => '',
    'client_id' => env('TD_API_CLIENT_ID'),
    'order_threshold' => 1,
    'quantity' => 1,
//    'registerapp' => 'https://auth.tdameritrade.com/auth?response_type=code&redirect_uri=https%3A%2F%2Fhighfrequencytradingservices.com%2Fcallback%2F&client_id=PP8HGBTPVG2IXJ9FY9NRPZFJ7M82UIPR%40AMER.OAUTHAP',
    'registerapp' => 'https://auth.tdameritrade.com/auth?response_type=code&redirect_uri=https://highfrequencytradingservices.com/callback/&client_id=PP8HGBTPVG2IXJ9FY9NRPZFJ7M82UIPR%40AMER.OAUTHAP',
    'redirect_url' => 'https://highfrequencytradingservices.com/callback/',
    'redirect_uri' => 'https://highfrequencytradingservices.com/callback/',
    'callback' => 'https://highfrequencytradingservices.com/callback/',
//    'redirect_url' => 'https://highfrequencytradingservices.com/dashboard',
];
