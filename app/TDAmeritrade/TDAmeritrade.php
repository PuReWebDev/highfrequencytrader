<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use App\Models\Account;
use App\Models\Mover;
use App\Models\Price;
use App\Models\Quote;
use App\Models\Symbol;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JsonException;

/**
 * @method Api\Accounts accounts() Account balances, positions, and orders for all linked accounts.
 * @method Api\Instruments instruments() Search for instrument and fundamental data
 * @method Api\Makert market() Operating hours of markets
 * @method Api\Movers movers() Retrieve mover information by index symbol, direction type and change
 * @method Api\Options options() Get Option Chains for optionable symbols
 * @method Api\Orders orders() All orders for a specific account
 * @method Api\Price price() Historical price data for charts
 * @method Api\Transactions transactions() APIs to access transaction history on the account
 * getAccessToken() Get the current access token
 * string getRefreshToken() Get the current Refresh Token
 * static string generateOAuth() Generate a OAuth Link
 * static \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse  redirectOAuth() Redirect to the Oauth Link
 * mixed refreshToken() Refresh the current access token using the refresh token
 * static mixed createAccessToken(string $code = null) Allows you to create an access token using the code given from Oauth
 */

class TDAmeritrade
{
    public const BASE_URL_TD = "https://api.tdameritrade.com";
    public const API_VER = "v1";
    public const BASE_URL_AV = 'https://www.alphavantage.co';


    protected $access_token;
    protected $refresh_token;

