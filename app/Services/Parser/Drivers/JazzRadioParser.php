<?php

namespace App\Services\Parser\Drivers;

use App\Exceptions\Services\Parser\InvalidResponseException;
use App\Services\Parser\ParserResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Saloon\XmlWrangler\Exceptions\MissingNodeException;
use Saloon\XmlWrangler\Exceptions\MultipleNodesFoundException;
use Saloon\XmlWrangler\Exceptions\QueryAlreadyReadException;
use Saloon\XmlWrangler\Exceptions\XmlReaderException;
use Saloon\XmlWrangler\XmlReader;
use Throwable;

class JazzRadioParser extends ParserAbstractClass
{
    const ALLOWED_CATEGORY_VALUES = [
        'MUSIC',
    ];

    /**
     * @throws MissingNodeException
     * @throws QueryAlreadyReadException
     * @throws MultipleNodesFoundException
     * @throws InvalidResponseException
     * @throws XmlReaderException
     * @throws RequestException
     * @throws Throwable
     */
    public function parse(): ParserResponse
    {
        $response = $this->getClient()
            ->get(self::getUrl())
            ->throw()
            ->body();

        $reader = XmlReader::fromString($response);
        $value = $reader->value('prog.morceau.0')->sole();
        $category = Arr::get($value, 'categorie');

        if (! empty($category) && in_array($category, self::ALLOWED_CATEGORY_VALUES)) {
            $data = $this->extractData($value);

            return new ParserResponse(Arr::get($data, 'song'), Arr::get($data, 'artist'));
        }

        Log::error('Invalid response parsing {$this->radio}', ['payload' => $value]);
        throw new InvalidResponseException("InvalidResponseException parsing {$this->radio}");
    }

    protected function extractData(array $value): array
    {
        return [
            'artist' => Arr::get($value, 'chanteur'),
            'song' => Arr::get($value, 'chanson'),
        ];
    }

    public static function getUrl(): string
    {
        return 'https://www.jazzradio.fr/winradio/prog.xml';
    }

    protected function getClient(): PendingRequest
    {
        return Http::withOptions([
            'verify' => false,
        ]);
    }
}
