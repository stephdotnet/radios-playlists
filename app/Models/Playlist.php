<?php

namespace App\Models;

use App\Repositories\PlaylistRepository;
use Database\Factories\PlaylistFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @mixin Model
 *
 * @property int         $id
 * @property string      $slug
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection<int, Song> $forbiddenSongs
 * @property-read int|null $forbidden_songs_count
 * @property-read Collection<int, Song> $songs
 * @property-read int|null $songs_count
 * @property-read SpotifyPlaylist|null $spotifyPlaylist
 *
 * @method static PlaylistFactory          factory($count = null, $state = [])
 * @method static Builder<static>|Playlist newModelQuery()
 * @method static Builder<static>|Playlist newQuery()
 * @method static Builder<static>|Playlist query()
 * @method static Builder<static>|Playlist whereCreatedAt($value)
 * @method static Builder<static>|Playlist whereId($value)
 * @method static Builder<static>|Playlist whereName($value)
 * @method static Builder<static>|Playlist whereSlug($value)
 * @method static Builder<static>|Playlist whereUpdatedAt($value)
 *
 * @mixin Eloquent
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
