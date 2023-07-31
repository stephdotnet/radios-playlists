<?php

namespace Database\Factories;

use App\Models\Song;
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

    public function withSongs(int $count = 10)
    {
        return $this->has(SongFactory::new()->count($count), 'songs');
    }

    public function hasForbiddenSongAttached(Song $song)
    {
        return $this->hasAttached($song, [], 'forbiddenSongs');
    }
}
