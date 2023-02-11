<?php

namespace Tests\Unit\Services\Spotify;

use App\Services\Spotify\SpotifyApiClient;
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
}
