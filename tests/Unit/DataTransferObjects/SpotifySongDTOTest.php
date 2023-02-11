<?php

namespace Test\Unit\DataTransferObjects;

use App\DataTransferObjects\SpotifySongDTO;
use Tests\Fixtures\Spotify\SpotifySongFixtures;
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
        $transformedData = SpotifySongDTO::toModel(SpotifySongFixtures::getSong());

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
}
