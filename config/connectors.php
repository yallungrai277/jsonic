<?php

return [
    /**
     * Jokes dev.
     */
    'jokes-dev-api' => [
        'base_url' => env('JOKES_DEV_API_BASE_URL', 'https://v2.jokeapi.dev'),
        'enabled' => env('JOKES_DEV_API_ENABLED', true),
    ],
    /**
     * Official joke.
     */
    'official-joke-api' => [
        'base_url' => env('OFFICIAL_JOKE_API_BASE_URL', 'https://official-joke-api.appspot.com'),
        'enabled' => env('OFFICIAL_JOKE_API_ENABLED', true),
    ],
];
