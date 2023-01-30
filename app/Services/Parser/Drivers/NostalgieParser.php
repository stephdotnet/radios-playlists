<?php

namespace App\Services\Parser;

use App\Services\Parser\Drivers\ParserInterface;

class NostalgieParser implements ParserInterface
{
    public function parse(): ParserResponse
    {
        // guzzle request to nostalgie.fr

        return new ParserResponse('song', 'artist');
    }
}
