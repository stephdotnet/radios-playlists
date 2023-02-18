<?php

namespace App\Services\Spotify;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiClient
{
    const ACCESS_TOKEN_SESSION_KEY = 'access_token';

    const REFRESH_TOKEN_SESSION_KEY = 'refresh_token';

    const STATE_SESSION_KEY = 'state';

    public function __construct(protected Session $session, protected SpotifyWebAPI $client)
    {
    }

    public function requestAccessToken(string $code)
    {
        $this->session->requestAccessToken($code);

        session()->put(self::ACCESS_TOKEN_SESSION_KEY, $this->session->getAccessToken());
        session()->put(self::REFRESH_TOKEN_SESSION_KEY, $this->session->getRefreshToken());
    }

    public function isAuthenticated()
    {
        return session()->has(self::ACCESS_TOKEN_SESSION_KEY);
    }

    public function getAuthenticatedClient()
    {
        return $this->client->setAccessToken(session()->get(self::ACCESS_TOKEN_SESSION_KEY));
    }

    public function getClientCredentialsClient(): SpotifyWebAPI
    {
        $this->client->setAccessToken($this->getClientCredentialsToken());

        return $this->client;
    }

    public function getAuthorizeUrl()
    {
        $state = $this->session->generateState();

        session()->put(self::STATE_SESSION_KEY, $state);

        return $this->session->getAuthorizeUrl([
            'scope' => [
                'playlist-read-private',
                'user-read-private',
            ],
            'state' => $state,
        ]);
    }

    public function revokeAuthenticatedClientToken()
    {
        session()->forget(self::ACCESS_TOKEN_SESSION_KEY);
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    */

    protected function getClientCredentialsToken()
    {
        $this->session->requestCredentialsToken();

        return $this->session->getAccessToken();
    }
}
