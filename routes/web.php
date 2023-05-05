<?php

use App\Http\Controllers\CallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('callback', CallbackController::class);

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth'])
    ->name('dashboard');

Route::get('/account', [\App\Http\Controllers\AccountController::class, 'index'])
    ->middleware(['auth'])
    ->name('account');

Route::get('/preference', [\App\Http\Controllers\PreferenceController::class, 'index'])
    ->middleware(['auth'])
    ->name('preference');

Route::resource('/orders', [\App\Http\Controllers\OrderController::class, 'index'])
    ->middleware(['auth'])
    ->name('orders');

Route::get('/trade', [\App\Http\Controllers\TradeController::class, 'index'])
    ->middleware(['auth'])
    ->name('trade');

Route::get('/watch-list', [\App\Http\Controllers\WatchListController::class, 'index'])
    ->middleware(['auth'])
    ->name('watch-list');

Route::get('/balances', [\App\Http\Controllers\BalancesController::class, 'index'])
    ->middleware(['auth'])
    ->name('balances');

Route::get('/movers', [\App\Http\Controllers\MoverController::class, 'index'])
    ->middleware(['auth'])
    ->name('movers');

require __DIR__.'/auth.php';
