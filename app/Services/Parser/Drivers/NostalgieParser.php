<?php

namespace App\Services\Parser\Drivers;

use App\Exceptions\Services\Parser\InvalidResponseException;
use App\Services\Parser\ParserAbstractClass;
use App\Services\Parser\ParserResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class NostalgieParser extends ParserAbstractClass
{
    /**
     * @throws InvalidResponseException
     * @throws RequestException
     */
    public function parse(): ParserResponse
    {
        $response = $this->getDriverData();

        try {
            $data = $this->extractData(Arr::get($response, '0'));

            return new ParserResponse(Arr::get($data, 'song'), Arr::get($data, 'artist'));
        } catch (\Exception) {
            Log::error(
                "Invalid response parsing {$this->radio}",
                ['payload' => Arr::get($response, '0')],
            );

            throw new InvalidResponseException("InvalidResponseException parsing {$this->radio}");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    */

    /**
     * @throws RequestException
     */
    protected function getDriverData(): array
    {
        return $this->getClient()
            ->get(self::getUrl())
            ->throw()
            ->json();
    }

    protected function extractData(array $value): array
    {
        return [
            'artist' => Arr::get($value, 'artist'),
            'song'   => Arr::get($value, 'title'),
        ];
    }

    protected static function getUrl(): string
    {
        return 'https://www.nostalgie.fr/api/chansons-diffusees';
    }
}
