<?php

namespace App\Services\Joke;

use App\Actions\Joke\JokeIngestAction;
use App\Connectors\JokeConnector;
use App\Dto\Joke\JokeIngestDTO;
use App\Helpers\FileHelper;
use Illuminate\Support\Collection;
use ReflectionClass;

class JokeIngestionService
{
    /**
     * Ingest jokes from all the connectors.
     */
    public function ingestJokes(): void
    {
        $this->getEnabledConnectors()->each(function (JokeConnector $connector) {
            $this->ingest($connector);
        });
    }

    /**
     * Get all connectors.
     *
     * @return Collection<int, JokeConnector>
     */
    public function getAllConnectors(): Collection
    {
        return FileHelper::getClassesFromPath(app_path('Connectors'), function (ReflectionClass $reflectionClass) {
            return ! $reflectionClass->isAbstract() && is_subclass_of($reflectionClass->getName(), JokeConnector::class);
        });
    }

    /**
     * Get enabled connectors.
     *
     * @return Collection<int, JokeConnector>
     */
    public function getEnabledConnectors(): Collection
    {
        return $this->getAllConnectors()
            ->filter(fn (JokeConnector $connector) => $connector->isEnabled())
            ->values();
    }

    /**
     * Ingest jokes from a single connector.
     */
    public function ingest(JokeConnector $connector): void
    {
        $connector->fetchJokes()
            ->each(function (JokeIngestDTO $joke) {
                JokeIngestAction::handle($joke);
            });
    }
}
