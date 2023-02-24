<?php

namespace Tests\Fixtures\Spotify;

use Tests\Fixtures\FixturesAbstractClass;

class SpotifyUserFixtures extends FixturesAbstractClass
{
    public static function getMe($file = 'spotify/user/user.json'): array
    {
        return self::getFixtureFromStorageAsArray($file);
    }
}
