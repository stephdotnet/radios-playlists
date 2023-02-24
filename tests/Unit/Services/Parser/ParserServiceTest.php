<?php

namespace Tests\Unit\Services\Parser;

use App\Services\Parser\Drivers\MockParser;
use App\Services\Parser\Drivers\RadiosFrParser;
use App\Services\Parser\ParserService;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Services
 * @group Unit.Services.Parser
 */
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

    public function test_create_radiosFr_driver()
    {
        $paserDriver = app(ParserService::class)->driver('radiosFr');

        $this->assertEquals(RadiosFrParser::class, $paserDriver::class);
    }
}
