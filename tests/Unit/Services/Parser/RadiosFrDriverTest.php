<?php

namespace Tests\Unit\Services\Parser;

use App\Exceptions\Services\Parser\InvalidResponseException;
use App\Facades\Parser;
use App\Services\Parser\ParserResponse;
use Exception;
use Tests\Fixtures\Parser\RadiosFrParserFixtures;
use Tests\TestCase;
use Tests\Traits\Mock\ParserMockTrait;

class RadiosFrDriverTest extends TestCase
{
    use ParserMockTrait;

    public function test_mock_parser_driver()
    {
        $this->mockHttpRequest(RadiosFrParserFixtures::getNowPlaying());

        $response = Parser::driver('radiosFr')->setRadio('mock')->parse();

        $this->assertInstanceOf(ParserResponse::class, $response);
        $this->assertEquals('Bliss', $response->song);
        $this->assertEquals('Muse', $response->artist);
    }

    public function test_parser_fails_if_no_radio_is_not_set()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Radio is not set');

        Parser::driver('radiosFr')->parse();
    }

    public function test_parser_fails_if_incorrect_radio_response()
    {
        $this->mockHttpRequest(RadiosFrParserFixtures::getBadResponse());

        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessageMatches('/Expected title, got: .*/');

        Parser::driver('radiosFr')->setRadio('mock')->parse();
    }
}
