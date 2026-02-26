<?php

namespace App\Enums\Joke;

enum JokeType: string
{
    case SINGLE = 'single';

    case TWO_PART = 'twopart';

    /**
     * Label for enum value.
     */
    public function toLabel(): string
    {
        return match ($this) {
            self::SINGLE => 'Single',
            self::TWO_PART => 'Two Part',
        };
    }
}
