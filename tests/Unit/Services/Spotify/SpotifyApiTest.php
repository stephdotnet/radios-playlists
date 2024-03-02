<?php

namespace Tests\Unit\Services\Spotify;

use App\Services\Parser\ParserResponse;
use App\Services\Spotify\SpotifyApi;
use SpotifyWebAPI\SpotifyWebAPI as SpotifyWebApiBasePackage;
use Tests\Fixtures\Spotify\SpotifySearchFixtures;
use Tests\Mocks\SpotifyApiClientMock;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Services
 * @group Unit.Services.Spotify
 * @group Unit.Services.Spotify.Api
 */
class SpotifyApiTest extends TestCase
{
    public function test_matching_song()
    {
        SpotifyApiClientMock::make()
            ->makeClientCredentialsClientMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock->shouldReceive('search')->andReturn(
                    SpotifySearchFixtures::getMatchingSong(),
                );
            })
            ->bind();

        $matchingSong = app(SpotifyApi::class)->getMatchingSong(new ParserResponse('bliss', 'muse'));

        $this->assertArrayHasKey('name', $matchingSong);
    }

    public function test_spotify_api_client_returns_proper_instance()
    {
        $this->assertInstanceOf(
            SpotifyWebApiBasePackage::class,
            app(SpotifyApi::class)->getAuthenticatedClient(),
        );
    }

    public function test_set_return_assoc()
    {
        $this->assertInstanceOf(
            SpotifyApi::class,
            app(SpotifyApi::class)->setReturnAssoc(true),
        );
    }
}
