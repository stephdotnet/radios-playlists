<?php

namespace Tests\Feature\Http;

use App\Services\Spotify\SpotifyApiClient;
use Tests\Mocks\SpotifyApiClientMock;
use Tests\TestCase;

/**
 * @group Feature
 * @group Feature.Http
 * @group Feature.Http.SpotifyAuth
 */
class AuthControllerTest extends TestCase
{
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

        $this->get(route('spotify.callback', ['code' => 'code', 'state' => SpotifyApiClientMock::FAKE_STATE]))
            ->assertRedirect(route('index'));
    }

    public function test_spotify_callback_when_state_invalid()
    {
        $this->get(route('spotify.callback', ['code' => 'code', 'state' => SpotifyApiClientMock::FAKE_STATE]))
        ->assertForbidden();
    }
}
