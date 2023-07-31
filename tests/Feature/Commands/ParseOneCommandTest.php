<?php

namespace App\Features\Commands;

use App\Models\Playlist;
use App\Models\Song;
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

    protected function setUp(): void
    {
        parent::setUp();

        Log::spy();
        $this->mockParserDriver()->shouldReceive('parse');
    }

    public function test_parse_one_command_succeeds()
    {
        $playlist = Playlist::factory([
            'slug' => 'mock',
        ])->create();

        $this->partialMock(SpotifyApi::class, function ($mock) {
            $this->mockGetMatchingSong($mock);
        });

        $this->artisan('parse:one', ['radio' => 'mock'])
            ->assertExitCode(0);

        $this->assertDatabaseHas('songs', ['spotify_id' => '09oOhscdjOI4JzxgP9t0x3']);
        $this->assertCount(1, $playlist->refresh()->songs);
    }

    public function test_parse_one_command_fails_if_unauthorized_parameter()
    {
        $this->artisan('parse:one', ['radio' => 'unauthorized'])
            ->assertExitCode(1);
    }

    public function test_parse_one_command_when_no_matching_song()
    {
        $this->partialMock(SpotifyApi::class, function ($mock) {
            $this->mockGetMatchingSong($mock, SpotifySearchFixtures::getMatchingSong('spotify/search/no-matching-song.json'));
        });

        $this->artisan('parse:one', ['radio' => 'mock'])
            ->assertExitCode(0);

        $this->assertDatabaseCount('songs', 0);
    }

    public function test_forbidden_song_not_added()
    {
        $song = Song::factory([
            'spotify_id' => '09oOhscdjOI4JzxgP9t0x3',
        ])->create();

        $playlist = Playlist::factory([
            'slug' => 'mock',
        ])->hasForbiddenSongAttached($song)->create();

        $this->partialMock(SpotifyApi::class, function ($mock) {
            $this->mockGetMatchingSong($mock);
        });

        $this->artisan('parse:one', ['radio' => 'mock'])
            ->assertExitCode(0);

        $this->assertDatabaseMissing('playlist_song', [
            'song_id' => $song->id,
            'playlist_id' => $playlist->id,
        ]);
    }

    public function test_song_already_exist()
    {
        Song::factory([
            'spotify_id' => '09oOhscdjOI4JzxgP9t0x3',
        ])->create();

        Playlist::factory([
            'slug' => 'mock',
        ])->create();

        $this->partialMock(SpotifyApi::class, function ($mock) {
            $this->mockGetMatchingSong($mock);
        });

        $this->artisan('parse:one', ['radio' => 'mock'])
            ->assertExitCode(0);

        $this->assertDatabaseCount('songs', 1);
    }
}
