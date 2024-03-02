<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class SpotifyPlaylistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'spotify_playlist_id' => $this->spotify_playlist_id,
            'snapshot_id'         => $this->snapshot_id,
            'url'                 => data_get($this, 'data.external_urls.spotify'),
            'recently_created'    => $this->wasRecentlyCreated,
            'songs_count'         => data_get($this, 'data.tracks.total'),
        ];
    }
}
