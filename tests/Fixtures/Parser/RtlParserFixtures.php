<?php

namespace Tests\Fixtures\Parser;

use Tests\Fixtures\FixturesAbstractClass;

class RtlParserFixtures extends FixturesAbstractClass
{
    public static function getPage($file = 'parser/rtl/ok.html'): string
    {
        return self::getFixtureFromStorage($file);
    }
}
