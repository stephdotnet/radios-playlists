<?php

namespace App\Console\Commands\Fixes;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FixTimestampsPlaylistSong extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:fix-timestamps-playlist-song';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Timestamps Playlist Song';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Parcours les playlists
        Playlist::all()->each(function (Playlist $playlist) {
            $this->log('Fix for Playlist : ' . $playlist->id);
            $playlist->songs()->chunk(50, function (Collection $songs) use ($playlist) {
                $songs->each(function (Song $song) use ($playlist) {
                    if ($song->created_at < $playlist->created_at) {
                        $this->log('Song ignored #' . $song->id);

                        return;
                    }

                    $song->playlists()->updateExistingPivot($playlist, [
                        'created_at' => $song->created_at,
                        'updated_at' => now(),
                    ]);
                });
            });
        });
    }

    protected function log($message): void
    {
        $this->line($message);
        $this->info($message);
    }
}
