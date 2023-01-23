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
    'api_key' => env('TD_AMERITRADE_API_KEY'),
    'auth_url' => 'https://api.tdameritrade.com/v1/oauth2/token',
    'base_url' => 'https://api.tdameritrade.com',
    'account_id' => 12345,
    'redirect_uri' => 'http://localhost',
    'client_id' => '12345',
    'client_secret' => '12345',
    'refresh_token' => '12345',
];
