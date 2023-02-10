<?php

namespace Tests\Unit\Services;

use App\Services\Spotify\SpotifyApiClient;
use SpotifyWebAPI\SpotifyWebAPI;
use Tests\Mocks\SpotifyApiClientMock;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Services
 * @group Unit.Services.SpotifyApiClient
 */
class SpotifyApiClientTest extends TestCase
{
    public function test_get_client_credentials_token()
    {
        SpotifyApiClientMock::make()
            ->makeSpotifySessionMock(function ($mock) {
                $mock->shouldReceive('requestCredentialsToken')->andReturn();
                $mock->shouldReceive('getAccessToken')->andReturn('token');
            })
            ->bind();

        $client = app(SpotifyApiClient::class)->getClientCredentialsClient();
        $this->assertEquals(SpotifyWebAPI::class, get_class($client));
    }
}
