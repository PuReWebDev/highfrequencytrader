<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use App\Models\MarketHour;
use App\Models\Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MarketHours
{
    /**
     * Get hours for multiple markets.
     *
     * @param array $markets
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public static function getHoursForMultipleMarkets(array $markets): array
    {
        $accessToken = self::getAccessToken();
        $client = new Client();

        $response = $client->get(
            'https://api.tdameritrade.com/v1/marketdata/hours',
            [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                ],
                'query' => [
                    'markets' => implode(',', $markets),
                ],
            ]
        );

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get hours for a single market.
     *
     * @param string $market
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public static function getHoursForSingleMarket(string $market): array
    {
        $accessToken = Token::where('user_id', Auth::id())->get();

        if (TDAmeritrade::isAccessTokenExpired
            ($accessToken['0']['updated_at']) === true) {
            // Time To Refresh The Token
            self::saveTokenInformation(TDAmeritrade::refreshToken($accessToken['0']['refresh_token']));
            Log::info('The Token Was Refreshed During This Process');
        }
        $client = new Client();

        $response = $client->get(
            "https://api.tdameritrade.com/v1/marketdata/{$market}/hours",
            [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken['0']['access_token']}",
                ],
            ]
        );

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
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

    public static function saveHours(array $hours)
    {
        foreach ($hours as $hour) {
            $marketHour = new MarketHour([
                "symbol" => $hour["symbol"],
                "regularMarketStart" => $hour["regularMarketStart"],
                "regularMarketEnd" => $hour["regularMarketEnd"],
                "extendedMarketStart" => $hour["extendedMarketStart"],
                "extendedMarketEnd" => $hour["extendedMarketEnd"],
                "status" => $hour["status"],
            ]);
            $marketHour->save();
        }
    }

    /**
     * Send a request to the TD Ameritrade API
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @return array
     */
    private static function sendRequest(string $method, string $url, array $data = [])
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request($method, $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . self::getAccessToken(),
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }

        return [
            'success' => true,
            'data' => json_decode($response->getBody()->getContents())
        ];
    }


    /**
     * Check if the specified market is open
     * @param string $market
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public static function isMarketOpen(string $market): bool
    {
        $dt = Carbon::now();
        $dt->toDateString();
        $MarketHour = self::getMarketHour($dt);

        if (empty($MarketHour['0'])) {
            Log::info('Fetching Market Hours');

            $hours = self::getHoursForSingleMarket($market);

            if (!empty($hours['equity'])) {
                Log::debug('isOpen', $hours['equity']['equity']);
            }

            Log::debug('Market Hours Response', $hours);


            if (!empty($hours['equity']['EQ'])) {
                self::saveMarketHours($hours['equity']['EQ'], 'regularMarket');
                self::saveMarketHours($hours['equity']['EQ'], 'preMarket');
                self::saveMarketHours($hours['equity']['EQ'], 'postMarket');
            }

            $MarketHour = self::getMarketHour($dt);
        }

        if (empty($MarketHour['0'])) {
            return false;
        }

        return Carbon::now()->between(Carbon::parse(str_replace('-04:00','',$MarketHour['0']['start'])),
            Carbon::parse(str_replace('-04:00', '', $MarketHour['0']['end'])));;
    }

    /**
     * Get the access token.
     *
     * @return string
     * @throws \JsonException|\GuzzleHttp\Exception\GuzzleException
     */
    protected static function getAccessToken(): string
    {
        $accessToken = Cache::get('td_ameritrade_access_token');

        if (!$accessToken) {
            $client = new Client();
            $response = $client->post(
                'https://api.tdameritrade.com/v1/oauth2/token',
                [
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => config('tdameritrade.refresh_token'),
                        'client_id' => config('tdameritrade.client_id'),
                    ],
                ]
            );

            $responseData = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $accessToken = $responseData['access_token'];

            Cache::put('td_ameritrade_access_token', $accessToken, now()->addSeconds($responseData['expires_in']));
        }

        return $accessToken;
    }

    /**
     * @param array $EQ
     * @param string $marketType
     */
    private static function saveMarketHours(array $EQ, string $marketType): void
    {
        MarketHour::updateOrCreate(
            [
                'date' => $EQ['date'],
                'market' => $marketType,
            ],
            [
                'date' => $EQ['date'],
                'market' => $marketType,
                'start' => $EQ['sessionHours'][$marketType]['0']['start'],
                'end' => $EQ['sessionHours'][$marketType]['0']['end'],
            ]
        );
    }

    /**
     * @param Carbon $dt
     * @return mixed
     */
    private static function getMarketHour(Carbon $dt)
    {
        $MarketHour = MarketHour::where([
            ['date', '=', $dt->toDateString()],
            ['market', '=', 'regularMarket'],
        ])->get();
        return $MarketHour;
    }
}
