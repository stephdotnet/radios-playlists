<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
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
                        'songs_count',
                    ],
                ],
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
            ]);
    }
}
