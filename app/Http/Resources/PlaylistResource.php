<?php

namespace App\Http\Resources;

use App\Models\Playlist;
use App\Services\Parser\ParserService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @mixin Playlist
 */
class PlaylistResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'name'        => $this->name,
            'songs'       => SongResource::collection($this->whenLoaded('songs')),
            'songs_count' => $this->songs()->count(),
            'active'      => ParserService::isRadioActive($this->slug),
            'url'         => $this->whenLoaded(
                'spotifyPlaylist',
                fn () => Arr::get($this->spotifyPlaylist?->data, 'external_urls.spotify'),
            ),
        ];
    }
}
