<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

class AcctActivity extends BaseClass
{
    /**
     * Subscribe to account activity for an account.
     *
     * @param  string  $accountId
     * @return array
     */
    public function subscribe(string $accountId): array
    {
        return $this->post('/activity/'.$accountId);
    }

    /**
     * Unsubscribe from account activity for an account.
     *
     * @param  string  $accountId
     * @return bool
     */
    public function unsubscribe(string $accountId): bool
    {
        return $this->delete('/activity/'.$accountId);
    }

    /**
     * Get the account activity for an account.
     *
     * @param  string  $accountId
     * @return array
     */
    public function getActivity(string $accountId): array
    {
        return $this->get('/activity/'.$accountId);
    }

    /**
     * Send a request for account activity for the specified account.
     *
     * @param string $credential
     * @param array $parameters
     * @param Cache $cache
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|\JsonException
     */
    public static function getAccountActivity(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        $accountId = $parameters['accountId'];
        $startDate = $parameters['startDate'];
        $endDate = $parameters['endDate'];

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/accounts/{$accountId}/activity", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'query' => [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ],
            ]);

            $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            // Parse response and save to database
            self::saveAcctActivity($data);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }

    private static function saveAcctActivity($response): void
    {
        $acctActivity = new AcctActivityModel();
        $acctActivity->accountId = $response['accountId'];
        $acctActivity->eventType = $response['eventType'];
        // Set other attributes as needed
        $acctActivity->save();
    }

}
