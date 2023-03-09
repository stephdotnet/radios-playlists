<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpotifyMeResource;
use App\Services\Spotify\SpotifyApi;
use App\Services\Spotify\SpotifyApiClient;
use Exception;
use Illuminate\Http\Request;

class SpotifyAuthController extends Controller
{
    public function redirect()
    {
        $url = app(SpotifyApiClient::class)->getAuthorizeUrl();
        session()->flash('redirect', request('redirect'));

        return redirect($url);
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
        } catch (Exception) {
            return response()->json(['data' => null]);
        }
    }

    public function logout()
    {
        app(SpotifyApiClient::class)->revokeAuthenticatedClientToken();

        return redirect(route('index'));
    }
}
