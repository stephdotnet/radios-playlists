<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class PlaylistResource extends JsonResource
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
            'id'          => $this->id,
            'slug'        => $this->slug,
            'songs'       => SongResource::collection($this->whenLoaded('songs')),
            'songs_count' => $this->songs()->count(),
            'url'         => Arr::get($this->spotifyPlaylist?->data, 'external_urls.spotify'),
        ];
    }
}
