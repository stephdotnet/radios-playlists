<?php

namespace App\Services\Parser;

use App\Services\Parser\Drivers\JazzRadioParser;
use App\Services\Parser\Drivers\MockParser;
use App\Services\Parser\Drivers\NostalgieParser;
use App\Services\Parser\Drivers\RadiosFrParser;
use App\Services\Parser\Drivers\RtlParser;
use Illuminate\Support\Arr;
use Illuminate\Support\Manager;

class ParserService extends Manager
{
    public function getDefaultDriver()
    {
        return 'mock';
    }

    public function createMockDriver(): MockParser
    {
        return new MockParser;
    }

    public function createRadiosFrDriver(): RadiosFrParser
    {
        return new RadiosFrParser;
    }

    public function createJazzRadioDriver(): JazzRadioParser
    {
        return new JazzRadioParser;
    }

    public function createNostalgieDriver(): NostalgieParser
    {
        return new NostalgieParser;
    }

    public function createRtlDriver(): RtlParser
    {
        return new RtlParser;
    }

    public function getDriverForRadio(string $radio): string
    {
        return Arr::get(config('services.parser.driver'), $radio);
    }

    public static function isRadioActive(string $radio): bool
    {
        return in_array($radio, config('services.parser.radios'));
    }
}
