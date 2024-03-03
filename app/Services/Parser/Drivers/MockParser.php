<?php

namespace App\Services\Parser\Drivers;

use App\Services\Parser\ParserAbstractClass;
use App\Services\Parser\ParserResponse;

class MockParser extends ParserAbstractClass
{
    public function parse(): ParserResponse
    {
        return new ParserResponse('song', 'artist');
    }
}
