<?php

namespace Tests\Unit\Models;

use App\Models\Playlist;
use App\Models\Song;
use Tests\TestCase;

class SongTest extends TestCase
{
    public function test_playlist_relationship()
    {
        $playlist = Playlist::factory()->create();
        $song     = Song::factory()
            ->playlist($playlist)
            ->create();

        $this->assertEquals($playlist->id, $song->playlists->first()->id);
    }
}
