<?php

namespace Tests\Traits\Mock;

use App\Facades\Parser;
use App\Services\Parser\Drivers\RadiosFrParser;
use App\Services\Parser\ParserResponse;

trait ParserMockTrait
{
    protected function mockParserDriver($driverMock = null)
    {
        $facadeMock = Parser::partialMock();

        $facadeMock
            ->shouldReceive('driver')
            ->with('radiosFr')
            ->andReturn($driverMock ?: $this->mockRadiosFrDriver());

        return $facadeMock;
    }

    protected function mockRadiosFrDriver(ParserResponse $response = null)
    {
        return $this->partialMock(RadiosFrParser::class, function ($mock) use ($response) {
            $mock->shouldReceive('parse')
                ->andReturn($response ?? new ParserResponse('song', 'artist'));
        });
    }
}
