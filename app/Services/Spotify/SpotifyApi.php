<?php

namespace App\Services\Spotify;

use App\Services\Parser\ParserResponse;
use Illuminate\Support\Arr;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApi
{
    public function getMatchingSong(ParserResponse $parserResponse): array
    {
        $query = "artist:{$parserResponse->artist} track:{$parserResponse->song}";
        $response = $this->getClientCredentialsClient()
            ->search($query, 'track', ['limit' => 1]);

        return Arr::get($response, 'tracks.items.0', []);
    }

    protected function getAuthenticatedClient(): SpotifyWebAPI
    {
        return app(SpotifyApiClient::class)
            ->getAuthenticatedClient()
            ->setOptions(['return_assoc' => true]);
    }    
    
    protected function getClientCredentialsClient(): SpotifyWebAPI
    {
        return app(SpotifyApiClient::class)
            ->getClientCredentialsClient()
            ->setOptions(['return_assoc' => true]);
    }
}