    public function  __construct($access_token = null, $refresh_token = null)
    {
        $token = Token::where('user_id', Auth::id())->get();
        $this->access_token = $token['0']['access_token'];
        $this->refresh_token = $refresh_token;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    public static function generateOAuth()
    {
        return "https://auth.tdameritrade.com/auth?response_type=code&redirect_uri=" . config('tdameritrade.callback') . "&client_id=" . config('tdameritrade.api_key') . "%40AMER.OAUTHAP";
    }

    public static function redirectOAuth()
    {
        return redirect(static::generateOAuth());
    }

    public static function refreshToken($refreshToken)
    {
        $body = [
            'grant_type' => "refresh_token",
            'access_type' => 'offline',
            'client_id' => config('tdameritrade.api_key'),
            'refresh_token' => $refreshToken
        ];

        return static::post('/oauth2/token', [
            'form_params' => $body
        ]);
    }

    public static function createAccessToken(string $code = null)
    {
        $body = [
            'grant_type' => 'authorization_code',
            'access_type' => 'offline',
            'client_id' => config('tdameritrade.api_key'),
            'redirect_uri' => config('tdameritrade.callback'),
            'code'  => $code
        ];

        return static::post('/oauth2/token', [
            'form_params' => $body
        ]);
    }

    public static function post(string $path, array $data = [])
    {
        $client = new Client([
            'base_uri' => SELF::BASE_URL_TD
        ]);

        try {
            Log::debug('Authentication Data',$data);
            $res = $client->request('post', SELF::API_VER . $path, $data);
            return json_decode((string)$res->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function postWithAuth(string $path, array $data = [])
    {
        $client = new Client([
            'base_uri' => SELF::BASE_URL_TD,
            'headers'  => ['Authorization' => 'Bearer ' . $this->access_token]
        ]);

        try {
            $res = $client->request('post', SELF::API_VER . $path, $data);
            return json_decode((string)$res->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getWithAuth(string $path, array $data = [])
    {
        dd('No call here');
        $token = Token::where('user_id', Auth::id())->get();
        $client = new Client([
            'base_uri' => SELF::BASE_URL_TD,
            'headers'  => ['Authorization' => 'Bearer ' . $token['0']['access_token']]
        ]);

//        try {
//        } catch (GuzzleException $e) {
            $res = $client->request('get', SELF::API_VER . $path, $data);
            Log::debug('Quote Response:', $res);
            return json_decode((string)$res->getBody()->getContents(), true, 512,
                JSON_THROW_ON_ERROR);
            throw new Exception($e->getMessage());
//        }
    }

    public function __call($method, $parameters)
    {
        $class =  static::getNamespace() . ucfirst($method);
        return new $class($this);
    }


    public static function getNamespace()
    {
        return __NAMESPACE__ . '\\Api\\';
    }

    public static function isAccessTokenExpired($timestamp):bool
    {
        # Create anchor time and another date time to be compared
        $anchorTime = Carbon::createFromFormat("Y-m-d H:i:s", $timestamp);
        $currentTime = Carbon::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:00"));
# count difference in minutes
        $minuteDiff = $anchorTime->diffInMinutes($currentTime);

        if ($minuteDiff > 25) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $authResponse
     */
    public static function saveTokenInformation(mixed $authResponse): void
    {
        Token::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'access_token' => $authResponse['access_token'] ?? null,
                'refresh_token' => $authResponse['refresh_token'] ?? null,
                'scope' => $authResponse['scope'] ?? null,
                'expires_in' => $authResponse['expires_in'] ?? null,
                'refresh_token_expires_in' => $authResponse['refresh_token_expires_in'] ?? null,
                'token_type' => $authResponse['token_type'] ?? null,
            ]
        );
    }

    /**
     * list
     * Account balances, positions, and orders for all linked accounts.
     * @param  mixed $fields
     * @return void
     */
    public static function list(string $fields = 'positions,orders')
    {
        return (new Client([
            'base_uri' => SELF::BASE_URL_TD
        ]))->getWithAuth('/accounts', [
            'query' => ['fields' => $fields]
        ]);
    }


    /**
     * get
     * Account balances, positions, and orders for a specific account.
     * @param  string $account_id
     * @return void
     */
    public function get(string  $account_id)
    {
        return $this->client->getWithAuth('/accounts/' . $account_id);
    }

    /**
     * quote
     * Get quote for a symbol
     * @param  mixed $symbol
     * @return void
     */
    public static function quote(string $symbol)
    {
        $client = new Client();
        
        $response = $client->request('get', SELF::API_VER . '/marketdata/' . $symbol . '/quote', $data);

        $responseData = json_decode((string) $response->getBody()->getContents(), true, 512,
            JSON_THROW_ON_ERROR);

        return $client->get();
    }

    /**
     * quotes
     * Get quote for one or more symbols
     * @param mixed $symbols
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     */
    public static function quotes(array $symbols): mixed
    {
        $data = [
            'base_uri' => SELF::BASE_URL_TD,
            'query' => [
                'apikey' => config('tdameritrade.api_key'),
                'symbol' => implode(',', $symbols)
            ]
        ];

        $client = new Client($data);

        $response = $client->request('get', SELF::API_VER . '/marketdata/quotes', $data);

        $responseData = json_decode((string) $response->getBody()->getContents(), true, 512,
            JSON_THROW_ON_ERROR);

        foreach ($responseData as $key => $value) {

            Quote::create(
                [
                    'symbol' => $value['symbol'],
                    'description' => $value['description'],
                    'bidPrice' => $value['bidPrice'],
                    'bidSize' => $value['bidSize'],
                    'bidId' => $value['bidId'],
                    'askPrice' => $value['askPrice'],
                    'askSize' => $value['askSize'],
                    'askId' => $value['askId'],
                    'lastPrice' => $value['lastPrice'],
                    'lastSize' => $value['lastSize'],
                    'lastId' => $value['lastId'],
                    'openPrice' => $value['openPrice'],
                    'highPrice' => $value['highPrice'],
                    'lowPrice' => $value['lowPrice'],
                    'closePrice' => $value['closePrice'],
                    'netChange' => $value['netChange'],
                    'totalVolume' => $value['totalVolume'],
                    'quoteTimeInLong' => $value['quoteTimeInLong'],
                    'tradeTimeInLong' => $value['tradeTimeInLong'],
                    'mark' => $value['mark'],
                    'exchange' => $value['exchange'],
                    'exchangeName' => $value['exchangeName'],
                    'marginable' => $value['marginable'],
                    'shortable' => $value['shortable'],
                    'volatility' => $value['volatility'],
                    'digits' => $value['digits'],
                    '52WkHigh' => $value['52WkHigh'],
                    '52WkLow' => $value['52WkLow'],
                    'peRatio' => $value['peRatio'],
                    'divAmount' => $value['divAmount'],
                    'divYield' => $value['divYield'],
                    'securityStatus' => $value['securityStatus'],
                    'regularMarketLastPrice' => $value['regularMarketLastPrice'],
                    'regularMarketLastSize' => $value['regularMarketLastSize'],
                    'regularMarketNetChange' => $value['regularMarketNetChange'],
                    'regularMarketTradeTimeInLong' => $value['regularMarketTradeTimeInLong'],
                ]
            );
        }

        return Quote::whereIn('symbol', $symbols)->where('created_at', '>',
                Carbon::now()->subSeconds(5)->toDateTimeString())->latest()
            ->get();
    }

    /**
     * Get the chart history for a symbol.
     *
     * @param string $symbol
     * @param string $periodType
     * @param int $period
     * @param string $frequencyType
     * @param int $frequency
     * @param string $endDate
     * @param string $startDate
     * @param bool|string $extendedHours
     * @return array
     * @throws JsonException
     */
    public static function getPriceHistory(
        string      $symbol,
        string      $periodType = 'day',
        int         $period = 1,
        string      $frequencyType = 'minute',
        int         $frequency = 1,
        string      $endDate = '',
        string      $startDate = '',
        bool|string $extendedHours = 'false'): array
    {
        $data = [
            'base_uri' => SELF::BASE_URL_TD,
            'query' => [
                'apikey' => config('tdameritrade.api_key'),
                'periodType' => $periodType,
//                'period' => $period,
                'frequencyType' => $frequencyType,
                'frequency' => $frequency,
                'extendedHours' => (string)$extendedHours,
            ]
        ];

        if (empty($period)) {
            if (empty($startDate)) {
                $data['query']['startDate'] = Carbon::now()->subHours(24)
                    ->timestamp;
            }
            if (empty($endDate)) {
                $data['query']['endDate'] = Carbon::now()->timestamp;
            }
        } else {
            $data['query']['period'] = $period;
        }

        $client = new Client($data);

        try {
            $response = $client->request('get', SELF::API_VER . '/marketdata/'.$symbol.'/pricehistory', $data);
        } catch (GuzzleException $guzzleException) {
            return [
                'success' => false,
                'error' => $guzzleException->getMessage(),
            ];
        }

        $responseData = json_decode((string) $response->getBody()->getContents
    (), true,
        512,
            JSON_THROW_ON_ERROR);

        self::processIncomingPrices($responseData, $symbol);

        return $responseData;
    }

    /**
     * Get Movers.
     *
     * @param string $exchange
     * @return array
     * @throws JsonException
     */
    public static function getMovers(string $exchange): array
    {
        Accounts::tokenPreFlight();
        $token = Token::where('user_id', Auth::id())->get();

        $data = [
            'base_uri' => SELF::BASE_URL_TD,
            'headers'  => [
                'Authorization' => 'Bearer ' . $token['0']['access_token'],
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'apikey' => config('tdameritrade.api_key'),
                'direction' => 'up',
                'change' => 'value',
            ]
        ];

        $client = new Client($data);

        try {
            $response = $client->request('get', SELF::API_VER . '/marketdata/'.$exchange.'/movers', $data);
        } catch (GuzzleException $guzzleException) {
            return [
                'success' => false,
                'error' => $guzzleException->getMessage(),
            ];
        }

        return json_decode((string) $response->getBody()->getContents
    (), true,
        512,
            JSON_THROW_ON_ERROR);
    }

    private static function processIncomingPrices(array $prices, string
    $symbol):void
    {
        foreach ($prices['candles'] as $candle) {
            $candle['symbol'] = $symbol;
            self::savePriceData($candle);
        }
    }

    /**
     * savePriceData
     * Saves The Price Data To Database
     * @param array $candle
     */
    private static function savePriceData(array $candle):void
    {
        Price::updateOrCreate([
            'symbol' => $candle['symbol'],
            'datetime' => $candle['datetime'],
        ],[
            'symbol' => $candle['symbol'],
            'open' => $candle['open'],
            'high' => $candle['high'],
            'low' => $candle['low'],
            'close' => $candle['close'],
            'volume' => $candle['volume'],
            'datetime' => $candle['datetime'],
        ]);
    }

    /**
     * getOrders
     * Get Orders For Account
     * @param string $status
     * @throws GuzzleException
     * @throws JsonException
     */
    public static function getOrders(string $status = ''): void
    {
        Accounts::tokenPreFlight();
        $token = Token::where('user_id', Auth::id())->get();
        $account = Account::where('user_id', Auth::id())->get();
        $fromEnteredTime = Carbon::today()->toDateString();

        if (strtoupper($status) === 'FULL') {
//            $fromEnteredTime = '2023-04-14';
            $fromEnteredTime = '2023-05-16';
            $status = '';
        }

        $data = [
            'base_uri' => SELF::BASE_URL_TD,
            'headers'  => [
                'Authorization' => 'Bearer ' . $token['0']['access_token'],
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'apikey' => config('tdameritrade.api_key'),
                'fromEnteredTime' => $fromEnteredTime,
                'toEnteredTime' => Carbon::today()->toDateString(),
            ]
        ];

        if (!empty($status)) {
            $data['query']['status'] = $status;
        }

        $client = new Client($data);

        try {
            $response = $client->request('get', SELF::API_VER . '/accounts/'
                . $account['0']['accountId'] .'/orders', $data);

            $responseData = json_decode((string) $response->getBody()->getContents(), true, 512,
                JSON_THROW_ON_ERROR);

            Accounts::processIncomingOrders($responseData);
        } catch (GuzzleException $guzzleException) {
            Log::debug('Failed To Retrieve Orders', ['errors' =>
                $guzzleException->getMessage()]);
        }

    }

    /**
     * getOrder
     * Get Orders For Account
     * @param string $orderId
     * @throws JsonException
     */
    public static function getOrder(string $orderId): void
    {
        Accounts::tokenPreFlight();
        $token = Token::where('user_id', Auth::id())->get();
        $account = Account::where('user_id', Auth::id())->get();


        $data = [
            'base_uri' => SELF::BASE_URL_TD,
            'headers'  => [
                'Authorization' => 'Bearer ' . $token['0']['access_token'],
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'apikey' => config('tdameritrade.api_key'),
            ]
        ];

        $client = new Client($data);

        try {
            $response = $client->request('get', SELF::API_VER . '/accounts/'
                . $account['0']['accountId'] .'/orders/'.$orderId, $data);

            $responseData = json_decode((string) $response->getBody()->getContents(), true, 512,
                JSON_THROW_ON_ERROR);

            Accounts::saveOrdersInformation($responseData);
        } catch (GuzzleException $guzzleException) {
            Log::debug('Failed To Retrieve Order', ['errors' =>
                $guzzleException->getMessage()]);
        }

    }

    /**
     * getSymbol
     * Get Symbol Fundamentals
     * @param string $symbol
     * @throws JsonException
     */
    public static function getSymbol(string $symbol): void
    {
        $data = [
            'base_uri' => SELF::BASE_URL_AV,
            'headers'  => [
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'function' => 'OVERVIEW',
                'symbol' => $symbol,
                'apikey' => config('tdameritrade.api_key_av'),
            ]
        ];

        $client = new Client($data);

        try {
            $response = $client->request('get','/query', $data);
            $responseData = json_decode($response->getBody()->getContents(), true, 512,
                JSON_THROW_ON_ERROR);

            self::processIncomingSymbol($responseData);
        } catch (GuzzleException $guzzleException) {
            Log::debug('Failed To Retrieve Symbol', ['errors' =>
                $guzzleException->getMessage()]);
        }
    }

    private static function processIncomingSymbol(array $symbol): void
    {
        Symbol::updateOrCreate([
            'symbol' => $symbol['Symbol'],
        ],[
            'symbol' => $symbol['Symbol'],
            'AssetType' => $symbol['AssetType'],
            'Name' => $symbol['Name'],
            'Description' => $symbol['Description'],
            'CIK' => $symbol['CIK'],
            'Exchange' => $symbol['Exchange'],
            'Currency' => $symbol['Currency'],
            'Country' => $symbol['Country'],
            'Sector' => $symbol['Sector'],
            'Industry' => $symbol['Industry'],
            'Address' => $symbol['Address'],
            'FiscalYearEnd' => $symbol['FiscalYearEnd'],
            'LatestQuarter' => $symbol['LatestQuarter'],
            'MarketCapitalization' => $symbol['MarketCapitalization'],
            'EBITDA' => $symbol['EBITDA'],
            'PERatio' => $symbol['PERatio'],
            'PEGRatio' => $symbol['PEGRatio'],
            'BookValue' => $symbol['BookValue'],
            'DividendPerShare' => $symbol['DividendPerShare'],
            'DividendYield' => $symbol['DividendYield'],
            'EPS' => $symbol['EPS'],
            'RevenuePerShareTTM' => $symbol['RevenuePerShareTTM'],
            'ProfitMargin' => $symbol['ProfitMargin'],
            'OperatingMarginTTM' => $symbol['OperatingMarginTTM'],
            'ReturnOnAssetsTTM' => $symbol['ReturnOnAssetsTTM'],
            'ReturnOnEquityTTM' => $symbol['ReturnOnEquityTTM'],
            'RevenueTTM' => $symbol['RevenueTTM'],
            'GrossProfitTTM' => $symbol['GrossProfitTTM'],
            'DilutedEPSTTM' => $symbol['DilutedEPSTTM'],
            'QuarterlyEarningsGrowthYOY' => $symbol['QuarterlyEarningsGrowthYOY'],
            'QuarterlyRevenueGrowthYOY' => $symbol['QuarterlyRevenueGrowthYOY'],
            'AnalystTargetPrice' => $symbol['AnalystTargetPrice'],
            'TrailingPE' => $symbol['TrailingPE'],
            'ForwardPE' => $symbol['ForwardPE'],
            'PriceToSalesRatioTTM' => $symbol['PriceToSalesRatioTTM'],
            'PriceToBookRatio' => $symbol['PriceToBookRatio'],
            'EVToRevenue' => $symbol['EVToRevenue'],
            'EVToEBITDA' => $symbol['EVToEBITDA'],
            'Beta' => $symbol['Beta'],
            '52WeekHigh' => $symbol['52WeekHigh'],
            '52WeekLow' => $symbol['52WeekLow'],
            '50DayMovingAverage' => $symbol['50DayMovingAverage'],
            '200DayMovingAverage' => $symbol['200DayMovingAverage'],
            'SharesOutstanding' => $symbol['SharesOutstanding'],
            'DividendDate' => $symbol['DividendDate'],
            'ExDividendDate' => $symbol['ExDividendDate'],
        ]);
    }

    /**
     * @param $orders
     * @return array
     */
    public static function extracted($orders): array
    {
        $workingCount = $orders->countBy(function ($item) {
            if ($item['status'] === 'WORKING') {
                return $item['status'];
            }
        });
        $filledCount = $orders->countBy(function ($item) {
            if ($item['status'] === 'FILLED') {
                return $item['status'];
            }
        });
        $rejectedCount = $orders->countBy(function ($item) {
            if ($item['status'] === 'REJECTED') {
                return $item['status'];
            }
        });
        $cancelledCount = $orders->countBy(function ($item) {
            if ($item['status'] === 'CANCELED') {
                return $item['status'];
            }
        });
        $expiredCount = $orders->countBy(function ($item) {
            if ($item['status'] === 'EXPIRED') {
                return $item['status'];
            }
        });
        $stoppedCount = $orders->countBy(function ($item) {
            if (!empty($item['stopPrice']) && $item['status'] === 'FILLED') {
                $then = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at']);
                if($then->diffInMinutes(Carbon::now()) < 5)
                {
                    return $item['status'];
                }
            }
        });
        $stoppedTotalCount = $orders->countBy(function ($item) {
            if (!empty($item['stopPrice']) && $item['status'] === 'FILLED') {
                    return $item['status'];
            }
        });
        return array($workingCount, $filledCount, $rejectedCount,
            $cancelledCount, $expiredCount, $stoppedCount,$stoppedTotalCount);
    }


    /**
     * cancelOrder
     * Cancel Order For Account
     * @throws GuzzleException
     * @throws JsonException
     */
    public static function cancelOrder($orderId): void
    {
        $token = Token::where('user_id', Auth::id())->get();
        $account = Account::where('user_id', Auth::id())->get();

        $data = [
            'base_uri' => SELF::BASE_URL_TD,
            'headers'  => [
                'Authorization' => 'Bearer ' . $token['0']['access_token'],
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'apikey' => config('tdameritrade.api_key'),
            ]
        ];

        $client = new Client($data);

        $client->request('DELETE', SELF::API_VER . '/accounts/'
            . $account['0']['accountId'] .'/orders/'.$orderId, $data);
    }

    public static function updateMovers(): void
    {
        $movers = self::getMovers('$COMPX');
        foreach ($movers as $mover) {
            self::saveMovers($mover);
        }

        usleep(500000);
        $spxMovers = self::getMovers('$SPX.X');
        foreach ($spxMovers as $spxMover) {
            self::saveMovers($spxMover);
        }
    }

    private static function saveMovers(array $data): void
    {
        Mover::updateOrCreate([
            'symbol' => $data['symbol'],
        ],[
            'change' => $data['change'],
            'description' => $data['description'],
            'direction' => $data['direction'],
            'last' => $data['last'],
            'symbol' => $data['symbol'],
            'totalVolume' => $data['totalVolume'],
        ]);
    }
}
