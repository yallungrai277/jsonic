<?php

use App\Console\Commands\IngestJokes;
use App\Services\Joke\JokeIngestionService;

it('executes joke ingestion service', function () {
    $mock = Mockery::mock(JokeIngestionService::class);
    $mock->shouldReceive('ingestJokes')->once();

    $this->app->instance(JokeIngestionService::class, $mock);
    $this->artisan(IngestJokes::SIGNATURE)->assertSuccessful();
});
