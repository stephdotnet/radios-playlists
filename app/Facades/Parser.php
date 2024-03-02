<?php

namespace App\Facades;

use App\Services\Parser\ParserService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static getDriverForRadio(array|bool|string|null $argument)
 * @method static driver($getDriverForRadio)
 */
class Parser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ParserService::class;
    }
}
