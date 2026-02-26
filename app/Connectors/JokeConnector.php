<?php

namespace App\Connectors;

use App\Dto\Joke\JokeIngestDTO;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Throwable;

abstract class JokeConnector extends BaseConnector
{
    /**
     * Fetch and transform jokes.
     */
    protected function fetchAndTransformJokes(
        string $apiUrl,
        array $params = [],
        ?string $jokeResponseKey = null,
    ): Collection {
        $jokes = collect();
        try {
            $response = Http::get($apiUrl, $params);
            $response->throwIf(! $response->successful());

            return collect($response->json($jokeResponseKey ?: null, []))
                ->map(fn ($joke) => $this->convertJokeToDTO($joke));
        } catch (RequestException|Throwable $e) {
            $this->logError($e);
        }

        return $jokes;
    }

    /**
     * Convert joke to DTO.
     */
    abstract public function convertJokeToDTO(array $joke): JokeIngestDTO;

    /**
     * Fetch jokes.
     */
    abstract public function fetchJokes(): Collection;
}
