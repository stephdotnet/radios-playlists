<?php

namespace Tests\Fixtures\Spotify;

use Tests\Fixtures\FixturesAbstractClass;

class SpotifySearchFixtures extends FixturesAbstractClass
{
    public static function getMatchingSong($file = 'spotify/search/matching-song.json'): array
    {
        return self::getFixtureFromStorageAsArray($file);
    }
}
