<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaylistResource;
use App\Models\Playlist;

class PlaylistController extends Controller
{
    public function index()
    {
        return PlaylistResource::collection(Playlist::all());
    }

    public function show(Playlist $playlist)
    {
        return PlaylistResource::make($playlist->load('songs'));
    }
}
