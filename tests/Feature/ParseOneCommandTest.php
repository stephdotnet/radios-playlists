<?php

namespace App\DataTransferObjects;

use App\Facades\Parser;
use Tests\TestCase;

/**
 * Unit
 * Unit.DTO
 * Unit.DTO.SpotifySongDTO
 */
class ParseOneCommandTest extends TestCase
{
    public function test_that_true_is_true()
    {
        $radiosFrParserMock = $this->partialMock(RadiosFrParser::class, function ($mock) {
            $mock->shouldReceive('setRadio')
                ->with('swissjazz')
                ->once();
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

        $this->artisan('parse:one')
            ->assertExitCode(0);

        // $radiosFrParserMock->shouldHaveReceived('parse')->once();
    }
}
