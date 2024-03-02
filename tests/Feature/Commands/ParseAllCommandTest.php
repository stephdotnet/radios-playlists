<?php

namespace App\Features\Commands;

use App\Facades\Parser;
use App\Services\Parser\Drivers\MockParser;
use App\Services\Parser\ParserResponse;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Tests\Traits\Mock\ParserMockTrait;
use Tests\Traits\Mock\SpotifyApiMockTrait;

/**
 * @group Feature
 * @group Feature.Parse
 * @group Feature.Parse.All
 */
class ParseAllCommandTest extends TestCase
{
    use ParserMockTrait;
    use SpotifyApiMockTrait;

    public function test_parse_all_command()
    {
        Log::spy();

        $mockDriverMock = $this->partialMock(MockParser::class, function ($mock) {
            $mock->shouldReceive('setRadio')
                ->with('mock')
                ->once()
                ->andReturnSelf();

            $mock->shouldReceive('parse')
                ->once()
                ->andReturn($response ?? new ParserResponse('song', 'artist'));
        });

        $this->mockParserDriver($mockDriverMock);

        $this->mockSpotifyApi()
            ->shouldReceive('getMatchingSong')
            ->andReturn([]);

        $this->artisan('parse:all');
        $this->assertDatabaseCount('songs', 0);
    }

    public function test_exception_catched()
    {
        $facadeMock = Parser::partialMock();

        $facadeMock
            ->shouldReceive('driver')
            ->with('radiosFr')
            ->andThrow(new Exception('Test exception'));

        $this->assertEquals(0, Artisan::call('parse:all'));
    }
}
