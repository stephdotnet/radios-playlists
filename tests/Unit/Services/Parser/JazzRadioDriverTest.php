<?php

namespace Services\Parser;

use App\Exceptions\Services\Parser\InvalidResponseException;
use App\Facades\Parser;
use App\Services\Parser\ParserResponse;
use Illuminate\Support\Facades\Log;
use Tests\Fixtures\Parser\JazzRadioParserFixtures;
use Tests\TestCase;
use Tests\Traits\Mock\ParserMockTrait;

/**
 * @group Unit
 * @group Unit.Services
 * @group Unit.Services.Parser
 * @group Unit.Services.Parser.JazzRadioDriver
 */
class JazzRadioDriverTest extends TestCase
{
    use ParserMockTrait;

    public function setUp(): void
    {
        parent::setUp();

        Log::spy();
    }

    public function test_parser_driver()
    {
        $this->mockHttpRequest(JazzRadioParserFixtures::getProg());

        $response = Parser::driver('jazzRadio')->setRadio('jazzradio')->parse();

        $this->assertInstanceOf(ParserResponse::class, $response);
        $this->assertEquals('Boogie down', $response->song);
        $this->assertEquals('AL JARREAU', $response->artist);
    }

    public function test_parser_no_song_playing()
    {
        $this->mockHttpRequest(JazzRadioParserFixtures::getProg('parser/jazzRadio/prog_no_song.xml'));

        $this->expectException(InvalidResponseException::class);
        Parser::driver('jazzRadio')->setRadio('jazzradio')->parse();
    }
}
