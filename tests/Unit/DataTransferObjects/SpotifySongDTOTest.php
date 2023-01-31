<?php

namespace Test\Unit\DataTransferObjects;

use App\DataTransferObjects\SpotifySongDTO;
use Tests\TestCase;

/**
 * @group Unit
 * @group Unit.DTO
 * @group Unit.DTO.SpotifySongDTO
 */
class SpotifySongDtoTest extends TestCase
{
    public function test_spotify_dto_has_correct_keys()
    {
        $transformedData = SpotifySongDTO::toModel($this->getSongFixture());

        $this->assertArrayHasKey('spotify_id', $transformedData);
        $this->assertArrayHasKey('name', $transformedData);
        $this->assertArrayHasKey('data', $transformedData);
        $this->assertArrayHasKey('album', $transformedData['data']);
        $this->assertArrayHasKey('artists', $transformedData['data']);
        $this->assertArrayHasKey('popularity', $transformedData['data']);
        $this->assertArrayHasKey('duration_ms', $transformedData['data']);
        $this->assertArrayHasKey('external_urls', $transformedData['data']);
        $this->assertArrayHasKey('href', $transformedData['data']);
        $this->assertArrayHasKey('uri', $transformedData['data']);
    }

    protected function getSongFixture()
    {
        return                     [
            'album' => [
                'album_type' => 'album',
                'artists' => [
                    [
                        'external_urls' => [
                            'spotify' => 'https://open.spotify.com/artist/3rxeQlsv0Sc2nyYaZ5W71T',
                        ],
                        'href' => 'https://api.spotify.com/v1/artists/3rxeQlsv0Sc2nyYaZ5W71T',
                        'id' => '3rxeQlsv0Sc2nyYaZ5W71T',
                        'name' => 'Chet Baker',
                        'type' => 'artist',
                        'uri' => 'spotify:artist:3rxeQlsv0Sc2nyYaZ5W71T',
                    ],
                ],
                'available_markets' => [
                    'AD',
                    'ZM',
                    'ZW',
                ],
                'external_urls' => [
                    'spotify' => 'https://open.spotify.com/album/5y1FKunuYa0igXa1HyI0SZ',
                ],
                'href' => 'https://api.spotify.com/v1/albums/5y1FKunuYa0igXa1HyI0SZ',
                'id' => '5y1FKunuYa0igXa1HyI0SZ',
                'images' => [
                    [
                        'height' => 640,
                        'url' => 'https://i.scdn.co/image/ab67616d0000b2733fa5768ab07f93ad0e63b946',
                        'width' => 640,
                    ],
                    [
                        'height' => 300,
                        'url' => 'https://i.scdn.co/image/ab67616d00001e023fa5768ab07f93ad0e63b946',
                        'width' => 300,
                    ],
                    [
                        'height' => 64,
                        'url' => 'https://i.scdn.co/image/ab67616d000048513fa5768ab07f93ad0e63b946',
                        'width' => 64,
                    ],
                ],
                'name' => 'Compact Jazz - Chet Baker',
                'release_date' => '1992-09-11',
                'release_date_precision' => 'day',
                'total_tracks' => 15,
                'type' => 'album',
                'uri' => 'spotify:album:5y1FKunuYa0igXa1HyI0SZ',
            ],
            'artists' => [

                [
                    'external_urls' => [
                        'spotify' => 'https://open.spotify.com/artist/3rxeQlsv0Sc2nyYaZ5W71T',
                    ],
                    'href' => 'https://api.spotify.com/v1/artists/3rxeQlsv0Sc2nyYaZ5W71T',
                    'id' => '3rxeQlsv0Sc2nyYaZ5W71T',
                    'name' => 'Chet Baker',
                    'type' => 'artist',
                    'uri' => 'spotify:artist:3rxeQlsv0Sc2nyYaZ5W71T',
                ],
            ],
            'available_markets' => [
                'AD',
                'AE',
                'ZA',
                'ZM',
                'ZW',
            ],
            'disc_number' => 1,
            'duration_ms' => 187666,
            'explicit' => false,
            'external_ids' => [
                'isrc' => 'USPR36486911',
            ],
            'external_urls' => [
                'spotify' => 'https://open.spotify.com/track/7oJ7VAlVzZwbrsumcW9PMT',
            ],
            'href' => 'https://api.spotify.com/v1/tracks/7oJ7VAlVzZwbrsumcW9PMT',
            'id' => '7oJ7VAlVzZwbrsumcW9PMT',
            'is_local' => false,
            'name' => 'Baby Breeze',
            'popularity' => 24,
            'preview_url' => 'https://p.scdn.co/mp3-preview/972a7f5ffebca95918a4a9287bf3824aa82435ef?cid=1f5731eaca9b475bbe138c1a73463bb7',
            'track_number' => 10,
            'type' => 'track',
            'uri' => 'spotify:track:7oJ7VAlVzZwbrsumcW9PMT',
        ];
    }

    protected function getFixture()
    {
        return  [
            'tracks' => [
                'href' => 'https://api.spotify.com/v1/search?query=artist%3AChet+Baker+track%3ABaby+Breeze&type=track&offset=0&limit=1',
                'items' => [
                    $this->getSongFixture(),
                ],
                'limit' => 1,
                'next' => 'https://api.spotify.com/v1/search?query=artist%3AChet+Baker+track%3ABaby+Breeze&type=track&offset=1&limit=1',
                'offset' => 0,
                'previous' => null,
                'total' => 6,
            ],
        ];
    }
}
