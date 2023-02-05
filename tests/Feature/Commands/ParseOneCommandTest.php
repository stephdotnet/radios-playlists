<?php

namespace App\Features\Commands;

use App\Facades\Parser;
use App\Services\Parser\Drivers\RadiosFrParser;
use App\Services\Parser\ParserResponse;
use App\Services\Spotify\SpotifyApi;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group Feature
 * @group Feature.Parse
 * @group Feature.Parse.One
 */
class ParseOneCommandTest extends TestCase
{
    public function test_parse_one_command()
    {
        $radiosFrParserMock = $this->partialMock(RadiosFrParser::class, function ($mock) {
            $mock->shouldReceive('parse')
                ->andReturn(new ParserResponse('song', 'artist'))
                ->once();
        });

        $facadeMock = Parser::partialMock();

        $facadeMock
            ->shouldReceive('driver')
            ->with('radiosFr')
            ->once()
            ->andReturn($radiosFrParserMock);

        $facadeMock->shouldReceive('parse');

        $this->partialMock(SpotifyApi::class, function ($mock) {
            $fixture = json_decode(Storage::disk('tests')->get('matching-song.json'), true);
            $mock->shouldReceive('getMatchingSong')
                ->andReturn(Arr::get($fixture, 'tracks.items.0'))
                ->once();
        });

        $this->artisan('parse:one', ['radio' => 'swissjazz'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('songs', ['spotify_id' => '09oOhscdjOI4JzxgP9t0x3']);
    }
}
