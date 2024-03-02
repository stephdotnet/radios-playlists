<?php

namespace Tests\Unit\Models;

use App\Models\Playlist;
use App\Models\Song;
use App\Models\SpotifyPlaylist;
use Tests\Fixtures\Spotify\SpotifyPlaylistFixtures;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Models
 * @group Unit.Models.SpotifyPlaylist
 */
class SpotifyPlaylistTest extends TestCase
{
    public function test_missing_songs()
    {
        $attachedSongs   = Song::factory(3)->create();
        $allSongs        = Song::factory(2)->create()->merge($attachedSongs);
        $playlist        = Playlist::factory()->hasAttached($allSongs)->create();
        $spotifyPlaylist = SpotifyPlaylist::factory()->playlist($playlist)->hasAttached($attachedSongs)->create();

        $this->assertEquals(2, $spotifyPlaylist->getMissingSongs()->count());
    }

    public function test_update_snapshot_id()
    {
        $spotifyPlaylist = SpotifyPlaylist::factory()->create();

        $spotifyPlaylist->updateSnapshotId(SpotifyPlaylistFixtures::getBasicPlaylist());

        $this->assertEquals('345', $spotifyPlaylist->snapshot_id);
    }
}
