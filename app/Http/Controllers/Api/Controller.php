<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Log;

/**
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var JsonResponse
     */
    protected $response;

    public function __construct() 
    {
        $this->response = new JsonResponse;
    }

    /**
     * Send a success response to application
     * @param mixed $data
     * @param int   $responseCode
     * @return JsonResponse
     */
    public function success(mixed $data, int $responseCode=200) : JsonResponse
    {
        $this->response->setData($data);
        $this->response->setStatusCode($responseCode);
        return $this->response;
    }

    /**
     * Send and log request errors
     * @param \Exception $e
     * @param int       $responseCode
     * @return JsonResponse
     */
    public function error(\Exception $e, int $responseCode=500) : JsonResponse
    {
        $this->log($e);
        $this->response->setData($e->getMessage());
        $this->response->setStatusCode($responseCode);
        return $this->response;
    }
}