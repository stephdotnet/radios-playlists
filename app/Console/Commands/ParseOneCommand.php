<?php

namespace App\Console\Commands;

use App\DataTransferObjects\SpotifySongDTO;
use App\Facades\Parser;
use App\Models\Playlist;
use App\Models\Song;
use App\Services\Spotify\SpotifyApi;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

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
    public function handle(SpotifyApi $spotifyApi)
    {
        if (! $this->isRadioAllowed($this->argument('radio'))) {
            $this->error('Radio not allowed');

            return SymfonyCommand::FAILURE;
        }

        $response = Parser::driver(Parser::getDriverForRadio($this->argument('radio')))
            ->setRadio($this->argument('radio'))
            ->parse();

        if ($matchingSong = $spotifyApi->getMatchingSong($response)) {
            $playlist = Playlist::firstOrCreate([
                'slug' => $this->argument('radio'),
            ]);

            $this->addMatchingSong($playlist, $matchingSong);

            return SymfonyCommand::SUCCESS;
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

        if ($playlist->hasForbiddenSong($song)) {
            $this->logInfo("Song #$song->id not added because in forbidden list ($song->name - $song->artists)");
        } elseif (! $song->wasRecentlyCreated) {
            $this->logInfo("Song #$song->id already exists ($song->name - $song->artists)");
        } elseif (! $playlist->hasSong($song)) {
            $playlist->songs()->attach($song);
            $this->logInfo("Song #$song->id added ($song->name)");
        }

        return $song;
    }

    protected function isRadioAllowed($radio): bool
    {
        return in_array($radio, config('services.parser.radios'));
    }

    protected function logInfo($message): void
    {
        $this->info($message);
        Log::info($message);
    }
}
