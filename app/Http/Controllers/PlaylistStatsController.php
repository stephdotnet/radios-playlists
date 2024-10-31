<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Services\Playlist\Stats;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class PlaylistStatsController extends Controller
{
    public function show(Playlist $playlist, Stats $stats)
    {
        return Cache::remember(
            'playlist.stats.id:' . $playlist->id,
            Carbon::now()->addHours(6),
            function () use ($playlist, $stats) {
                return $stats->setPlaylist($playlist)->make()->json();
            },
        );
    }
}
