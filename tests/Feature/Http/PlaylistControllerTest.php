<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
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
            ])
            ->assertJsonCount(5, 'data.songs');
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
}
