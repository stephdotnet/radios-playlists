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
        return $this->belongsToMany(Song::class);
    }

    public function forbiddenSongs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'playlist_forbidden_songs');
    }

    public function spotifyPlaylist(): HasOne
    {
        return $this->hasOne(SpotifyPlaylist::class);
    }

    public function hasSpotifyPlaylist(): bool
    {
        return (bool) $this->spotifyPlaylist;
    }

    public function getRepository()
    {
        return new PlaylistRepository($this);
    }

    public function hasSong(Song $song)
    {
        return $this->getRepository()->hasSong($this, $song);
    }

    public function hasForbiddenSong(Song $song)
    {
        return $this->getRepository()->hasForbiddenSong($this, $song);
    }
}
