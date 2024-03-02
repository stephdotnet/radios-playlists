<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Arr;

class SpotifySongDTO
{
    public static function toModel(array $data): array
    {
        return [
            'spotify_id' => $data['id'],
            'name'       => $data['name'],
            'data'       => Arr::only($data, [
                'album',
                'artists',
                'popularity',
                'duration_ms',
                'external_urls',
                'href',
                'uri',
            ]),
        ];
    }
}
