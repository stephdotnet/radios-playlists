<?php

use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PlaylistStatsController;
use App\Http\Controllers\SpotifyAuthController;
use App\Http\Controllers\SpotifyPlaylistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/me', SpotifyAuthController::class . '@me')
    ->name('spotify.me');

Route::get('/playlists', PlaylistController::class . '@index')
    ->name('playlist.index');

Route::get('/playlists/{playlist}', PlaylistController::class . '@show')
    ->name('playlist.show');

Route::get('/playlists/{playlist}/stats', PlaylistStatsController::class . '@show')
    ->name('playlist.stats.show');

Route::get('/playlists/{playlist}/songs', PlaylistController::class . '@songs')
    ->name('playlist.songs');

Route::get('/playlists/{playlist}/sync-count/', SpotifyPlaylistController::class . '@count')
    ->name('spotify.playlist.sync-count');

Route::group(['middleware' => 'spotify'], function () {
    Route::post('/playlists/{playlist}/sync/', SpotifyPlaylistController::class . '@sync')
        ->name('spotify.playlist.sync');

    Route::delete('/playlists/{playlist}/songs/{song}', PlaylistController::class . '@deleteSong')
        ->middleware('can:delete-song,song')
        ->name('playlist.songs.delete');
});
