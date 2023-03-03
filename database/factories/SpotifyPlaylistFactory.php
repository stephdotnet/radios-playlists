<?php

namespace Database\Factories;

use App\Models\Playlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpotifyPlaylist>
 */
class SpotifyPlaylistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'playlist_id' => Playlist::factory(),
            'spotify_user_id' => $this->faker->uuid(),
            'spotify_playlist_id' => $this->faker->uuid(),
            'snapshot_id' => $this->faker->md5(),
            'data' => [],
        ];
    }

    public function playlist(Playlist $playlist)
    {
        return $this->state([
            'playlist_id' => $playlist->id,
        ]);
    }

    public function withSongs(int $count = 10)
    {
        return $this->has(SongFactory::new()->count($count));
    }

    public function attachedSongs(mixed $songs)
    {
        return $this->hasAttached($songs);
    }
}
