<?php

namespace App\Http\Controllers;

use App\Models\Playlist;

class PlaylistController extends Controller
{
    public function index()
    {
        return view('playlist.index', [
            'playlists' => Playlist::all(),
        ]);
    }

    public function show(Playlist $playlist)
    {
        return view('playlist.show', [
            'playlist' => $playlist->load('songs'),
        ]);
    }
}
