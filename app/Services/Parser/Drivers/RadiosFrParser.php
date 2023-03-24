<?php

namespace App\Services\Parser\Drivers;

use App\Services\Parser\Exceptions\InvalidResponseException;
use App\Services\Parser\ParserResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class RadiosFrParser implements ParserInterface
{
    protected string $radio;

    public function setRadio(string $radio): self
    {
        $this->radio = $radio;

        return $this;
    }

    public function parse(): ParserResponse
    {
        if (empty($this->radio)) {
            throw new \Exception('Radio is not set');
        }

        $response = $this->getClient()
            ->get(self::getUrl('now-playing'), [
                'stationIds' => $this->radio,
            ])
            ->throw()
            ->json();

        if ($titre = Arr::get($response, '0.title')) {
            $data = $this->extractData($titre);

            return new ParserResponse(Arr::get($data, 'song'), Arr::get($data, 'artist'));
        }

        throw new InvalidResponseException("Expected title, got: $titre");
    }

    protected function extractData(string $titre): array
    {
        $data = explode('-', $titre);

        return [
            'artist' => trim($data[0]),
            'song' => trim($data[1]),
        ];
    }

    public static function getUrl(string $endpoint = ''): string
    {
        return "https://prod.radio-api.net/stations/$endpoint";
    }

    protected function getClient(): PendingRequest
    {
        return Http::withOptions([
            'verify' => false,
        ]);
    }
}
