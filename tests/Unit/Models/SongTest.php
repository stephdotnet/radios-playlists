<?php

use App\Models\Playlist;
use App\Models\Song;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Models
 * @group Unit.Models.Song
 */
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
