<?php

namespace Tests\Fixtures\Spotify;

use Illuminate\Support\Arr;
use Tests\Fixtures\FixturesAbstractClass;

class SpotifySongFixtures extends FixturesAbstractClass
{
    public static function getSong($file = 'spotify/search/matching-song.json'): array
    {
        $search = self::getFixtureFromStorageAsArray($file);

        return Arr::get($search, 'tracks.items.0');
    }
}
