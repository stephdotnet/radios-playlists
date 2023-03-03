<?php

namespace Tests\Fixtures\Spotify;

use Tests\Fixtures\FixturesAbstractClass;

class SpotifyPlaylistFixtures extends FixturesAbstractClass
{
    public static function getBasicPlaylist($file = 'spotify/playlists/playlist-basic-informations.json'): array
    {
        return self::getFixtureFromStorageAsArray($file);
    }

    public static function getCreatedPlaylist($file = 'spotify/playlists/playlist-creation.json'): array
    {
        return self::getFixtureFromStorageAsArray($file);
    }

    public static function getPlaylistTracksIds($file = 'spotify/playlists/playlist-tracks-ids.json'): array
    {
        return self::getFixtureFromStorageAsArray($file);
    }
}
