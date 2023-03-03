<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use App\Models\SpotifyPlaylist;
use App\Services\Spotify\SpotifyApiClient;
use App\Services\Spotify\SpotifyApiSync;
use Tests\TestCase;

/**
 * @group Feature
 * @group Feature.Http
 * @group Feature.Http.SpotifyPlaylist
 */
class SpotifyPlaylistControllerTest extends TestCase
{
    public function test_sync_playlist_calls_service()
    {
        $this->partialMock(SpotifyApiSync::class, function ($mock) {
            $mock->shouldReceive('setPlaylist')
                ->once()
                ->andReturnSelf();

            $mock->shouldReceive('sync')
                ->once()
                ->andReturn([
                    'syncedSongs' => '1',
                    'spotifyPlaylist' => SpotifyPlaylist::factory()->make(),
                ]);
        });

        $this->withSession([SpotifyApiClient::ACCESS_TOKEN_SESSION_KEY => 'token'])
            ->get(route('spotify.playlist.sync', ['playlist' => 1]))
            ->assertOK();
    }

    public function test_route_not_accessible_without_access_token()
    {
        $this
            ->get(route('spotify.playlist.sync', ['playlist' => Playlist::factory()->create()]))
            ->assertRedirect(route('index'));
    }
}
