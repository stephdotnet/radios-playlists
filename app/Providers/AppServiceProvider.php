<?php

namespace App\Providers;

use App\Services\Parser\ParserService;
use App\Services\Spotify\SpotifyApi;
use App\Services\Spotify\SpotifyApiClient;
use App\Services\Spotify\SpotifyApiSync;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ParserService::class, function ($app) {
            return new ParserService($app);
        });

        $this->app->singleton(SpotifyApiClient::class, function () {
            $session = new Session(
                config('services.spotify.client_id'),
                config('services.spotify.client_secret'),
                route('spotify.callback'),
            );
            $client = new SpotifyWebAPI;

            return new SpotifyApiClient($session, $client);
        });

        $this->app->singleton(SpotifyApi::class, function () {
            return new SpotifyApi;
        });

        $this->app->singleton(SpotifyApiSync::class, function () {
            return new SpotifyApiSync(app(SpotifyApi::class)->getAuthenticatedClient());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::shouldBeStrict();
    }
}
