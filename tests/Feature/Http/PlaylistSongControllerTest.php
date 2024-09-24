<?php

namespace Tests\Feature\Http;

use App\Models\Playlist;
use App\Models\Song;
use App\Models\SpotifyPlaylist;
use App\Services\Spotify\SpotifyApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\Fixtures\Spotify\SpotifyPlaylistFixtures;
use Tests\Mocks\SpotifyApiClientMock;
use Tests\TestCase;

class PlaylistSongControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_songs()
    {
        $playlist = Playlist::factory()->withSongs(10)->create();

        $this->getJson(route('playlist.songs', ['playlist' => $playlist->id]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'artists',
                        'spotify_url',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_delete_song()
    {
        // Mocks
        $this->partialMock(SpotifyApiClient::class, function ($mock) {
            $mock
                ->shouldReceive('isAdmin')->andReturn(true);
        });

        $basicPlaylistInformations = SpotifyPlaylistFixtures::getBasicPlaylist();
        $spotifyApiClientMock      = SpotifyApiClientMock::make()
            ->makeRequestAccessTokenSessionMock()
            ->makeSpotifyWebApiMock(function ($mock) use ($basicPlaylistInformations) {
                $mock->shouldReceive('deletePlaylistTracks')
                    ->andReturn('123456')
                    ->shouldReceive('getPlaylist')
                    ->andReturn($basicPlaylistInformations);
            })
            ->getMock();

        $spotifyApiClientMock = Mockery::mock($spotifyApiClientMock, function ($mock) {
            $mock->shouldReceive('isAdmin')->andReturn(true);
        })->makePartial();

        $this->instance(SpotifyApiClient::class, $spotifyApiClientMock);

        // Seed
        $song     = Song::factory()->create();
        $playlist = Playlist::factory()
            ->hasAttached($song)
            ->create();
        $spotifyPlaylist = SpotifyPlaylist::factory()
            ->playlist($playlist)
            ->hasAttached($song)
            ->create();

        $this->assertCount(1, $playlist->songs);

        // Test endpoint
        $this
            ->withSession([
                SpotifyApiClient::ACCESS_TOKEN_SESSION_KEY => SpotifyApiClientMock::FAKE_ACCESS_TOKEN
            ])
            ->deleteJson(
                route('playlist.songs.delete', [
                    'playlist' => $playlist->id,
                    'song'     => $song->id,
                ]),
            )
            ->assertNoContent();

        $this->assertCount(0, $playlist->refresh()->songs);
        $this->assertCount(1, $playlist->forbiddenSongs);
        $this->assertCount(0, $spotifyPlaylist->refresh()->songs);
        $this->assertEquals($basicPlaylistInformations['snapshot_id'], $playlist->spotifyPlaylist->snapshot_id);
    }

    public function test_term_search()
    {
        $song = Song::factory([
            'name' => 'song name',
            'data' => [
                'artists' => [
                    ['name' => 'artist one'],
                    ['name' => 'artist two'],
                ],
            ],
        ])
            ->create();

        $playlist = Playlist::factory()
            ->hasAttached($song)
            ->create();

        $this->getJson(
            route('playlist.songs', ['playlist' => $playlist->id, 'filter' => [
                'term' => 'nomatch',
            ]]),
        )
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->getJson(
            route('playlist.songs', ['playlist' => $playlist->id, 'filter' => [
                'term' => 'song',
            ]]),
        )
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
