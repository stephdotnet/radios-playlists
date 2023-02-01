<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Feature
 * @group Feature.Http
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
                    'songs' => [
                        '*' => [
                            'id',
                            'name',
                            'artists',
                            'spotify_url',
                        ],
                    ],
                ],
            ])
            ->assertJsonCount(5, 'data.songs');
    }
}
