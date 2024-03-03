<?php

namespace Tests\Fixtures\Parser;

use Tests\Fixtures\FixturesAbstractClass;

class NostalgieParserFixtures extends FixturesAbstractClass
{
    public static function getHistory($file = 'parser/nostalgie/api.json'): string
    {
        return self::getFixtureFromStorage($file);
    }
}
