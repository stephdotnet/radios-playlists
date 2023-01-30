<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse all radios';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (config('services.parser.radios') as $radio) {
            $this->call('parse:one', ['radio' => $radio]);
        }

        return Command::SUCCESS;
    }
}
