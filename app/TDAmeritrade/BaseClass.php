<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use GuzzleHttp\Client;

abstract class BaseClass
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The TD Ameritrade API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Create a new BaseClass instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @param  string  $apiKey
     * @return void
     */
    public function __construct(Client $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * Make a GET request to the TD Ameritrade API.
     *
     * @param  string  $uri
     * @param  array  $query
     * @return array
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
     * @param  string  $uri
     * @param  array  $data
     * @return array
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
     * @param  string  $path
     * @param  array  $data
     * @return array
     */
    protected function put(string $path, array $data): array
    {
        $response = $this->client->put($path, [
            'headers' => $this->headers,
            'json' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Make a DELETE request to the TD Ameritrade API.
     *
     * @param  string  $path
     * @return array
     */
    protected function delete(string $path): array
    {
        $response = $this->client->delete($path, [
            'headers' => $this->headers,
        ]);

        return json_decode((string) $response->getBody(), true);
    }

}
