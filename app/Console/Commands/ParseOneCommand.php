<?php

namespace App\Console\Commands;

use App\DataTransferObjects\SpotifySongDTO;
use App\Facades\Parser;
use App\Models\Playlist;
use App\Models\Song;
use App\Services\Parser\ParserResponse;
use App\Services\Spotify\SpotifyApi;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class ParseOneCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'parse:one {radio}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Parse one source';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->isRadioAllowed($this->argument('radio'))) {
            $this->error('Radio not allowed');

            return Command::FAILURE;
        }

        $response = Parser::driver('radiosFr')
            ->setRadio($this->argument('radio'))
            ->parse();

        if ($matchingSong = app(SpotifyApi::class)->getMatchingSong($response)) {
            $playlist = Playlist::firstOrCreate([
                'slug' => $this->argument('radio'),
            ]);

            $song = $this->addMatchingSong($playlist, $matchingSong);

            $this->logSongUpdateOrCreate($song, $response);

            return;
        }

        $this->logInfo("No matching song found for Artist: $response->artist and Song: $response->song");
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    */

    protected function addMatchingSong(Playlist $playlist, array $matchingSong)
    {
        $song = Song::firstOrCreate([
            'spotify_id' => Arr::get($matchingSong, 'id'),
        ], SpotifySongDTO::toModel($matchingSong));

        if (! $playlist->hasSong($song) && ! $playlist->hasForbiddenSong($song)) {
            $playlist->songs()->attach($song);

            return $song;
        }
    }

    protected function isRadioAllowed($radio)
    {
        return in_array($radio, config('services.parser.radios'));
    }

    protected function logSongUpdateOrCreate(?Song $song, ParserResponse $response)
    {
        if (! $song) {
            return $this->logInfo("Song not added because in forbidden list (Artist: $response->artist and Song: $response->song)");
        }

        if ($song->wasRecentlyCreated) {
            return $this->logInfo("Song #$song->id added (Artist: $response->artist and Song: $response->song)");
        }

        return $this->logInfo("Song already exists (Artist: $response->artist and Song: $response->song)");
    }

    protected function logInfo($message)
    {
        $this->info($message);
        Log::info($message);
    }
}
