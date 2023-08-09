<?php

namespace App\Repositories;

use App\Models\Playlist;
use App\Models\Song;

class PlaylistRepository
{
    public function hasSong(Playlist $playlist, Song $song)
    {
        return $playlist->songs()->where('song_id', $song->id)->exists();
    }

    public function hasForbiddenSong(Playlist $playlist, Song $song)
    {
        return $playlist->forbiddenSongs()->where('song_id', $song->id)->exists();
    }
}
