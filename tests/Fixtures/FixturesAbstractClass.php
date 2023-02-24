<?php

namespace Tests\Fixtures;

use Illuminate\Support\Facades\Storage;

abstract class FixturesAbstractClass
{
    protected static function getFixtureFromStorage(string $file): string
    {
        return Storage::disk('tests')->get($file);
    }

    protected static function getFixtureFromStorageAsArray(string $file): array
    {
        return json_decode(self::getFixtureFromStorage($file), true);
    }
}
