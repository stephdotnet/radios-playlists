<?php

namespace Tests\Unit\Services\Spotify;

use App\Exceptions\Services\Spotify\SpotifyAuthException;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\SpotifyPlaylist;
use App\Services\Spotify\SpotifyApiClient;
use App\Services\Spotify\SpotifyApiSync;
use Exception;
use Illuminate\Support\Facades\Log;
use Tests\Fixtures\Spotify\SpotifyPlaylistFixtures;
use Tests\Fixtures\Spotify\SpotifyUserFixtures;
use Tests\Mocks\SpotifyApiClientMock;
use Tests\TestCase;

class SpotifyApiSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            SpotifyApiClient::ACCESS_TOKEN_SESSION_KEY => SpotifyApiClientMock::FAKE_ACCESS_TOKEN
        ]);
    }

    public function test_set_playlist()
    {
        $response = app(SpotifyApiSync::class)->setPlaylist(Playlist::factory()->make());

        $this->assertEquals(SpotifyApiSync::class, get_class($response));
    }

    public function test_sync_checks_requisites()
    {
        $this->expectException(Exception::class);
        app(SpotifyApiSync::class)->sync();

        $this->expectException(SpotifyAuthException::class);
        app(SpotifyApiSync::class)
            ->setPlaylist(Playlist::factory()->make())
            ->sync();
    }

    public function test_sync_spotify_playlist_created_if_doesnt_exist()
    {
        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock
                    ->shouldReceive('me')
                    ->andReturn(SpotifyUserFixtures::getMe())
                    ->shouldReceive('createPlaylist')
                    ->andReturn(SpotifyPlaylistFixtures::getCreatedPlaylist())
                    ->shouldReceive('getPlaylist')
                    ->andReturn(SpotifyPlaylistFixtures::getBasicPlaylist())
                    ->shouldReceive('getPlaylistTracks')
                    ->times(2)
                    ->andReturn(SpotifyPlaylistFixtures::getPlaylistTracksIds())
                    ->shouldReceive('addPlaylistTracks')
                    ->once()
                    ->with('123456', ['AAA']);
            })
            ->bind();

        $songToSync = Song::factory([
            'spotify_id' => 'AAA',
        ])->create();

        $playlist = Playlist::factory()
            ->hasAttached($songToSync)
            ->create();

        Log::spy();

        $response = app(SpotifyApiSync::class)
            ->setPlaylist($playlist)
            ->sync();

        $spotifyPlaylist = SpotifyPlaylist::where('playlist_id', $playlist->id)->first();

        $this->assertDatabaseHas(SpotifyPlaylist::class, [
            'playlist_id' => $playlist->id,
        ]);

        $this->assertEquals($playlist->id, data_get($response, 'spotifyPlaylist.playlist_id'));
        $this->assertEquals($spotifyPlaylist->snapshot_id, '345');
        $this->assertEquals($spotifyPlaylist->songs->first()->spotify_id, 'AAA');
    }

    public function test_playlist_creation_issue_throws_exception()
    {
        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock
                    ->shouldReceive('me')
                    ->andReturn(SpotifyUserFixtures::getMe())
                    ->shouldReceive('createPlaylist')
                    ->andReturn([]);
            })
            ->bind();

        $songToSync = Song::factory([
            'spotify_id' => '280OLJ1XsWyuEFTiiDg54q',
        ])->create();

        $playlist = Playlist::factory()
            ->hasAttached($songToSync)
            ->create();

        $this->expectException(Exception::class);

        app(SpotifyApiSync::class)
            ->setPlaylist($playlist)
            ->sync();
    }

    public function test_requisite_fails_if_cant_retrieve_user()
    {
        SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock
                    ->shouldReceive('me')
                    ->andReturn([]);
            })
            ->bind();

        $this->expectException(SpotifyAuthException::class);

        app(SpotifyApiSync::class)
            ->setPlaylist(Playlist::factory()->make())
            ->sync();
    }
}
