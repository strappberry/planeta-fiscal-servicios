<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Http::macro('planetaFiscalApi', function () {
            return Http::withHeaders([
                'Authorization' => 'Bearer ' . config('planetafiscal.planeta_fiscal_api_token'),
                'Accept'        => 'application/json',
            ])->baseUrl(config('planetafiscal.planeta_fiscal_api'));
        });
    }
}
