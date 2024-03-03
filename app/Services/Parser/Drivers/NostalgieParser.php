<?php

namespace App\Services\Parser\Drivers;

use App\Exceptions\Services\Parser\InvalidParserException;
use App\Services\Parser\ParserAbstractClass;
use App\Services\Parser\ParserResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;

class NostalgieParser extends ParserAbstractClass
{
    /**
     * @throws RequestException
     * @throws InvalidParserException
     */
    public function parse(): ParserResponse
    {
        $response = $this->getDriverData();
        $data     = $this->extractData(Arr::get($response, '0'));

        return ParserResponse::make(Arr::get($data, 'song'), Arr::get($data, 'artist'));
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
