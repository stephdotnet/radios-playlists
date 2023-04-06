<?php

namespace App\Exceptions\Services\Parser;

use App\Exceptions\Traits\WithoutTrace;

class InvalidResponseException extends \Exception
{
    use WithoutTrace;
}
