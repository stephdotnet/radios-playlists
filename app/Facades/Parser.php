<?php

namespace App\Facades;

use App\Services\Parser\ParserService;
use Illuminate\Support\Facades\Facade;

class Parser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ParserService::class;
    }
}
