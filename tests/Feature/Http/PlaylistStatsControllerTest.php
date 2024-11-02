<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use App\Services\Playlist\Stats;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PlaylistStatsControllerTest extends TestCase
{
    public function test_index()
    {
        $playlist = Playlist::factory()->create();

        $this->partialMock(Stats::class)
            ->shouldReceive('json')
            ->andReturn(json_encode([
                Carbon::now()->subDay()->toIso8601String() => [
                    'total' => 1,
                    'count' => 1,
                ],
                Carbon::now()->toIso8601String() => [
                    'total' => 2,
                    'count' => 1,
                ],
            ]));

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
                    'total' => 2,
                    'count' => 1,
                ]
            ])
            ->assertJsonCount(2);
    }
}
