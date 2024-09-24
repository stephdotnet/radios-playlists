<?php

namespace App\Services\Spotify;

use App\Exceptions\Services\Spotify\SpotifyAuthException;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\SpotifyPlaylist;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyApiSync
{
    const BASIC_PLAYLIST_INFORMATIONS = [
        'fields' => ['id', 'snapshot_id', 'tracks(total)', 'external_urls(spotify)'],
    ];

    public function __construct(
        protected SpotifyWebAPI $client,
        protected ?Playlist $playlist = null,
        protected ?array $user = [],
    ) {}

    public function sync(): array
    {
        $this->checkRequisites();

        $spotifyPlaylist = $this->firstOrCreatePlaylist();
        $dirtySnapshotId = $spotifyPlaylist->snapshot_id;

        $spotifyPlaylist = $this->syncPlaylistInformations($spotifyPlaylist);

        if ($dirtySnapshotId != $spotifyPlaylist->snapshot_id) {
            $needRefresh = true;
            $this->syncRemoteTracks($spotifyPlaylist);
        }

        $syncedSongs = $this->addMissingSongsToRemotePlaylist($spotifyPlaylist);

        return [
            'syncedSongs'     => $syncedSongs,
            'spotifyPlaylist' => $spotifyPlaylist,
            'needRefresh'     => $needRefresh ?? false,
        ];
    }

    public function syncPlaylistInformations($spotifyPlaylist, $fields = self::BASIC_PLAYLIST_INFORMATIONS)
    {
        $informations = $this->client->getPlaylist($spotifyPlaylist->spotify_playlist_id, $fields);
        $spotifyPlaylist->updateSnapshotId($informations);

        return $spotifyPlaylist;
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
     * @throws Exception
     */
    protected function checkRequisites(): void
    {
        if (! $this->playlist) {
            throw new Exception('Playlist is not set');
        }

        if (! $this->user = $this->client->me()) {
            throw new SpotifyAuthException('Unauthenticated user');
        }
    }

    protected function firstOrCreatePlaylist(): SpotifyPlaylist
    {
        $spotifyPlaylist = SpotifyPlaylist::where([
            'spotify_user_id' => data_get($this->user, 'id'),
            'playlist_id'     => data_get($this->playlist, 'id'),
        ])->first();

        if (! $spotifyPlaylist) {
            $response = $this->client->createPlaylist(data_get($this->user, 'id'), [
                'name'   => data_get($this->playlist, 'slug') . ' - ' . config('app.name'),
                'public' => false,
            ]);

            if (! $response) {
                throw new Exception('Could not create playlist');
            }

            $spotifyPlaylist = SpotifyPlaylist::create([
                'spotify_user_id'     => data_get($this->user, 'id'),
                'playlist_id'         => data_get($this->playlist, 'id'),
                'spotify_playlist_id' => data_get($response, 'id'),
                'snapshot_id'         => data_get($response, 'snapshot_id'),
                'data'                => $response,
            ]);
        }

        return $spotifyPlaylist;
    }

    /**
     * Sync all the remote spotify tracks with the local database
     */
    protected function syncRemoteTracks(SpotifyPlaylist $spotifyPlaylist)
    {
        $remoteTracksIds = [];
        for ($i = 0; $i < data_get($spotifyPlaylist->data, 'tracks.total'); $i += 100) {
            Log::info('Syncing remote track on playlist #' . $spotifyPlaylist->spotify_playlist_id . '. Batch #' . $i);
            $remoteTracks = $this->client->getPlaylistTracks(
                $spotifyPlaylist->spotify_playlist_id,
                [
                    'offset' => $i,
                    'limit'  => 100,
                    'fields' => ['items(track(id))'],
                ],
            );

            $remoteTracksIds = array_merge($remoteTracksIds, Arr::pluck($remoteTracks['items'], 'track.id'));
        }

        $spotifyPlaylist->songs()
            ->sync(Song::whereIn('spotify_id', $remoteTracksIds)->pluck('id'));
        $spotifyPlaylist->refresh();
    }

    protected function addMissingSongsToRemotePlaylist(SpotifyPlaylist $spotifyPlaylist)
    {
        $songsToAdd = $spotifyPlaylist->getMissingSongs();

        $count = $songsToAdd->count();

        $songsToAdd
            ->chunk(100, function ($songs) use ($spotifyPlaylist) {
                Log::info('Adding to playlist #' . $spotifyPlaylist->spotify_playlist_id);
                $this->client->addPlaylistTracks(
                    $spotifyPlaylist->spotify_playlist_id,
                    $songs->pluck('spotify_id')->toArray(),
                );

                $spotifyPlaylist->songs()->attach($songs->pluck('id')->toArray());
            });

        return $count;
    }
}
