<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use App\Models\Song;
use App\Models\SpotifyPlaylist;
use App\Services\Spotify\SpotifyApiClient;
use App\Services\Spotify\SpotifyApiSync;
use Tests\TestCase;

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
                    'syncedSongs'     => '1',
                    'spotifyPlaylist' => SpotifyPlaylist::factory()->make(),
                ]);
        });

        $this->withSession([SpotifyApiClient::ACCESS_TOKEN_SESSION_KEY => 'token'])
            ->post(route('spotify.playlist.sync', ['playlist' => Playlist::factory()->create()]))
            ->assertJsonStructure([
                'data' => [
                    'synced_songs',
                    'spotify_playlist' => [
                        'id',
                        'spotify_playlist_id',
                        'snapshot_id',
                        'url',
                        'recently_created',
                        'songs_count',
                    ],
                ],
            ])
            ->assertOK();
    }

    public function test_route_not_accessible_without_access_token()
    {
        $this
            ->post(route('spotify.playlist.sync', ['playlist' => Playlist::factory()->create()]))
            ->assertRedirect(route('index'));
    }

    public function test_songs_to_sync()
    {
        $songs = Song::factory()->count(2)->create();

        $playlist = Playlist::factory()
            ->hasAttached($songs)
            ->create();

        SpotifyPlaylist::factory()
            ->hasAttached($songs->first())
            ->for($playlist)
            ->create();

        $this->getJson(route('spotify.playlist.sync-count', ['playlist' => $playlist->id]))
            ->assertOk()
            ->assertJsonFragment([
                'data' => [
                    'count' => 1
                ]
            ]);
    }
}
