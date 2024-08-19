<?php

namespace Tests\Unit\Services\Parser;

use App\Exceptions\Services\Parser\InvalidParserException;
use App\Services\Parser\ParserResponse;
use Tests\TestCase;

class ParserResponseTest extends TestCase
{
    public function test_mock_parser_driver()
    {
        $this->expectException(InvalidParserException::class);
        ParserResponse::make('', '');
    }
}
