<?php

namespace Tests\Mocks;

use App\Services\Spotify\SpotifyApiClient;
use Closure;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiClientMock
{
    use InteractsWithContainer;

    protected $app;

    public function __construct(protected $spotifySessionMock = null, protected $spotifyWebApiMock = null, protected $mock = null)
    {
        $this->app = app();
    }

    public function getMock()
    {
        $spotifyApiClientMock = new SpotifyApiClient(
            $this->spotifySessionMock ?: $this->makeSpotifySession(),
            $this->spotifyWebApiMock ?: $this->makeSpotifyApi()
        );

        return $spotifyApiClientMock;
    }

    public function makeSpotifySessionMock(Closure $callback)
    {
        $this->setSpotifySessionMock(
            $this->partialMock(Session::class, $callback)
        );

        return $this;
    }

    public function makeSpotifyWebApiMock(Closure $callback)
    {
        $this->setSpotifyWebApiMock(
            $this->partialMock(SpotifyWebAPI::class, $callback)
        );

        return $this;
    }

    public function bind(): void
    {
        $this->instance(SpotifyApiClient::class, $this->getMock());
    }

    /*
    |--------------------------------------------------------------------------
    | Public setters
    |--------------------------------------------------------------------------
    */

    public function setSpotifySessionMock($spotifySessionMock)
    {
        $this->spotifySessionMock = $spotifySessionMock;

        return $this;
    }

    public function setSpotifyWebApiMock($spotifyWebApiMock)
    {
        $this->spotifyWebApiMock = $spotifyWebApiMock;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Mock templates
    |--------------------------------------------------------------------------
    */

    public function makeClientCredentialsClientMock()
    {
        $this->makeSpotifySessionMock(function ($mock) {
            $mock->shouldReceive('requestCredentialsToken')->andReturn();
            $mock->shouldReceive('getAccessToken')->andReturn('token');
        });

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    */

    protected function makeSpotifySession()
    {
        return new Session(null, null);
    }

    protected function makeSpotifyApi()
    {
        return new SpotifyWebAPI();
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function make()
    {
        return new self();
    }
}
