<?php

namespace App\Repositories;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlaylistRepository
{
    public function hasSong(Playlist $playlist, Song $song): bool
    {
        return $playlist->songs()->where('song_id', $song->id)->exists();
    }

    public function hasForbiddenSong(Playlist $playlist, Song $song): bool
    {
        return $playlist->forbiddenSongs()->where('song_id', $song->id)->exists();
    }

    public function oldestSong(Playlist $playlist): ?Song
    {
        return $this->songsByOrder($playlist)->first();
    }

    public function newestSong(Playlist $playlist): ?Song
    {
        return $this->songsByOrder($playlist, 'desc')->first();
    }

    protected function songsByOrder(Playlist $playlist, string $order = 'asc'): BelongsToMany
    {
        return $playlist->songs()
            ->withPivot('created_at')
            ->wherePivotNotNull('created_at')
            ->orderByPivot('created_at', $order);
    }
}
