<?php

namespace App\Dto\Joke;

use App\Enums\Joke\JokeType;
use App\Models\Joke;

readonly class JokeIngestDTO
{
    public function __construct(
        public string|int $source_id,
        public string $source,
        public JokeType $type,
        public JokeContentDTO $jokeContent,
        public ?string $category = null,
    ) {}

    /**
     * Create from API response.
     */
    public static function fromApi(
        string|int $source_id,
        string $source,
        JokeType $type,
        JokeContentDTO $jokeContent,
        ?string $category = null
    ): self {
        return new self(
            $source_id,
            $source,
            $type,
            $jokeContent,
            $category
        );
    }

    /**
     * Create from a joke model.
     */
    public static function fromModel(Joke $joke): self
    {
        return new self(
            $joke->source_id,
            $joke->source,
            JokeType::from($joke->type),
            new JokeContentDTO($joke->content),
            $joke->category,
        );
    }
}
