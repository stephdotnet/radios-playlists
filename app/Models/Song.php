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

    public function playlistss()
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function getArtistsAttribute()
    {
        return collect(data_get($this->data, 'artists'))->pluck('name')->implode(', ');
    }
}
