<?php

namespace App\Services\Spotify;

use App\Services\Parser\ParserResponse;
use Illuminate\Support\Arr;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApi
{
    public function __construct(protected bool $returnAssoc = true)
    {
    }

    public function setReturnAssoc(bool $returnAssoc): self
    {
        $this->returnAssoc = $returnAssoc;

        return $this;
    }

    public function getMatchingSong(ParserResponse $parserResponse): array
    {
        $query = "artist:{$parserResponse->artist} track:{$parserResponse->song}";
        $response = $this->getClientCredentialsClient()
            ->search($query, 'track', ['limit' => 1]);

        return Arr::get($response, 'tracks.items.0', []);
    }

    public function getAuthenticatedClient(): SpotifyWebAPI
    {
        return app(SpotifyApiClient::class)
            ->getAuthenticatedClient()
            ->setOptions(['return_assoc' => $this->returnAssoc]);
    }

    public function getClientCredentialsClient(): SpotifyWebAPI
    {
        return app(SpotifyApiClient::class)
            ->getClientCredentialsClient()
            ->setOptions(['return_assoc' => $this->returnAssoc]);
    }
}
