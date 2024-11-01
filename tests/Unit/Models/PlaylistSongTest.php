<?php

namespace Models;

use App\Models\Playlist;
use App\Models\Song;
use Tests\TestCase;

class PlaylistSongTest extends TestCase
{
    public function test_playlist_relationship()
    {
        $playlist = Playlist::factory()->create();
        $song     = Song::factory()->create();

        $playlist->songs()->save($song);

        $pivot = $playlist->songs->first()->pivot;

        $this->assertNotNull($pivot->created_at);
        $this->assertNotNull($pivot->updated_at);

        $playlist->songs->first()->pivot->update(['created_at' => null]);
    }
}
