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

        app(SpotifyApiClient::class)->requestAccessToken('code');

        $this->assertEquals(SpotifyApiClientMock::FAKE_ACCESS_TOKEN, Session::get('access_token'));
        $this->assertEquals(SpotifyApiClientMock::FAKE_REFRESH_TOKEN, Session::get('refresh_token'));
    }

    public function test_get_authorize_url_stores_state_in_session()
    {
        SpotifyApiClientMock::make()
            ->makeGetAuthorizeUrlSessionMock()
            ->bind();

        $authorizeUrl = app(SpotifyApiClient::class)->getAuthorizeUrl();

        $this->assertEquals(SpotifyApiClientMock::FAKE_STATE, Session::get('state'));
        $this->assertEquals(SpotifyApiClientMock::FAKE_REDIRECT_URL, $authorizeUrl);
    }

    public function test_is_authenticated_function()
    {
        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->bind();

        app(SpotifyApiClient::class)->requestAccessToken('code');

        $this->assertTrue(app(SpotifyApiClient::class)->isAuthenticated());
    }
}
