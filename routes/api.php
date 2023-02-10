<?php

use App\Http\Controllers\PlaylistController;
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

Route::get('/playlists', PlaylistController::class.'@index')
    ->name('playlist.index');

Route::get('/playlists/{playlist}', PlaylistController::class.'@show')
    ->name('playlist.show');
