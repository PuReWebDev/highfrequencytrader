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
];
