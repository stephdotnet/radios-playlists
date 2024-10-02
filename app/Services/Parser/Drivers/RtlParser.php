<?php

namespace App\Services\Parser\Drivers;

use App\Exceptions\Services\Parser\InvalidParserException;
use App\Exceptions\Services\Parser\InvalidResponseException;
use App\Services\Parser\ParserAbstractClass;
use App\Services\Parser\ParserResponse;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class RtlParser extends ParserAbstractClass
{
    /**
     * @throws RequestException
     * @throws InvalidParserException
     * @throws ConnectionException
     */
    public function parse(): ParserResponse
    {
        $response = $this->getDriverData();
        $data     = $this->extractData($response);

        return ParserResponse::make(Arr::get($data, 'song'), Arr::get($data, 'artist'));
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    */

    /**
     * @throws ConnectionException
     * @throws InvalidResponseException
     * @throws RequestException
     */
    protected function getDriverData(): ?array
    {
        $data = $this->getClient()
            ->get(self::getUrl())
            ->throw()
            ->body();

        Storage::disk('local')->put('output.html', $data);

        $doc = new DOMDocument;
        $doc->loadHTML($data, LIBXML_NOERROR);
        $xpath = new DOMXpath($doc);

        $elements = $xpath->query("//div[@class='cards-container']/div");

        $json = $elements?->item(0)?->getAttribute('data-qect-info');

        $data = json_decode($json, true) ?? [];

        if (! $data) {
            throw new InvalidResponseException("InvalidResponseException parsing {$this->radio}. Expected title, got: $json");
        }

        return $data;
    }

    protected function extractData(array $value): array
    {
        return [
            'artist' => Arr::get($value, 'singer'),
            'song'   => Arr::get($value, 'title'),
        ];
    }

    protected static function getUrl(): string
    {
        return 'https://www.rtl2.fr/quel-est-ce-titre';
    }
}
