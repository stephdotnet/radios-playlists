<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpotifyPlaylistResource;
use App\Models\Playlist;
use App\Services\Spotify\SpotifyApiSync;

class SpotifyPlaylistController extends Controller
{
    public function sync(Playlist $playlist)
    {
        $syncResponse = app(SpotifyApiSync::class)
            ->setPlaylist($playlist)
            ->sync();

        return response()->json(['data' => [
            'synced_songs'     => $syncResponse['syncedSongs'],
            'spotify_playlist' => SpotifyPlaylistResource::make($syncResponse['spotifyPlaylist']),
        ]]);
    }
}
