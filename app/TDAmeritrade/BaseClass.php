<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use App\Models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;

abstract class BaseClass
{
    /**
     * The HTTP client instance.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * The TD Ameritrade API key.
     *
     * @var string
     */
    protected mixed $apiKey;

    /**
     * Create a new BaseClass instance.
     *
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => "https://api.tdameritrade.com",
        ]);
        $token = Token::where('user_id', Auth::id())->get();
        $this->apiKey = $token['0']['access_token'];
    }

    /**
     * Make a GET request to the TD Ameritrade API.
     *
     * @param string $uri
     * @param array $query
     * @return array
     * @throws \JsonException|GuzzleException
     */
    public function get(string $uri, array $query = []): array
    {
        $response = $this->client->get($uri, [
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
            ],
            'query' => $query,
        ]);

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Make a POST request to the TD Ameritrade API.
     *
     * @param string $uri
     * @param array $data
     * @return array
     * @throws \JsonException|GuzzleException
     */
    protected function post(string $uri, array $data = []): array
    {
        $response = $this->client->post($uri, [
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
            ],
            'json' => $data,
        ]);

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Make a PUT request to the TD Ameritrade API.
     *
     * @param string $path
     * @param array $data
     * @return array
     * @throws \JsonException
     */
    protected function put(string $path, array $data): array
    {
        $response = $this->client->put($path, [
            'headers' => $this->headers,
            'json' => $data,
        ]);

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Make a DELETE request to the TD Ameritrade API.
     *
     * @param string $path
     * @return array
     * @throws \JsonException|GuzzleException
     */
    protected function delete(string $path): array
    {
        $response = $this->client->delete($path, [
            'headers' => $this->headers,
        ]);

        return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

}
