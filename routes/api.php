<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function() {
    Route::get('/price/history/{symbol}', 'App\Http\Controllers\Api\PriceHistoryApiController@show');
    Route::get('/quote/{symbol}', 'App\Http\Controllers\Api\QuoteApiController@show');
    Route::get('/quotes', 'App\Http\Controllers\Api\QuoteApiController@index');
    Route::get('/quotes/search', 'App\Http\Controllers\Api\QuoteApiController@search');
});
