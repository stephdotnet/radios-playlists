<?php

namespace Tests\Traits\Mock;

use App\Facades\Parser;
use App\Services\Parser\Drivers\RadiosFrParser;
use App\Services\Parser\ParserResponse;
use Illuminate\Support\Facades\Http;

trait ParserMockTrait
{
    protected function mockParserDriver($driverMock = null): \Mockery\MockInterface
    {
        $facadeMock = Parser::partialMock();

        $facadeMock
            ->shouldReceive('driver')
            ->andReturn($driverMock ?: $this->mockRadiosFrDriver());

        return $facadeMock;
    }

    protected function mockRadiosFrDriver(?ParserResponse $response = null): \Mockery\MockInterface
    {
        return $this->partialMock(RadiosFrParser::class, function ($mock) use ($response) {
            $mock->shouldReceive('parse')
                ->andReturn($response ?? new ParserResponse('song', 'artist'));
        });
    }

    protected function mockHttpRequest(...$response): void
    {
        Http::fake([
            '*' => Http::response(...$response),
        ]);
    }
}
