<?php

namespace Tests\Fixtures;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class SpotifySongFixtures
{
    public static function getSong($file = 'spotify/search/matching-song.json'): array
    {
        $search = json_decode(Storage::disk('tests')->get($file), true);

        return Arr::get($search, 'tracks.items.0');
    }
}
