<?php

namespace Tests\Fixtures;

use Illuminate\Support\Facades\Storage;

class SpotifySearchFixtures
{
    public static function getMatchingSong($file = 'spotify/search/matching-song.json'): array
    {
        return json_decode(Storage::disk('tests')->get($file), true);
    }
}
