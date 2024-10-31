<?php

namespace App\Http\Resources;

use App\Services\Parser\ParserService;
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
            'name'        => $this->name,
            'songs'       => SongResource::collection($this->whenLoaded('songs')),
            'songs_count' => $this->songs()->count(),
            'active'      => ParserService::isRadioActive($this->slug),
            'url'         => Arr::get($this->spotifyPlaylist?->data, 'external_urls.spotify'),
        ];
    }
}
