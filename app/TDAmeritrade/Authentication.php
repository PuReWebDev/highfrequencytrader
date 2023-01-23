<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Authentication
{
    /**
     * The Guzzle client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The base URI for the TD Ameritrade API.
     *
     * @var string
     */
    protected $baseUri = 'https://api.tdameritrade.com';

    /**
     * The client ID for the application.
     *
     * @var string
     */
    protected $clientId;

    /**
     * The client secret for the application.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * The redirect URI for the application.
     *
     * @var string
     */
    protected $redirectUri;

    /**
     * Create a new instance of the Authentication class.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @return void
     */
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;

        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    /**
     * Get the authorization URL for the application.
     *
     * @return string
     */
    public function getAuthorizationUrl(): string
    {
        return $this->baseUri . '/oauth2/authorize?client_id=' . $this->clientId . '&response_type=code&redirect_uri=' . $this->redirectUri;
    }

    /**
     * Get an access token using the authorization code.
     *
     * @param string $code
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function postAccessToken(string $code): array
    {
        try {
            // make the request to the TD Ameritrade API
            $response = $this->client->post('/oauth2/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'access_type' => 'offline',
                    'code' => $code,
                    'client_id' => $this->clientId,
                    'redirect_uri' => $this->redirectUri,
                ],
                'headers' => [
                    'Authorization' => 'Basic '.base64_encode($this->clientId.':'.$this->clientSecret),
                ],
            ]);

            $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

}

