<?php

namespace App\Features\Commands;

use App\Services\Spotify\SpotifyApi;
use Illuminate\Support\Facades\Log;
use Tests\Fixtures\Spotify\SpotifySearchFixtures;
use Tests\TestCase;
use Tests\Traits\Mock\ParserMockTrait;
use Tests\Traits\Mock\SpotifyApiMockTrait;

/**
 * @group Feature
 * @group Feature.Parse
 * @group Feature.Parse.One
 */
class ParseOneCommandTest extends TestCase
{
    use ParserMockTrait;
    use SpotifyApiMockTrait;

    public function test_parse_one_command()
    {
        Log::spy();

        $this->mockParserDriver()->shouldReceive('parse');

        $this->partialMock(SpotifyApi::class, function ($mock) {
            $this->mockGetMatchingSong($mock);
        });

        $this->artisan('parse:one', ['radio' => 'mock'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('songs', ['spotify_id' => '09oOhscdjOI4JzxgP9t0x3']);
    }

    public function test_parse_one_command_fails_if_unauthorized_parameter()
    {
        $this->artisan('parse:one', ['radio' => 'unauthorized'])
            ->assertExitCode(1);
    }

    public function test_parse_one_command_when_no_matching_song()
    {
        Log::spy();

        $this->mockParserDriver()->shouldReceive('parse');

        $this->partialMock(SpotifyApi::class, function ($mock) {
            $this->mockGetMatchingSong($mock, SpotifySearchFixtures::getMatchingSong('spotify/search/no-matching-song.json'));
        });

        $this->artisan('parse:one', ['radio' => 'mock'])
            ->assertExitCode(0);

        $this->assertDatabaseCount('songs', 0);
    }
}
