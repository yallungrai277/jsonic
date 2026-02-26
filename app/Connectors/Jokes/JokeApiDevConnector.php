<?php

namespace App\Connectors\Jokes;

use App\Connectors\JokeConnector;
use App\Dto\Joke\JokeContentDTO;
use App\Dto\Joke\JokeIngestDTO;
use App\Enums\Joke\JokeType;
use Illuminate\Support\Collection;

class JokeApiDevConnector extends JokeConnector
{
    protected string $identifier = 'jokes-dev-api';

    protected string $label = 'Joke API Dev';

    protected string $description = 'Fetches jokes from joke api dev';

    /**
     * {@inheritDoc}
     */
    public function fetchJokes(): Collection
    {
        return $this->fetchAndTransformJokes($this->config['base_url'].'/joke/Any', [
            'amount' => 10,
        ], 'jokes');
    }

    /**
     * {@inheritDoc}
     */
    public function convertJokeToDTO(array $joke): JokeIngestDTO
    {
        return JokeIngestDTO::fromApi(
            $joke['id'],
            $this->getIdentifier(),
            JokeType::from($joke['type']),
            new JokeContentDTO([
                $joke['type'] === JokeType::SINGLE->value ? ($joke['joke'] ?? null) : ($joke['setup'] ?? null),
                $joke['type'] === JokeType::SINGLE->value ? null : ($joke['delivery'] ?? null),
            ]),
            $joke['category'] ?? null
        );
    }
}
