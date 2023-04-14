<?php

namespace App\Services;

use App\Models\Price;
use App\Models\Token;
use App\Traits\HasCaching;
use App\Traits\HasErrorHandling;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PriceService
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected static Client $client;

    /**
     * @param Client $client
     */
    public static function setClient(Client $client): void
    {
        self::$client = $client;
    }

    /**
     * The API endpoint.
     *
     * @var string
     */
    protected static string $endpoint = 'https://api.tdameritrade.com/v1/marketdata';

    /**
     * Create a new service instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        self::setClient($client);
//        $this->client = $client;
    }

    /**
     * Get the current price for a given symbol.
     *
     * @param  string  $symbol
     * @return \App\Models\Price
     */
    public static function getPrice(string $symbol): Price
    {
        self::setClient(new Client());
        $token = Token::where('user_id', Auth::id())->get();
        $url = "https://api.tdameritrade.com/v1/marketdata/{$symbol}/pricehistory";

        $response = self::$client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token['0']['access_token'],
                'Content-Type' => 'application/json'
            ],
            'body' => [
                'periodType' => 'day',
                'period' => 1,
                'frequencyType' => 'min'
            ],
        ]);

        try {
//            $response = self::$client->get($url);
            $response = self::$client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token['0']['access_token'],
                    'Content-Type' => 'application/json'
                ],
//            'body' => $symbol,
            ]);
        } catch (ClientException $e) {
            return Log::info('Exception Thrown:'. $e->getMessage());
        } catch (GuzzleException $e) {
            return Log::info('Exception Thrown:'. $e->getMessage());
        }

        $data = json_decode($response->getBody(), true);

        dd($data);
        return Price::updateOrCreate(
            [
                'symbol' => $symbol,
                'price' => $data['last']['price'],
//                'timestamp' => $data['last']['timestamp'],
            ],
            [
                'symbol' => $symbol,
                'price' => $data['last']['price'],
            ]
        );
    }
}
