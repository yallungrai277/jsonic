<?php

use App\Connectors\JokeConnector;
use App\Dto\Joke\JokeContentDTO;
use App\Dto\Joke\JokeIngestDTO;
use App\Enums\Joke\JokeStatus;
use App\Enums\Joke\JokeType;
use App\Models\Joke as JokeModel;
use App\Services\Embedding\EmbeddingService;
use App\Services\Joke\JokeIngestionService;
use Laravel\Ai\Responses\Data\Meta;
use Laravel\Ai\Responses\EmbeddingsResponse;
use Laravel\Ai\Responses\QueuedEmbeddingsResponse;

afterEach(function () {
    Mockery::close();
});

it('ingests jokes from enabled connectors and triggers action flow', function () {
    $serviceMock = Mockery::mock('alias:'.EmbeddingService::class);
    $serviceMock->shouldReceive('getModel')->andReturn('test-model');
    $serviceMock->shouldReceive('getColumn')->andReturn('embedding_768');
    $serviceMock->shouldReceive('getDimension')->andReturn(768);

    $jokeContent = new JokeContentDTO(['Why did the chicken cross the road?']);
    $jokeDTO = new JokeIngestDTO(
        source_id: 'test-ingestion-sync',
        source: 'test-source',
        type: JokeType::SINGLE,
        jokeContent: $jokeContent
    );

    $mockConnector = Mockery::mock(JokeConnector::class);
    $mockConnector->shouldReceive('isEnabled')->andReturn(true);
    $mockConnector->shouldReceive('fetchJokes')->andReturn(collect([$jokeDTO]));

    // Mock embedding queue.
    $embeddingsResponse = new EmbeddingsResponse([array_fill(0, 768, rand(0, 10) / 10)], 10, new Meta);
    $queuedResponse = Mockery::mock(QueuedEmbeddingsResponse::class);
    $queuedResponse->shouldReceive('then')->andReturnUsing(function ($callback) use ($embeddingsResponse) {
        $callback($embeddingsResponse);

        return Mockery::self();
    });
    $queuedResponse->shouldReceive('catch')->andReturnSelf();
    $serviceMock->shouldReceive('queue')->andReturn($queuedResponse);

    // Mock service to use our mock connector.
    $service = Mockery::mock(JokeIngestionService::class)->makePartial();
    $service->shouldReceive('getEnabledConnectors')->andReturn(collect([$mockConnector]));
    $service->ingestJokes();

    $jokeModel = JokeModel::where('source_id', 'test-ingestion-sync')->first();
    expect($jokeModel)->not->toBeNull();
    expect($jokeModel->status->value)->toBe(JokeStatus::PROCESSED->value);
    expect($jokeModel->embeddings)->toHaveCount(1);
    expect($jokeModel->embeddings->first()->getAttribute(EmbeddingService::getColumn()))->toHaveCount(EmbeddingService::getDimension());
});

it('does not ingest from disabled connectors', function () {
    $mockConnector = Mockery::mock(JokeConnector::class);
    $mockConnector->shouldReceive('isEnabled')->andReturn(false);

    $service = Mockery::mock(JokeIngestionService::class)->makePartial();
    $service->shouldReceive('getAllConnectors')->andReturn(collect([$mockConnector]));
    $service->ingestJokes();

    expect(JokeModel::where('source_id', 'test-ingestion-sync')->exists())->toBeFalse();
});
