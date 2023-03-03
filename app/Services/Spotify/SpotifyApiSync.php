<?php

namespace App\Services\Spotify;

use App\Exceptions\SpotifyAuthException;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\SpotifyPlaylist;
use Illuminate\Support\Arr;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiSync
{
    public function __construct(
        protected SpotifyWebAPI $client,
        protected ?Playlist $playlist = null,
        protected array $user = []
    ) {
    }

    public function sync(): array
    {
        $this->checkRequisites();

        $spotifyPlaylist = $this->firstOrCreatePlaylist();

        $remoteSpotifyPlaylist = $this->client->getPlaylist($spotifyPlaylist->spotify_playlist_id, [
            'fields' => ['id', 'snapshot_id', 'tracks(total)'],
        ]);

        if ($remoteSpotifyPlaylist['snapshot_id'] != $spotifyPlaylist->snapshot_id) {
            $this->syncRemoteTracks($spotifyPlaylist, $remoteSpotifyPlaylist);
        }

        $syncedSongs = $this->addMissingSongsToRemotePlaylist($spotifyPlaylist);

        return [
            'syncedSongs' => $syncedSongs,
            'spotifyPlaylist' => $spotifyPlaylist,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public function setPlaylist(Playlist $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    */

    /**
     * @throws \Exception
     */
    protected function checkRequisites(): void
    {
        if (! $this->playlist) {
            throw new \Exception('Playlist is not set');
        }

        if (! $this->user = $this->client->me()) {
            throw new SpotifyAuthException('Unauthenticated user');
        }
    }

    protected function firstOrCreatePlaylist(): SpotifyPlaylist
    {
        $spotifyPlaylist = SpotifyPlaylist::where([
            'spotify_user_id' => data_get($this->user, 'id'),
            'playlist_id' => data_get($this->playlist, 'id'),
        ])->first();

        if (! $spotifyPlaylist) {
            $response = $this->client->createPlaylist([
                'name' => data_get($this->playlist, 'slug').' - '.config('app.name'),
                'public' => false,
            ]);

            if (! $response) {
                throw new \Exception('Could not create playlist');
            }

            $spotifyPlaylist = SpotifyPlaylist::create([
                'spotify_user_id' => data_get($this->user, 'id'),
                'playlist_id' => data_get($this->playlist, 'id'),
                'spotify_playlist_id' => data_get($response, 'id'),
                'snapshot_id' => data_get($response, 'snapshot_id'),
                'data' => $response,
            ]);
        }

        return $spotifyPlaylist;
    }

    /**
     * Sync all the remote spotify tracks with the local database
     */
    protected function syncRemoteTracks(SpotifyPlaylist $spotifyPlaylist, array $remoteSpotifyPlaylist)
    {
        $numberOfRemoteTracks = data_get($remoteSpotifyPlaylist, 'tracks.total');
        $remoteTracksIds = [];

        // loop over the remote tracks paginated by 100
        for ($i = 0; $i < $numberOfRemoteTracks; $i += 100) {
            $remoteTracks = $this->client->getPlaylistTracks(
                $spotifyPlaylist->spotify_playlist_id,
                [
                    'offset' => $i,
                    'limit' => 100,
                    'fields' => ['items(track(id))'],
                ]
            );

            $remoteTracksIds = array_merge(
                $remoteTracksIds,
                Arr::pluck($remoteTracks['items'], 'track.id')
            );
        }

        // Add all remote tracks from : $remoteTracksIds
        $spotifyPlaylist->songs()->sync(Song::whereIn('spotify_id', $remoteTracksIds)->pluck('id'));

        // Update the snapshot_id
        $spotifyPlaylist->updateSnapshotId($remoteSpotifyPlaylist);
    }

    protected function addMissingSongsToRemotePlaylist(SpotifyPlaylist $spotifyPlaylist)
    {
        $songsToAdd = $spotifyPlaylist->getMissingSongs();

        $count = $songsToAdd->count();

        $songsToAdd
            ->chunk(100, function ($songs) use ($spotifyPlaylist) {
                $this->client->addPlaylistTracks(
                    $spotifyPlaylist->spotify_playlist_id,
                    $songs->pluck('spotify_id')->toArray()
                );
            });

        return $count;
    }
}
