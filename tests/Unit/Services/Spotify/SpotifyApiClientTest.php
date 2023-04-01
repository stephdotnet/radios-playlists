<?php

namespace Tests\Unit\Services\Spotify;

use App\Services\Spotify\SpotifyApiClient;
use Illuminate\Support\Facades\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Tests\Mocks\SpotifyApiClientMock;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Services
 * @group Unit.Services.Spotify
 * @group Unit.Services.Spotify.ApiClient
 */
class SpotifyApiClientTest extends TestCase
{
    public function test_api_client_service_provider()
    {
        $service = app(SpotifyApiClient::class);

        $this->assertEquals(SpotifyApiClient::class, get_class($service));
    }

    public function test_get_client_credentials_token()
    {
        SpotifyApiClientMock::make()
            ->makeClientCredentialsClientMock()
            ->bind();

        $client = app(SpotifyApiClient::class)->getClientCredentialsClient();
        $this->assertEquals(SpotifyWebAPI::class, get_class($client));
    }

    public function test_request_access_token_is_stored_in_session()
    {
        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->bind();

        app(SpotifyApiClient::class)->requestAccessToken(SpotifyApiClientMock::FAKE_CODE);

        $this->assertEquals(
            SpotifyApiClientMock::FAKE_ACCESS_TOKEN,
            Session::get(SpotifyApiClient::ACCESS_TOKEN_SESSION_KEY)
        );
        $this->assertEquals(
            SpotifyApiClientMock::FAKE_REFRESH_TOKEN,
            Session::get(SpotifyApiClient::REFRESH_TOKEN_SESSION_KEY)
        );
    }

    public function test_get_authorize_url_stores_state_in_session()
    {
        SpotifyApiClientMock::make()
            ->makeGetAuthorizeUrlSessionMock()
            ->bind();

        $authorizeUrl = app(SpotifyApiClient::class)->getAuthorizeUrl();

        $this->assertEquals(SpotifyApiClientMock::FAKE_STATE, Session::get(SpotifyApiClient::STATE_SESSION_KEY));
        $this->assertEquals(SpotifyApiClientMock::FAKE_REDIRECT_URL, $authorizeUrl);
    }

    public function test_is_authenticated_function()
    {
        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->bind();

        app(SpotifyApiClient::class)->requestAccessToken(SpotifyApiClientMock::FAKE_CODE);

        $this->assertTrue(app(SpotifyApiClient::class)->isAuthenticated());
    }

    public function test_revoke_client()
    {
        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->bind();

        app(SpotifyApiClient::class)->requestAccessToken(SpotifyApiClientMock::FAKE_CODE);

        $this->assertTrue(app(SpotifyApiClient::class)->isAuthenticated());

        app(SpotifyApiClient::class)->revokeAuthenticatedClientToken();

        $this->assertFalse(app(SpotifyApiClient::class)->isAuthenticated());
    }

    public function test_is_admin_passes_with_good_admin_id()
    {
        $this->app['config']->set('services.spotify.admin_id', '1234');

        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock->shouldReceive('me')
                    ->andReturn((object) ['id' => config('services.spotify.admin_id')]);
            })
            ->bind();

        app(SpotifyApiClient::class)->requestAccessToken(SpotifyApiClientMock::FAKE_CODE);

        $this->assertTrue(app(SpotifyApiClient::class)->isAdmin());
    }

    public function test_is_admin_does_not_with_bad_admin_id()
    {
        $this->app['config']->set('services.spotify.admin_id', '1234');

        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock->shouldReceive('me')
                    ->andReturn((object) ['id' => '3456']);
            })
            ->bind();

        app(SpotifyApiClient::class)->requestAccessToken(SpotifyApiClientMock::FAKE_CODE);

        $this->assertFalse(app(SpotifyApiClient::class)->isAdmin());
    }
}
