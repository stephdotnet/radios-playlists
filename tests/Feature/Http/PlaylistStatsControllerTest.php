<?php

namespace Http;

use App\Models\Playlist;
use App\Models\Song;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaylistStatsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $playlist = Playlist::factory()->create();

        $playlist->songs()->attach(Song::factory([
            'created_at' => Carbon::now()->subDays(5),
        ])->create());

        foreach (CarbonPeriod::create(Carbon::now()->subDays(3), '1 day', Carbon::now()) as $period) {
            $playlist->songs()->attach(Song::factory([
                'created_at' => $period->startOfDay(),
            ])->create());
        }

        $this->getJson(route('playlist.stats.show', ['playlist' => $playlist]))
            ->assertOk()
            ->assertJsonFragment([
                [
                    'total' => 1,
                    'count' => 1,
                ]
            ])
            ->assertJsonFragment([
                [
                    'total' => 1,
                    'count' => 0,
                ]
            ])
            ->assertJsonFragment([
                [
                    'total' => 2,
                    'count' => 1,
                ]
            ])
            ->assertJsonFragment([
                [
                    'total' => 3,
                    'count' => 1,
                ]
            ])
            ->assertJsonFragment([
                [
                    'total' => 4,
                    'count' => 1,
                ]
            ])
            ->assertJsonFragment([
                [
                    'total' => 5,
                    'count' => 1,
                ]
            ])
            ->assertJsonCount(6);
    }
}
