<?php

namespace Commands\Fixes;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class FixTimestampsPlaylistSongTest extends TestCase
{
    protected Playlist $playlist;

    protected function setUp(): void
    {
        parent::setUp();

        $this->playlist = Playlist::factory([
            'created_at' => now()->subMonth()
        ])->create();
    }

    public function test_relation_has_date_of_song(): void
    {
        Song::factory([
            'created_at' => now(),
        ])->playlist($this->playlist)->create();

        $this->playlist->songs->first()->pivot
            ->update(['created_at' => null, 'updated_at' => null]);

        Artisan::call('app:fix-timestamps-playlist-song');

        $this->assertEquals(
            $this->playlist->songs->first()->pivot->refresh()->created_at->format('Y-m-d'),
            now()->format('Y-m-d'),
        );
    }

    public function test_when_song_older_than_playlist(): void
    {
        Song::factory([
            'created_at' => now()->subYear(),
        ])->playlist($this->playlist)->create();

        $this->playlist->songs->first()->pivot
            ->update(['created_at' => null, 'updated_at' => null]);

        Artisan::call('app:fix-timestamps-playlist-song');

        $this->assertNull($this->playlist->songs->first()->pivot->refresh()->created_at);
    }
}