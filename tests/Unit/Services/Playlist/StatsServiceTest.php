<?php

namespace Tests\Unit\Services\Playlist;

use App\Models\Playlist;
use App\Models\Song;
use App\Services\Playlist\Stats;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StatsServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_service_stats_generation(): void
    {
        $playlist = Playlist::factory()->create();

        $playlist->songs()
            ->attach(
                Song::factory()->create(),
                ['created_at' => Carbon::now()->subDays(3)->startOfDay()],
            );

        $playlist->songs()
            ->attach(
                Song::factory()->create(),
                ['created_at' => null],
            );

        foreach (CarbonPeriod::create(Carbon::now()->subDays(1), '1 day', Carbon::now()) as $period) {
            $playlist->songs()->attach(
                Song::factory()->create(),
                ['created_at' => $period->startOfDay()],
            );
        }

        $result = app(Stats::class)->setPlaylist($playlist)->make()->data;

        $this->assertEquals([
            Carbon::now()->subDays(3)->startOfDay()->toIso8601String() => [
                'total' => 1,
                'count' => 1,
            ],
            Carbon::now()->subDays(2)->startOfDay()->toIso8601String() => [
                'total' => 1,
                'count' => 0,
            ],
            Carbon::now()->subDays(1)->startOfDay()->toIso8601String() => [
                'total' => 2,
                'count' => 1,
            ],
            Carbon::now()->startOfDay()->toIso8601String() => [
                'total' => 3,
                'count' => 1,
            ],
        ], $result);
    }

    #[DataProvider(methodName: 'data_provider')]
    public function test_format_methods($method, $expected)
    {
        $playlist = Playlist::factory()->create();
        $result   = app(Stats::class)->setPlaylist($playlist)->make()->{$method}();

        $this->assertEquals($expected, $result);
    }

    public static function data_provider(): array
    {
        return [
            [
                'method'   => 'json',
                'expected' => '[]'
            ],
            [
                'method'   => 'array',
                'expected' => []
            ],
        ];
    }
}
