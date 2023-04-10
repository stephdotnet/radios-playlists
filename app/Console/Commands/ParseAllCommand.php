<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
            try {
                Artisan::call('parse:one', ['radio' => $radio]);
            } catch (Exception) {
            }
        }

        return Command::SUCCESS;
    }
}
