<?php

namespace Tests\Fixtures\Parser;

use Tests\Fixtures\FixturesAbstractClass;

class RadiosFrParserFixtures extends FixturesAbstractClass
{
    public static function getNowPlaying($file = 'parser/radiosFr/now-playing.json'): array
    {
        return self::getFixtureFromStorageAsArray($file);
    }

    public static function getBadResponse($file = 'parser/radiosFr/now-playing-fail.json'): array
    {
        return self::getFixtureFromStorageAsArray($file);
    }
}
