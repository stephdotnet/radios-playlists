<?php

namespace App\Services\Parser\Drivers;

use App\Exceptions\Services\Parser\InvalidResponseException;
use App\Services\Parser\ParserAbstractClass;
use App\Services\Parser\ParserResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
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
        'J5 WEBRADIO JAZZ MANOUCHE',
    ];

    const RADIO_TO_FILE = [
        'jazzradio'          => 'prog.xml',
        'jazzradio_manouche' => 'prog7.xml'
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
            ->get(self::getUrl($this->getFileName()))
            ->throw()
            ->body();

        $reader   = XmlReader::fromString($response);
        $value    = $reader->value('prog.morceau.0')->sole();
        $category = Arr::get($value, 'categorie');

        if (! empty($category) && in_array($category, self::ALLOWED_CATEGORY_VALUES)) {
            $data = $this->extractData($value);

            return ParserResponse::make(Arr::get($data, 'song'), Arr::get($data, 'artist'));
        }

        Log::error("Invalid response parsing {$this->radio}", ['payload' => $value]);
        throw new InvalidResponseException("InvalidResponseException parsing {$this->radio}");
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    */

    protected function extractData(array $value): array
    {
        return [
            'artist' => Arr::get($value, 'chanteur'),
            'song'   => Arr::get($value, 'chanson'),
        ];
    }

    protected static function getUrl(string $filename): string
    {
        return 'https://www.jazzradio.fr/winradio/' . $filename;
    }

    protected function getFileName(): string
    {
        return Arr::get(self::RADIO_TO_FILE, $this->radio);
    }
}
