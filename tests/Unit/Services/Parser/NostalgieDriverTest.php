<?php

namespace Services\Parser;

use App\Facades\Parser;
use App\Services\Parser\ParserResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Tests\Fixtures\Parser\NostalgieParserFixtures;
use Tests\TestCase;
use Tests\Traits\Mock\ParserMockTrait;

/**
 * @group Unit
 * @group Unit.Services
 * @group Unit.Services.Parser
 * @group Unit.Services.Parser.NostalgieDriver
 */
class NostalgieDriverTest extends TestCase
{
    use ParserMockTrait;

    public function setUp(): void
    {
        parent::setUp();

        Log::spy();
    }

    public function test_parser_driver()
    {
        $this->mockHttpRequest(NostalgieParserFixtures::getHistory());

        $response = Parser::driver('nostalgie')->setRadio('nostalgie')->parse();

        $this->assertInstanceOf(ParserResponse::class, $response);
        $this->assertEquals('LE PHARE', $response->song);
        $this->assertEquals('ETIENNE DAHO', $response->artist);
    }

    public function test_parser_no_response()
    {
        $this->mockHttpRequest('', 403);

        $this->expectException(RequestException::class);
        Parser::driver('nostalgie')->setRadio('nostalgie')->parse();
    }
}
