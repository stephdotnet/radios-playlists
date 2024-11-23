<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int                             $id
 * @property int                             $playlist_id
 * @property int                             $spotify_user_id
 * @property string                          $spotify_playlist_id
 * @property string                          $snapshot_id
 * @property array                           $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Playlist $playlist
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 *
 * @method static \Database\Factories\SpotifyPlaylistFactory                    factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist whereSnapshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist whereSpotifyPlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist whereSpotifyUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SpotifyPlaylist whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class)->withTimestamps();
    }

    public function getMissingSongs(): BelongsToMany
    {
        $songs = $this->songs->pluck('spotify_id')->toArray();

        return $this
            ->playlist
            ->songs()
            ->whereNotIn('spotify_id', $songs);
    }

    public function updateSnapshotId($httpPlaylist): bool
    {
        return $this->update([
            'snapshot_id' => $httpPlaylist['snapshot_id'],
            'data'        => $httpPlaylist,
        ]);
    }
}
