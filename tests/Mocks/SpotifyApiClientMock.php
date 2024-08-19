<?php

namespace Tests\Mocks;

use App\Services\Spotify\SpotifyApiClient;
use Closure;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Mockery;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiClientMock
{
    use InteractsWithContainer;

    const FAKE_REDIRECT_URL = 'https://accounts.spotify.com/authorize?client_id=mocked_client_id&redirect_uri=mocked_redirect_uri&response_type=code&scope=playlist-modify-private+playlist-modify-public+playlist-read-private+user-read-private&state=mocked_state';

    const FAKE_ACCESS_TOKEN = 'mocked_access_token';

    const FAKE_REFRESH_TOKEN = 'mocked_refresh_token';

    const FAKE_STATE = 'mocked_state';

    const FAKE_CODE = 'mocked_code';

    protected $app;

    public function __construct(
        protected $spotifySessionMock = null,
        protected $spotifyWebApiMock = null,
        protected $mock = null,
    ) {
        $this->app = app();
    }

    public function getMock()
    {
        return new SpotifyApiClient(
            $this->spotifySessionMock ?: $this->makeSpotifySession(),
            $this->spotifyWebApiMock ?: $this->makeSpotifyApi(),
        );
    }

    public function makeSpotifySessionMock(Closure $callback)
    {
        $this->setSpotifySessionMock(
            $this->partialMock(Session::class, $callback),
        );

        return $this;
    }

    public function makeSpotifyWebApiMock(Closure $callback)
    {
        $this->setSpotifyWebApiMock(
            $this->partialMock(SpotifyWebAPI::class, $callback),
        );

        return $this;
    }

    public function bind($mock = null): void
    {
        $this->instance(SpotifyApiClient::class, $mock ?: $this->getMock());
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
        return $this->makeSpotifySessionMock(function ($mock) {
            $mock->shouldReceive('requestCredentialsToken')->andReturn();
            $mock->shouldReceive('getAccessToken')->andReturn(self::FAKE_ACCESS_TOKEN);
        });
    }

    public function makeRequestAccessTokenSessionMock()
    {
        return $this->makeSpotifySessionMock(function ($mock) {
            $mock
                ->shouldReceive('requestAccessToken')
                ->with(self::FAKE_CODE);
            $mock
                ->shouldReceive('getAccessToken')
                ->andReturn(self::FAKE_ACCESS_TOKEN);
            $mock
                ->shouldReceive('getRefreshToken')
                ->andReturn(self::FAKE_REFRESH_TOKEN);
        });
    }

    public function makeGetAuthorizeUrlSessionMock()
    {
        return $this->setSpotifySessionMock(
            Mockery::mock(
                new Session('mocked_client_id', 'mocked_client_secret', 'mocked_redirect_uri'),
                function ($mock) {
                    $mock
                        ->shouldReceive('generateState')
                        ->andReturn(self::FAKE_STATE);
                },
            )
                ->makePartial(),
        );
    }

    public function makeFullAuthorizationCodeMock()
    {
        return $this->makeSpotifySessionMock(function ($mock) {
            $mock
                ->shouldReceive('requestAccessToken')
                ->with(self::FAKE_CODE);
            $mock
                ->shouldReceive('generateState')
                ->andReturn(self::FAKE_STATE);
            $mock
                ->shouldReceive('getAccessToken')
                ->andReturn(self::FAKE_ACCESS_TOKEN);
            $mock
                ->shouldReceive('getRefreshToken')
                ->andReturn(self::FAKE_REFRESH_TOKEN);
        });
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
        return new SpotifyWebAPI;
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function make()
    {
        return new self;
    }
}
