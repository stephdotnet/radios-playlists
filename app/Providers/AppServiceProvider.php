<?php

namespace App\Providers;

use App\Services\Parser\ParserService;
use App\Services\Spotify\SpotifyApi;
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
        $this->app->singleton(ParserService::class, function ($app) {
            return new ParserService($app);
        });

        $this->app->singleton(SpotifyApi::class, function ($app) {
            return new SpotifyApi($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
