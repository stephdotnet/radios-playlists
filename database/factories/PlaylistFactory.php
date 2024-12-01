<?php

namespace Database\Factories;

use App\Models\Song;
use App\Models\SpotifyPlaylist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Playlist>
 */
class PlaylistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'slug' => Str::slug($this->faker->name),
        ];
    }

    public function withSongs(int $count = 10): PlaylistFactory|Factory
    {
        return $this->has(SongFactory::new()->count($count), 'songs');
    }

    public function withSpotifyPlaylist(?SpotifyPlaylistFactory $spotifyPlaylist = null): PlaylistFactory|Factory
    {
        return $this->has(
            $spotifyPlaylist ?? SpotifyPlaylist::factory(),
            'songs',
        );
    }

    public function hasForbiddenSongAttached(Song $song): PlaylistFactory|Factory
    {
        return $this->hasAttached($song, [], 'forbiddenSongs');
    }
}
