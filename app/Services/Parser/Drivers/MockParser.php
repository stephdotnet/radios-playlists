<?php

namespace App\Services\Parser\Drivers;

use App\Services\Parser\ParserResponse;

class MockParser implements ParserInterface
{
    public function parse(): ParserResponse
    {
        return new ParserResponse('song', 'artist');
    }
}
