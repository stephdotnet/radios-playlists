<?php

namespace App\Models;

use App\Repositories\PlaylistRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Model
 */
class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
    ];

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class)->withTimestamps();
    }

    public function forbiddenSongs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'playlist_forbidden_songs')->withTimestamps();
    }

    public function spotifyPlaylist(): HasOne
    {
        return $this->hasOne(SpotifyPlaylist::class);
    }

    public function hasSpotifyPlaylist(): bool
    {
        return (bool) $this->spotifyPlaylist;
    }

    public function getRepository(): PlaylistRepository
    {
        return new PlaylistRepository;
    }

    public function hasSong(Song $song): bool
    {
        return $this->getRepository()->hasSong($this, $song);
    }

    public function oldestSong(): ?Song
    {
        return $this->getRepository()->oldestSong($this);
    }

    public function newestSong(): ?Song
    {
        return $this->getRepository()->newestSong($this);
    }

    public function hasForbiddenSong(Song $song): bool
    {
        return $this->getRepository()->hasForbiddenSong($this, $song);
    }
}
