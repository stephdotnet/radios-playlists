<?php

namespace Tests\Unit\Services\Parser;

use App\Exceptions\Services\Parser\InvalidResponseException;
use App\Facades\Parser;
use App\Services\Parser\ParserResponse;
use Illuminate\Support\Facades\Log;
use Tests\Fixtures\Parser\RtlParserFixtures;
use Tests\TestCase;
use Tests\Traits\Mock\ParserMockTrait;

class RtlDriverTest extends TestCase
{
    use ParserMockTrait;

    public function setUp(): void
    {
        parent::setUp();

        Log::spy();
    }

    public function test_parser_driver()
    {
        $this->mockHttpRequest(RtlParserFixtures::getPage());

        $response = Parser::driver('rtl')->setRadio('rtl')->parse();

        $this->assertInstanceOf(ParserResponse::class, $response);
        $this->assertEquals('BEHIND BLUE EYES', $response->song);
        $this->assertEquals('LIMP BIZKIT', $response->artist);
    }

    public function test_parser_no_song_playing()
    {
        $this->mockHttpRequest(RtlParserFixtures::getPage('parser/rtl/error.html'));

        $this->expectException(InvalidResponseException::class);
        Parser::driver('rtl')->setRadio('rtl')->parse();
    }
}
