<?php

namespace App\Http\Controllers;

use App\Services\Spotify\SpotifyApiClient;
use Illuminate\Http\Request;

class SpotifyAuthController extends Controller
{
    public function redirect()
    {
        $url = app(SpotifyApiClient::class)->getAuthorizeUrl();

        return redirect($url);
    }

    public function callback(Request $request)
    {
        if ($request->get('state') !== session()->get('state')) {
            abort(403);
        }

        app(SpotifyApiClient::class)->requestAccessToken($request->get('code'));

        return redirect(route('index'));
    }
}
