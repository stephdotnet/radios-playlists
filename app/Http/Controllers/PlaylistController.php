<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaylistResource;
use App\Http\Resources\SongResource;
use App\Models\Playlist;
use App\Models\Song;

class PlaylistController extends Controller
{
    const DEFAULT_PAGINATION = 30;

    const MAX_PAGINATION = 200;

    public function index()
    {
        return PlaylistResource::collection(Playlist::all());
    }

    public function show(Playlist $playlist)
    {
        return PlaylistResource::make($playlist);
    }

    public function songs(Playlist $playlist)
    {
        return SongResource::collection(
            $playlist->songs()
            ->paginate(request('per_page', self::DEFAULT_PAGINATION))
        );
    }

    public function deleteSong(Playlist $playlist, Song $song)
    {
        $playlist->songs()->detach($song);

        return response()->json(null, 204);
    }
}
