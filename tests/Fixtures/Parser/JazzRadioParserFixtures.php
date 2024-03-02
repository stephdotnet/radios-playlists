<?php

namespace Tests\Fixtures\Parser;

use Tests\Fixtures\FixturesAbstractClass;

class JazzRadioParserFixtures extends FixturesAbstractClass
{
    public static function getProg($file = 'parser/jazzRadio/prog.xml'): string
    {
        return self::getFixtureFromStorage($file);
    }
}
