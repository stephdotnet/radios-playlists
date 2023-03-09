<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyPlaylist extends Model
{
    use HasFactory;

    protected $fillable = [
        'spotify_user_id',
        'playlist_id',
        'spotify_playlist_id',
        'snapshot_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class)->withTimestamps();
    }

    public function getMissingSongs()
    {
        $songs = $this->songs->pluck('spotify_id')->toArray();

        return $this
            ->playlist
            ->songs()
            ->whereNotIn('spotify_id', $songs);
    }

    public function updateSnapshotId($httpPlaylist)
    {
        return $this->update([
            'snapshot_id' => $httpPlaylist['snapshot_id'],
            'data' => $httpPlaylist,
        ]);
    }
}
