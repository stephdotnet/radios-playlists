<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use App\Models\Song;
use App\Services\Spotify\SpotifyApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Feature
 * @group Feature.Http
 * @group Feature.Http.Playlist
 */
class PlaylistControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        Playlist::factory()->withSongs(5)->create();

        $this->getJson('/api/playlists')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'slug',
                        'songs_count',
                    ],
                ],
            ]);
    }

    public function test_show()
    {
        Playlist::factory()->withSongs(5)->create();

        $this->getJson('/api/playlists/1')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'slug',
                    'songs_count',
                ],
            ]);
    }

    public function test_songs()
    {
        Playlist::factory()->withSongs(10)->create();

        $this->getJson('/api/playlists/1/songs')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'artists',
                        'spotify_url',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_delete_song()
    {
        $this->partialMock(SpotifyApiClient::class, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        });

        $song = Song::factory()->create();
        $playlist = Playlist::factory()
            ->hasAttached($song)
            ->create();

        $this->assertCount(1, $playlist->songs);

        $this
            ->withSession([SpotifyApiClient::ACCESS_TOKEN_SESSION_KEY => 'token'])
            ->deleteJson(
                route('playlist.songs.delete', [
                    'playlist' => $playlist->id,
                    'song' => $song->id,
                ])
            )
            ->assertNoContent();

        $this->assertCount(0, $playlist->refresh()->songs);
    }
}
