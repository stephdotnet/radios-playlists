<?php

namespace App\Services\Parser;

class ParserResponse
{
    public function __construct(public string $song, public string $artist)
    {
    }
}
