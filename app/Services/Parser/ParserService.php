<?php

namespace App\Services\Parser;

use App\Services\Parser\Drivers\MockParser;
use App\Services\Parser\Drivers\RadiosFrParser;
use Illuminate\Support\Manager;

class ParserService extends Manager
{
    public function getDefaultDriver()
    {
        return 'mock';
    }

    public function createMockDriver(): MockParser
    {
        return new MockParser();
    }

    public function createRadiosFrDriver(): RadiosFrParser
    {
        return new RadiosFrParser();
    }
}
