<?php

namespace App\Console\Commands;

use App\Services\Joke\JokeIngestionService;
use Illuminate\Console\Command;

class IngestJokes extends Command
{
    public const SIGNATURE = 'app:ingest-jokes';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = self::SIGNATURE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ingest jokes from external API registered in config.';

    /**
     * Execute the console command.
     */
    public function handle(JokeIngestionService $service)
    {
        $service->ingestJokes();
    }
}
