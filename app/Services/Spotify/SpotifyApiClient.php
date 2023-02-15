<?php

namespace App\Services\Spotify;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiClient
{
    public function __construct(protected Session $session, protected SpotifyWebAPI $client)
    {
    }

    public function requestAccessToken(string $code) {
        $this->session->requestAccessToken($code);
    
        session()->put('access_token', $this->session->getAccessToken());
        session()->put('refresh_token', $this->session->getRefreshToken());        
    }

    public function isAuthenticated() {
        return session()->has('access_token');
    }
    
    public function getAuthenticatedClient() {
        return $this->client->setAccessToken(session()->get('access_token'));
    }

    public function getClientCredentialsClient(): SpotifyWebAPI
    {
        $this->client->setAccessToken($this->getClientCredentialsToken());

        return $this->client;
    }

    public function getAuthorizeUrl() {
        $state = $this->session->generateState();
    
        session()->put('state', $state);
    
        return $this->session->getAuthorizeUrl([
            'scope' => [
                'playlist-read-private',
                'user-read-private',
            ],
            'state' => $state,
        ]);
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
