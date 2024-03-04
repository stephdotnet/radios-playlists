<?php

namespace Tests\Feature\Http;

use App\Services\Spotify\SpotifyApiClient;
use Exception;
use Tests\Fixtures\Spotify\SpotifyUserFixtures;
use Tests\Mocks\SpotifyApiClientMock;
use Tests\TestCase;

/**
 * @group Feature
 * @group Feature.Http
 * @group Feature.Http.SpotifyAuth
 */
class SpotifyAuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withSession([
            SpotifyApiClient::ACCESS_TOKEN_SESSION_KEY => SpotifyApiClientMock::FAKE_ACCESS_TOKEN
        ]);
    }

    public function test_spotify_redirect()
    {
        SpotifyApiClientMock::make()
            ->makeGetAuthorizeUrlSessionMock()
            ->bind();

        $this->get(route('spotify.redirect'))
            ->assertRedirect(SpotifyApiClientMock::FAKE_REDIRECT_URL);
    }

    public function test_spotify_callback()
    {
        SpotifyApiClientMock::make()
            ->makeFullAuthorizationCodeMock()
            ->bind();

        app(SpotifyApiClient::class)->getAuthorizeUrl();

        $this->get(route('spotify.callback', [
            'code'  => SpotifyApiClientMock::FAKE_CODE,
            'state' => SpotifyApiClientMock::FAKE_STATE,
        ]))
            ->assertRedirect(route('index'));
    }

    public function test_spotify_callback_when_state_invalid()
    {
        $this->get(route('spotify.callback', [
            'code'  => SpotifyApiClientMock::FAKE_CODE,
            'state' => SpotifyApiClientMock::FAKE_STATE,
        ]))
            ->assertForbidden();
    }

    public function test_get_me_returns_spotify_me_resource()
    {
        SpotifyApiClientMock::make()
            ->makeFullAuthorizationCodeMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock->shouldReceive('me')->once()->andReturn(SpotifyUserFixtures::getMe());
            })
            ->bind();

        $this->get(route('spotify.me'))
            ->assertJsonStructure([
                'data' => [
                    'display_name',
                ],
            ]);
    }

    public function test_get_me_returns_null_when_service_fails()
    {
        SpotifyApiClientMock::make()
            ->makeFullAuthorizationCodeMock()
            ->makeSpotifyWebApiMock(function ($mock) {
                $mock->shouldReceive('me')->once()->andThrow(new Exception('Fake exception'));
            })
            ->bind();

        $this->get(route('spotify.me'))
            ->assertJsonFragment([
                'data' => null,
            ]);
    }

    public function test_logout()
    {
        $this->spy(SpotifyApiClient::class, function ($mock) {
            $mock->shouldReceive('revokeAuthenticatedClientToken')->once();
        });

        $this->get(route('spotify.logout'))
            ->assertRedirect(route('index'));
    }
}
