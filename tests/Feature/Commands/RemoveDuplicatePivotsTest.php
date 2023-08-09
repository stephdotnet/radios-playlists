<?php

namespace Tests\Feature\Commands;

use App\Models\Playlist;
use App\Models\Song;
use Tests\TestCase;

/**
 * @group Feature
 * @group Feature.Commands
 * @group Feature.Commands.RemoveDuplicate
 */
class RemoveDuplicatePivotsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_playlist_duplicate_removed()
    {
        $playlist = Playlist::factory()->create();
        $songNotOk = Song::factory()->create();
        $songOk = Song::factory()->create();

        $playlist->songs()->attach($songNotOk);
        $playlist->songs()->attach($songNotOk);
        $playlist->songs()->attach($songOk);

        $this->assertCount(2, $playlist->refresh()->songs()->where('song_id', $songNotOk->id)->get());
        $this->assertCount(1, $playlist->refresh()->songs()->where('song_id', $songOk->id)->get());

        $this->artisan('fix:remove-duplicates');

        $this->assertCount(1, $playlist->refresh()->songs()->where('song_id', $songNotOk->id)->get());
        $this->assertCount(1, $playlist->refresh()->songs()->where('song_id', $songOk->id)->get());
    }
}
