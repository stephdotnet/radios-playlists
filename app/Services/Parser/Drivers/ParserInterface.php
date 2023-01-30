<?php

namespace App\Services\Parser\Drivers;

use App\Services\Parser\ParserResponse;

interface ParserInterface
{
    public function parse(): ParserResponse;
}
