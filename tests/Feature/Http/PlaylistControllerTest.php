<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use App\Models\Song;
use App\Models\SpotifyPlaylist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaylistControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        Playlist::factory()->withSongs(5)->create();

        $this->getJson(route('playlist.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'slug',
                        'name',
                        'songs_count',
                        'active',
                        'songs_to_sync',
                        'url'
                    ],
                ],
            ])
            ->assertJsonFragment([
                'songs_to_sync' => 0
            ]);
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

        $this->getJson(route('playlist.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'slug',
                        'name',
                        'songs_count',
                        'active',
                        'songs_to_sync',
                        'url'
                    ],
                ],
            ])
            ->assertJsonFragment([
                'songs_to_sync' => 1
            ]);
    }

    public function test_show()
    {
        $playlist = Playlist::factory()->withSongs(5)->create();

        $this->getJson(route('playlist.show', ['playlist' => $playlist->id]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'slug',
                    'songs_count',
                    'active',
                ],
            ])
            ->assertJsonFragment([
                'songs_to_sync' => 0
            ]);
    }
}
