<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TradeEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initiate Trade Engine For Client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        return 0;
    }

    public function getOrdersByPath(int $accountId): bool
    {
        $retry_count = 0;
        $response = null;
        $type = 'account';
        do {
            try {
                self::setGuzzleClient(new Client());
                self::$log_errors['error'] = null;
                self::$log_errors['payload'] = json_decode(
                    $payload,
                    false,
                    512,
                    JSON_THROW_ON_ERROR
                );

                $response = self::$guzzleClient->request(
                    'POST',
                    config("datadog.api_endpoints.$type.url"),
                    [
                        'debug' => env('APP_DEBUG') === 1,
                        'base_uri' => config("tdameritrade.api_endpoints.$type.base_url"),
                        'headers' => [
                            'DD-API-KEY' => env('DD_API_KEY'),
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'cache-control' => 'no-cache',
                        ],
                        'body' => $payload,
                        'timeout' => 15.00,
                    ]
                );

                $response = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            } catch (ConnectException $e) {
                self::sendError($e);
                sleep(10);
            } catch (RequestException $e) {
                self::sendError($e);
                sleep(10);
            } catch (GuzzleException $e) {
                self::sendError($e);
            } catch (JsonException $e) {
                self::sendError($e);
                break;
            }

            if (++$retry_count === 5) {
                self::$log_errors['error'] = 'Error: Failed To Send Payload to DataDog';
                Log::error(self::datadogFormatter(
                    self::getLogErrors(),
                    'Error: Failed To Send DataDog Payload After 5 tries'
                ));
                break;
            }
        } while (! is_array($response));

        if (! is_array($response)) {
            return false;
        }

        return true;
    }
}
