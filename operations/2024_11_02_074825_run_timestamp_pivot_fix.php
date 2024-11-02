<?php

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Support\Collection;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    /**
     * Determine if the operation is being processed asynchronously.
     */
    protected bool $async = true;

    /**
     * The queue that the job will be dispatched to.
     */
    protected string $queue = 'default';

    /**
     * A tag name, that this operation can be filtered by.
     */
    protected ?string $tag = null;

    /**
     * Process the operation.
     */
    public function process(): void
    {
        Playlist::all()->each(function (Playlist $playlist) {
            $this->log('Fix for Playlist : ' . $playlist->id);
            $playlist->songs()->chunk(50, function (Collection $songs) use ($playlist) {
                $songs->each(function (Song $song) use ($playlist) {
                    if ($song->created_at < $playlist->created_at) {
                        $this->log('Song ignored #' . $song->id);

                        return;
                    }

                    $song->playlists()->updateExistingPivot($playlist, [
                        'created_at' => $song->created_at,
                        'updated_at' => now(),
                    ]);
                });
            });
        });
    }

    protected function log($message): void
    {
        Log::info($message);
    }
};
