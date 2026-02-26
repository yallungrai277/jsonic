<?php

namespace App\Dto\Joke;

use InvalidArgumentException;

class JokeContentDTO
{
    /**
     * Parts prefix for joke content.
     */
    public const PARTS_PREFIX = 'part_';

    /**
     * Parts of the joke.
     */
    public array $parts;

    public function __construct(array $parts = [])
    {
        $normalized = array_values(array_filter($parts, fn ($p) => is_string($p) && trim($p) !== ''));
        if (empty($normalized[0])) {
            throw new InvalidArgumentException('Part one is required');
        }

        // Assign dynamic keys: part_1, part_2, etc...
        $this->parts = [];
        foreach ($normalized as $index => $part) {
            $this->parts[self::PARTS_PREFIX.($index + 1)] = trim($part);
        }
    }

    /**
     * Return content for embedding dynamically.
     */
    public function getEmbeddingText(): string
    {
        return collect($this->parts)
            ->map(fn ($val, $key) => ucfirst(str_replace('_', ' ', $key)).': '.$val)
            ->join("\n");
    }
}
