<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'spotify_id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'data' => $this->makeFakeData(),
        ];
    }

    protected function makeFakeData()
    {
        return [
            'name' => $this->faker->name,
            'artists' => [
                [
                    'name' => $this->faker->name,
                ],
            ],
            'album' => [
                'name' => $this->faker->name,
            ],
            'duration_ms' => $this->faker->numberBetween(1000, 100000),
            'preview_url' => $this->faker->url,
            'external_urls' => [
                'spotify' => $this->faker->url,
            ],
        ];
    }
}
