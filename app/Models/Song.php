<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'spotify_id',
        'name',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)->withTimestamps();
    }

    public function getArtistsAttribute()
    {
        return collect(data_get($this->data, 'artists'))->pluck('name')->implode(', ');
    }

    public function getSpotifyUrlAttribute()
    {
        return data_get($this->data, 'external_urls.spotify');
    }
}
