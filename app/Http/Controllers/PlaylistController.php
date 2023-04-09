<?php

namespace App\Http\Controllers;

use App\Http\Filters\SongTermSimpleFilter;
use App\Http\Resources\PlaylistResource;
use App\Http\Resources\SongResource;
use App\Models\Playlist;
use App\Models\Song;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
        $query = QueryBuilder::for($playlist->songs())
            ->allowedFilters([
                AllowedFilter::custom('term', new SongTermSimpleFilter),
            ]);

        return SongResource::collection(
            $query->paginate(request('per_page', self::DEFAULT_PAGINATION))
        );
    }

    public function deleteSong(Playlist $playlist, Song $song)
    {
        $playlist->songs()->detach($song);

        return response()->json(null, 204);
    }
}
