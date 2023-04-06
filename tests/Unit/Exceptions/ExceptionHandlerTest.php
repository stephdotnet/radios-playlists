<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\Services\Parser\InvalidResponseException;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.Exceptions
 * @group Unit.Exceptions.Handler
 */
class ExceptionHandlerTest extends TestCase
{
    /**
     * @covers \App\Exceptions\Handler::register
     */
    public function test_exception_handler()
    {
        $logSpy = Log::spy();

        $this->expectException(InvalidResponseException::class);
        throw new InvalidResponseException('test');
        $logSpy->shouldHaveReceived('error')
            ->with('test');
    }
}
