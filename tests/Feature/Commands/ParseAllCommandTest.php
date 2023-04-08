<?php

namespace App\Features\Commands;

use App\Services\Parser\ParserResponse;
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

        $radiosFrDriverMock = $this->partialMock(RadiosFrParser::class, function ($mock) {
            $mock->shouldReceive('setRadio')
            ->with('mock')
            ->once()
            ->andReturnSelf();

            $mock->shouldReceive('parse')
                ->once()
                ->andReturn($response ?? new ParserResponse('song', 'artist'));
        });

        $this->mockParserDriver($radiosFrDriverMock);

        $this->mockSpotifyApi()
            ->shouldReceive('getMatchingSong')
            ->andReturn([]);

        $this->artisan('parse:all');
        $this->assertDatabaseCount('songs', 0);
    }
}
