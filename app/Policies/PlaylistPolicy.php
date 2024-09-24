<?php

namespace App\Policies;

use App\Models\User;
use App\Services\Spotify\SpotifyApiClient;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct() {}

    public function delete(?User $user)
    {
        return app(SpotifyApiClient::class)->isAdmin();
    }
}
