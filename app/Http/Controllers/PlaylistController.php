<?php

namespace App\Http\Controllers;

use App\Http\Filters\SongTermSimpleFilter;
use App\Http\Resources\PlaylistResource;
use App\Http\Resources\SongResource;
use App\Models\Playlist;
use App\Models\Song;
use App\Services\Spotify\SpotifyApi;
use App\Services\Spotify\SpotifyApiSync;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PlaylistController extends Controller
{
    const DEFAULT_PAGINATION = 30;

    const MAX_PAGINATION = 200;

    public function index()
    {
        return PlaylistResource::collection(
            Playlist::query()
                ->withCount('songs')
                ->with('spotifyPlaylist', 'spotifyPlaylist.songs', 'spotifyPlaylist.playlist')
                ->orderBy('songs_count', 'desc')
                ->get(),
        );
    }

    public function show(Playlist $playlist)
    {
        return PlaylistResource::make($playlist
            ->load('spotifyPlaylist', 'spotifyPlaylist.songs', 'spotifyPlaylist.playlist'));
    }

    public function songs(Playlist $playlist)
    {
        $query = QueryBuilder::for($playlist->songs())
            ->allowedFilters([
                AllowedFilter::custom('term', new SongTermSimpleFilter),
            ]);

        return SongResource::collection(
            $query->paginate(request('per_page', self::DEFAULT_PAGINATION)),
        );
    }

    public function deleteSong(Playlist $playlist, Song $song, SpotifyApiSync $spotifyApiSync)
    {
        $client = app(SpotifyApi::class)
            ->getAuthenticatedClient();

        $spotifyPlaylist = $playlist->spotifyPlaylist;

        // Delete song from spotify
        if ($playlist->hasSpotifyPlaylist()) {
            $snapshotId = $client->deletePlaylistTracks(
                $spotifyPlaylist->spotify_playlist_id,
                ['tracks' => [
                    ['uri' => $song->spotify_id],
                ]],
            );
        }

        $playlist->songs()->detach($song);
        $playlist->forbiddenSongs()->attach($song);

        if (isset($snapshotId)) {
            $playlist->spotifyPlaylist->songs()->detach($song);

            $spotifyApiSync->syncPlaylistInformations($playlist->spotifyPlaylist);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
