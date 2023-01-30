<?php

namespace App\Console\Commands;

use App\DataTransferObjects\SpotifySongDTO;
use App\Facades\Parser;
use App\Models\Playlist;
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
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->isRadioAllowed($this->argument('radio'))) {
            $this->error('Radio not allowed');

            return;
        }

        $response = Parser::driver('radiosFr')
            ->setRadio($this->argument('radio'))
            ->parse();

        if ($matchingSong = app(SpotifyApi::class)->getMatchingSong($response)) {
            $playlist = Playlist::firstORCreate([
                'slug' => $this->argument('radio'),
            ]);

            $song = $playlist->songs()->firstOrCreate([
                'spotify_id' => Arr::get($matchingSong, 'id'),
            ], SpotifySongDTO::toModel($matchingSong));

            if ($song->wasRecentlyCreated) {
                $this->logInfo("Song #$song->id added (Artist: $response->artist and Song: $response->song)");
            } else {
                $this->logInfo("Song already exists (Artist: $response->artist and Song: $response->song)");
            }
        } else {
            $this->logInfo("No matching song found for Artist: $response->artist and Song: $response->song");
        }
    }

    public function isRadioAllowed($radio)
    {
        return in_array($radio, config('services.parser.radios'));
    }

    public function logInfo($message)
    {
        $this->info($message);
        Log::info($message);
    }
}
