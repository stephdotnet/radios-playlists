<?php

namespace App\Services\Parser;

use App\Exceptions\Services\Parser\InvalidParserException;

class ParserResponse
{
    public function __construct(public string $song, public string $artist)
    {
    }

    /**
     * @throws InvalidParserException
     */
    public static function make(string $song, string $artist): ParserResponse
    {
        if (is_empty($song) || is_empty($artist)) {
            throw new InvalidParserException("Can't make parser for song: ${song} and artist: ${artist}");
        }

        return new ParserResponse($song, $artist);
    }
}
