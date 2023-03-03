<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Services\Spotify\SpotifyApiSync;

class SpotifyPlaylistController extends Controller
{
    public function sync(Playlist $playlist)
    {
        $spotifyPlaylist = app(SpotifyApiSync::class)
            ->setPlaylist($playlist)
            ->sync($playlist);

        return response()->json(['data' => $spotifyPlaylist]);
    }
}
