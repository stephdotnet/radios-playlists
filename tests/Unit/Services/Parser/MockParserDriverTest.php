<?php

namespace Tests\Unit\Services\Parser;

use App\Facades\Parser;
use App\Services\Parser\ParserResponse;
use Tests\TestCase;

class MockParserDriverTest extends TestCase
{
    public function test_mock_parser_driver()
    {
        $response = Parser::driver('mock')
            ->parse();

        $this->assertEquals(ParserResponse::class, $response::class);
    }
}
