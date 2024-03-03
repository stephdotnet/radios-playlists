<?php

namespace Services\Parser;

use App\Exceptions\Services\Parser\InvalidParserException;
use App\Services\Parser\ParserResponse;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Services
 * @group Unit.Services.Parser
 * @group Unit.Services.Parser.ParserResponse
 */
class ParserResponseTest extends TestCase
{
    public function test_mock_parser_driver()
    {
        $this->expectException(InvalidParserException::class);
        ParserResponse::make('', '');
    }
}
