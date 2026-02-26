<?php

namespace App\Enums\Joke;

enum JokeStatus: string
{
    case PROCESSING = 'processing';

    case PROCESSED = 'processed';

    case FAILED = 'failed';
}
