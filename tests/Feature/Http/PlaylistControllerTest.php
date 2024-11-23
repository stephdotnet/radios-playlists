<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
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
                        'url'
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

    public function test_songs_order()
    {
        $playlist = Playlist::factory()->create();

        for ($i = 1; $i <= 2; $i++) {
            $song = Song::factory()->create();
            $playlist->songs()->attach($song, ['created_at' => now()->addDay($i)]);
        }

        $songs = $this->getJson(route('playlist.show', ['playlist' => $playlist->id]))
            ->assertOk()
            ->json('data.songs');

        $this->assertEquals(2, Arr::get($songs, '0.id'));
        $this->assertEquals(1, Arr::get($songs, '1.id'));
    }
}
