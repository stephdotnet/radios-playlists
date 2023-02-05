<?php

namespace App\Services\Spotify;

use App\Services\Parser\ParserResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\PendingRequest;

class SpotifyApi
{
    public function getMatchingSong(ParserResponse $parserResponse): array
    {
        $queryParameters = "artist:{$parserResponse->artist} track:{$parserResponse->song}";

        $response = $this->sendRequest('get', 'search', [
            'q' => $queryParameters,
            'type' => 'track',
            'limit' => 1,
        ]);

        return Arr::get($response, 'tracks.items.0');
    }

    public function sendRequest(string $method, string $endpoint, array $params = []): array
    {
        $response = $this->getClient()
            ->withToken($this->getToken())
            ->$method($endpoint, $params)
            ->throw()
            ->json();

        return $response;
    }

    protected function getToken(): string
    {
        return Http::withBasicAuth(config('services.spotify.client_id'), config('services.spotify.client_secret'))
            ->asForm()
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
            ])
            ->throw()
            ->json('access_token');
    }

    protected function getClient(): PendingRequest
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->baseUrl('https://api.spotify.com/v1');
    }
}
