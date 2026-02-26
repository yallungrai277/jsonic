<?php

namespace App\Connectors\Jokes;

use App\Connectors\JokeConnector;
use App\Dto\Joke\JokeContentDTO;
use App\Dto\Joke\JokeIngestDTO;
use App\Enums\Joke\JokeType;
use Illuminate\Support\Collection;

class OfficialJokeApiConnector extends JokeConnector
{
    protected string $identifier = 'official-joke-api';

    protected string $label = 'Official Joke API';

    protected string $description = 'Fetches joke from official Joke API';

    /**
     * {@inheritDoc}
     */
    public function fetchJokes(): Collection
    {
        return $this->fetchAndTransformJokes($this->config['base_url'].'/jokes/ten');
    }

    /**
     * {@inheritDoc}
     */
    public function convertJokeToDTO(array $joke): JokeIngestDTO
    {
        return JokeIngestDTO::fromApi(
            $joke['id'],
            $this->getIdentifier(),
            JokeType::from(JokeType::TWO_PART->value),
            new JokeContentDTO([
                $joke['setup'] ?? null,
                $joke['punchline'] ?? null,
            ]),
            $joke['type'] ?? null
        );
    }
}
