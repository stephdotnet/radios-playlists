<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpotifyMeResource;
use App\Services\Spotify\SpotifyApi;
use App\Services\Spotify\SpotifyApiClient;
use Illuminate\Http\Request;
use Throwable;

class SpotifyAuthController extends Controller
{
    public function redirect()
    {
        $url = app(SpotifyApiClient::class)->getAuthorizeUrl();

        return redirect($url)->with('redirect', request('redirect'));
    }

    public function callback(Request $request)
    {
        if ($request->get('state') !== session()->get('state')) {
            abort(403);
        }

        app(SpotifyApiClient::class)->requestAccessToken($request->get('code'));

        return redirect(route('index', ['path' => session()->get('redirect', '/')]));
    }

    public function me()
    {
        try {
            return SpotifyMeResource::make(app(SpotifyApi::class)->getAuthenticatedClient()->me());
        } catch (Throwable) {
            return response()->json(['data' => null]);
        }
    }

    public function logout()
    {
        app(SpotifyApiClient::class)->revokeAuthenticatedClientToken();

        return redirect(route('index'));
    }
}
