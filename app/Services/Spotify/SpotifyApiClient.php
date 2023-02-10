<?php

namespace App\Services\Spotify;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiClient
{
    public function __construct(protected Session $session, protected SpotifyWebAPI $client)
    {
    }

    public function getClientCredentialsClient(): SpotifyWebAPI
    {
        $this->client->setAccessToken($this->getClientCredentialsToken());

        return $this->client;
    }

    /*
    |--------------------------------------------------------------------------
    | Protected Methods
    |--------------------------------------------------------------------------
    */

    protected function getClientCredentialsToken()
    {
        $this->session->requestCredentialsToken();

        return $this->session->getAccessToken();
    }
}
