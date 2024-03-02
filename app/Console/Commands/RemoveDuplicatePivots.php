<?php

namespace App\Console\Commands;

use App\Models\Playlist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicatePivots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:remove-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicates from pivot playlist_song';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Playlist::all()->each(function ($playlist) {
            DB::table('playlist_song')
                ->where('playlist_id', $playlist->id)
                ->groupBy('song_id')
                ->having(DB::raw('count(song_id)'), '>', 1)
                ->pluck('song_id')
                ->each(function ($id) use ($playlist) {
                    $playlist->songs()->detach($id);
                    $playlist->songs()->attach($id);
                });
        });

        return Command::SUCCESS;
    }
}
