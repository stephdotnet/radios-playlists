<?php

namespace Tests\Unit\Services\Parser;

use App\Services\Parser\Drivers\MockParser;
use App\Services\Parser\Drivers\RadiosFrParser;
use App\Services\Parser\Drivers\RtlParser;
use App\Services\Parser\ParserService;
use Tests\TestCase;

class ParserServiceTest extends TestCase
{
    public function test_default_driver()
    {
        $paserDriver = app(ParserService::class)->driver();

        $this->assertEquals(MockParser::class, $paserDriver::class);
    }

    public function test_create_mock_driver()
    {
        $paserDriver = app(ParserService::class)->driver('mock');

        $this->assertEquals(MockParser::class, $paserDriver::class);
    }

    public function test_create_radios_fr_driver()
    {
        $paserDriver = app(ParserService::class)->driver('radiosFr');

        $this->assertEquals(RadiosFrParser::class, $paserDriver::class);
    }

    public function test_create_rtl_driver()
    {
        $paserDriver = app(ParserService::class)->driver('rtl');

        $this->assertEquals(RtlParser::class, $paserDriver::class);
    }
}
