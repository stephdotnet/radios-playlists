<?php

namespace App\Http\Resources;

use App\Services\Spotify\SpotifyApiClient;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class SpotifyMeResource extends JsonResource
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
            'display_name' => Arr::get($this, 'display_name'),
            'is_admin'     => app(SpotifyApiClient::class)->isAdmin(),
        ];
    }
}
