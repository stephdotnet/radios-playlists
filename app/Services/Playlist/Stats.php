<?php

namespace App\Services\Playlist;

use App\Models\Playlist;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;

class Stats
{
    public function __construct(public Playlist $playlist, public ?array $data = null) {}

    public function setPlaylist(Playlist $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function make(): self
    {
        // Get songs per day
        $data  = [];
        $total = 1;

        foreach ($this->playlist->songs()->orderBy('created_at')->cursor() as $song) {
            $data[$song->created_at->format('Y-m-d')] = [
                'total' => $total++,
                'count' => Arr::get($data, $song->created_at->format('Y-m-d') . '.count', 0) + 1
            ];
        }

        // Fill in the gaps
        $oldest = $this->playlist->songs()->oldest()->first();
        $latest = $this->playlist->songs()->latest()->first();

        $total = 0;
        foreach (CarbonPeriod::create($oldest->created_at, '1 day', $latest->created_at) as $period) {
            if (isset($data[$period->format('Y-m-d')])) {
                $total = $data[$period->format('Y-m-d')]['total'];
            } else {
                $data[$period->format('Y-m-d')] = [
                    'total' => $total,
                    'count' => 0
                ];
            }
        }

        // Parse and aggregate the sum per day
        ksort($data);
        $this->data = $data;

        return $this;
    }

    public function json(): bool|string
    {
        return json_encode($this->data, true);
    }
}
