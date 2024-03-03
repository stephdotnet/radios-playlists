<?php

namespace App\Exceptions\Services\Parser;

use App\Exceptions\Traits\WithoutTrace;
use Exception;

class InvalidParserException extends Exception
{
    use WithoutTrace;
}
