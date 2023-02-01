<?php

namespace App\Features\Commands;

use App\Facades\Parser;
use Tests\TestCase;

/**
 * Feature
 * Feature.Parse
 * Feature.Parse.One
 */
class ParseOneCommandTest extends TestCase
{
    public function test_parse_one_command()
    {
        $this->markTestSkipped('Not implemented yet');

        $radiosFrParserMock = $this->partialMock(RadiosFrParser::class, function ($mock) {
            $mock->shouldReceive('parse')
                ->once();
        });

        // $radiosFrParserMock = $this->spy(RadiosFrParser::class);

        $facadeMock = Parser::partialMock();

        $facadeMock
            ->shouldReceive('driver')
            ->with('radiosFr')
            ->once()
            ->andReturn($radiosFrParserMock);

        $facadeMock->shouldReceive('parse');

        $this->artisan('parse:one', ['radio' => 'swissjazz'])
            ->assertExitCode(0);

        // $radiosFrParserMock->shouldHaveReceived('parse')->once();
    }
}
