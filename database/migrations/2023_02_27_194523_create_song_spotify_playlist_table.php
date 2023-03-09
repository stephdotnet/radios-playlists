<?php

use App\Models\Song;
use App\Models\SpotifyPlaylist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_spotify_playlist', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SpotifyPlaylist::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Song::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_spotify_playlist');
    }
};
