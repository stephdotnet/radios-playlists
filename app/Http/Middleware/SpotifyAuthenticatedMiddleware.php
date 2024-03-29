<?php

namespace App\Http\Middleware;

use App\Services\Spotify\SpotifyApiClient;
use Closure;
use Illuminate\Http\Request;

class SpotifyAuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! app(SpotifyApiClient::class)->isAuthenticated()) {
            return redirect(route('index'));
        }

        return $next($request);
    }
}
