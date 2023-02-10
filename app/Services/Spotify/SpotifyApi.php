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

        return Arr::get($this->convertReponseToArray($response), 'tracks.items.0', []);
    }

    protected function convertReponseToArray($response): array
    {
        return json_decode(json_encode($response), true);
    }

    protected function getClientCredentialsClient(): SpotifyWebAPI
    {
        return app(SpotifyApiClient::class)->getClientCredentialsClient();
    }
}
