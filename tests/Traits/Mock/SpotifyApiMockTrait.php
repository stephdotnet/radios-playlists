<?php

namespace Tests\Traits\Mock;

use App\Services\Spotify\SpotifyApi;
use Illuminate\Support\Arr;
use Mockery\MockInterface;
use Tests\Fixtures\Spotify\SpotifySearchFixtures;

trait SpotifyApiMockTrait
{
    protected function mockSpotifyApi(): MockInterface
    {
        return $this->partialMock(SpotifyApi::class, function ($mock) {
            $mock
                ->shouldAllowMockingProtectedMethods()
                ->shouldReceive('getToken')
                ->andReturn('token');
        });
    }

    protected function mockGetMatchingSong($mock, array $response = null)
    {
        if (is_null($response)) {
            $response = SpotifySearchFixtures::getMatchingSong();
        }

        $mock->shouldReceive('getMatchingSong')
            ->andReturn(Arr::get($response, 'tracks.items.0', []))
            ->once();
    }
}
