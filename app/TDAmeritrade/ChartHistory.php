<?php

declare(strict_types=1);

namespace App\TDAmeritrade;

/**
 * The ChartHistory class handles requests for chart history data.
 *
 * $chartHistory = new ChartHistory();
$symbol = 'AAPL';
$periodType = 'day';
$period = '1';
$frequencyType = 'minute';
$frequency = '1';
$startDate = '2022-01-01';
$endDate = '2022-01-05';
$extendedHours = 'false';
$chartData = $chartHistory->getChartHistory($symbol, $periodType, $period, $frequencyType, $frequency, $startDate, $endDate, $extendedHours);

 *
 */
class ChartHistory extends BaseClass
{
    /**
     * Get the chart history for a symbol.
     *
     * @param  string  $symbol
     * @param  int  $periodType
     * @param  int  $period
     * @param  int  $frequencyType
     * @param  int  $frequency
     * @param  int  $endDate
     * @param  int  $startDate
     * @param  bool  $extendedHours
     * @return array
     */
    public function getHistory(string $symbol, int $periodType = 1, int $period = 1, int $frequencyType = 1, int $frequency = 1, int $endDate = 1, int $startDate = 1, bool $extendedHours = false): array
    {
        $params = [
            'periodType' => $periodType,
            'period' => $period,
            'frequencyType' => $frequencyType,
            'frequency' => $frequency,
            'endDate' => $endDate,
            'startDate' => $startDate,
            'extendedHours' => $extendedHours,
        ];

        return $this->get('/marketdata/'.$symbol.'/pricehistory', $params);
    }

    /**
     * Send a request for chart history data.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function getChartHistoryold(string $credential, array
    $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();
        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/marketdata/{$parameters['symbol']}/pricehistory", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'query' => [
                    'frequencyType' => $parameters['frequencyType'],
                    'frequency' => $parameters['frequency'],
                    'startDate' => $parameters['startDate'],
                    'endDate' => $parameters['endDate'],
                    'needExtendedHoursData' => $parameters['needExtendedHoursData'],
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            // handle any exceptions thrown during the request
            self::handleException($e);
        }

        return $data;
    }

    /**
     * Send a request for chart history for the specified symbol.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function getChartHistory(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        $symbol = $parameters['symbol'];
        $periodType = $parameters['periodType'];
        $period = $parameters['period'];
        $frequencyType = $parameters['frequencyType'];
        $frequency = $parameters['frequency'];
        $endDate = $parameters['endDate'];
        $startDate = $parameters['startDate'];

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/marketdata/{$symbol}/pricehistory", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'query' => [
                    'periodType' => $periodType,
                    'period' => $period,
                    'frequencyType' => $frequencyType,
                    'frequency' => $frequency,
                    'endDate' => $endDate,
                    'startDate' => $startDate,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            // handle the error if the request fails
            $data = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }


    /**
     * Send a request for chart data for the specified symbol.
     *
     * @param string $credential
     * @param array $parameters
     * @param \Illuminate\Contracts\Cache\Repository $cache
     * @return array
     */
    public static function getChart(string $credential, array $parameters, Cache $cache): array
    {
        self::$cache = $cache;
        self::initClient();

        $symbol = $parameters['symbol'];

        try {
            // make the request to the TD Ameritrade API
            $response = self::$client->get("/v1/marketdata/{$symbol}/pricehistory", [
                'headers' => [
                    'Authorization' => "Bearer {$credential}",
                ],
                'query' => [
                    'periodType' => $parameters['periodType'],
                    'frequencyType' => $parameters['frequencyType'],
                    'frequency' => $parameters['frequency'],
                    'startDate' => $parameters['startDate'],
                    'endDate' => $parameters['endDate'],
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            // handle any exceptions thrown during the request
            self::handleException($e);
        }

        return $data;
    }

}
