<?php

namespace App\Providers;

use App\TDAmeritrade\AcctActivity;
use App\TDAmeritrade\Admin;
use App\TDAmeritrade\ChartHistory;
use App\TDAmeritrade\LevelOne;
use App\TDAmeritrade\Order;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Admin::class, function ($app) {
            return new Admin($app['GuzzleHttp\Client'], config('td-ameritrade.api_key'));
        });

        $this->app->singleton(AcctActivity::class, function ($app) {
            return new AcctActivity($app['GuzzleHttp\Client'], config('td-ameritrade.api_key'));
        });

        $this->app->singleton(ChartHistory::class, function ($app) {
            return new ChartHistory($app['GuzzleHttp\Client'], config('td-ameritrade.api_key'));
        });

        $this->app->singleton(LevelOne::class, function ($app) {
            return new LevelOne($app['GuzzleHttp\Client'], config('td-ameritrade.api_key'));
        });

        $this->app->singleton(Order::class, function ($app) {
            return new Order($app['GuzzleHttp\Client'], config('td-ameritrade.api_key'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(in_array(Request::ip(), ['67.9.66.186'])) {
            config(['app.debug' => true]);
        }
    }
}
