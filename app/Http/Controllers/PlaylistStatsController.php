<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Services\Playlist\Stats;

class PlaylistStatsController extends Controller
{
    public function show(Playlist $playlist, Stats $stats)
    {
        return $stats->setPlaylist($playlist)->make()->json();
    }
}
