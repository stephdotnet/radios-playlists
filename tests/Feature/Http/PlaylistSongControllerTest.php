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
 * @group Feature.Http.PlaylistSong
 */
class PlaylistSongControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_songs()
    {
        $playlist = Playlist::factory()->withSongs(10)->create();

        $this->getJson(route('playlist.songs', ['playlist' => $playlist->id]))
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

    public function test_term_search()
    {
        $song = Song::factory([
            'name' => 'song name',
            'data' => [
                'artists' => [
                    ['name' => 'artist one'],
                    ['name' => 'artist two'],
                ],
            ],
        ])
            ->create();

        $playlist = Playlist::factory()
            ->hasAttached($song)
            ->create();

        $this->getJson(
            route('playlist.songs', ['playlist' => $playlist->id, 'filter' => [
                'term' => 'nomatch',
            ]]),
        )
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->getJson(
            route('playlist.songs', ['playlist' => $playlist->id, 'filter' => [
                'term' => 'song',
            ]]),
        )
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
