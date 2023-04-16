<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use App\Models\Token;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    const BASE_URL = "https://api.tdameritrade.com";
    const API_VER = "v1";

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
            'base_uri' => SELF::BASE_URL
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
            'base_uri' => SELF::BASE_URL,
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
            'base_uri' => SELF::BASE_URL,
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
            'base_uri' => SELF::BASE_URL
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
        return $client->getWithAuth('/marketdata/' . $symbol . '/quotes');
    }

    /**
     * quotes
     * Get quote for one or more symbols
     * @param  mixed $symbols
     * @return void
     */
    public static function quotes(array $symbols)
    {
        $token = Token::where('user_id', Auth::id())->get();

        $data = [
            'base_uri' => SELF::BASE_URL,
            'headers'  => [
                'Authorization' => 'Bearer ' . $token['0']['access_token'],
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'apikey' => config('tdameritrade.api_key'),
                'symbol' => implode(',', $symbols)
            ]
        ];

        $client = new Client($data);

        $response = $client->request('get', SELF::API_VER . '/marketdata/quotes', $data);

//        return json_decode((string) $response->getBody()->getContents(), true, 512,
//            JSON_THROW_ON_ERROR);
        $responseData = json_decode((string) $response->getBody()->getContents(), true, 512,
            JSON_THROW_ON_ERROR);

        foreach ($responseData as $key => $value) {
            Log::info('symbol: '. $value['symbol']);
            Log::info('description: '. $value['description']);
        }

        return $responseData;
    }
}
