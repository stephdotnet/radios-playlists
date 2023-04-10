<?php

namespace App\Services\Spotify;

use Exception;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiClient
{
    const ACCESS_TOKEN_SESSION_KEY = 'access_token';

    const REFRESH_TOKEN_SESSION_KEY = 'refresh_token';

    const STATE_SESSION_KEY = 'state';

    const SCOPES = [
        'playlist-modify-private',
        'playlist-modify-public',
        'playlist-read-private',
        'user-read-private',
    ];

    public function __construct(protected Session $session, protected SpotifyWebAPI $client)
    {
    }

    public function requestAccessToken(string $code)
    {
        $this->session->requestAccessToken($code);

        session()->put(self::ACCESS_TOKEN_SESSION_KEY, $this->session->getAccessToken());
        session()->put(self::REFRESH_TOKEN_SESSION_KEY, $this->session->getRefreshToken());

        return $this;
    }

    public function isAuthenticated()
    {
        return session()->has(self::ACCESS_TOKEN_SESSION_KEY);
    }

    public function isAdmin()
    {
        try {
            $adminId = config('services.spotify.admin_id');

            return $adminId
                && $this->getAuthenticatedClient()
                    ->setOptions(['return_assoc' => false])
                    ->me()->id === $adminId;
        } catch (Exception) {
            return false;
        }
    }

    public function getAuthenticatedClient(): SpotifyWebAPI
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
            'scope' => self::SCOPES,
            'state' => $state,
        ]);
    }

    public function revokeAuthenticatedClientToken()
    {
        session()->forget(self::ACCESS_TOKEN_SESSION_KEY);
        session()->forget(self::REFRESH_TOKEN_SESSION_KEY);
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
