<?php

namespace App\Exceptions\Services\Parser;

use App\Exceptions\Traits\WithoutTrace;
use Exception;

class InvalidResponseException extends Exception
{
    use WithoutTrace;
}
