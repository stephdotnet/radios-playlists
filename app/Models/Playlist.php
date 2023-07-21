<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function spotifyPlaylist(): HasOne
    {
        return $this->hasOne(SpotifyPlaylist::class);
    }

    public function hasSpotifyPlaylist(): bool
    {
        return (bool) $this->spotifyPlaylist;
    }
}
