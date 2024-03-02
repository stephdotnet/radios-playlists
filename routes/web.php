<?php

use App\Http\Controllers\SpotifyAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/spotify-redirect', SpotifyAuthController::class . '@redirect')
    ->name('spotify.redirect');

Route::get('/spotify-callback', SpotifyAuthController::class . '@callback')
    ->name('spotify.callback');

Route::get('/logout', SpotifyAuthController::class . '@logout')
    ->name('spotify.logout');

Route::get('/{path?}', fn () => view('index'))->where(['path' => '.*'])
    ->name('index');
