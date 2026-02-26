<?php

use App\Actions\Joke\JokeIngestAction;
use App\Dto\Joke\JokeContentDTO;
use App\Dto\Joke\JokeIngestDTO;
use App\Enums\Joke\JokeStatus;
use App\Enums\Joke\JokeType;
use App\Models\Joke as JokeModel;
use App\Services\Embedding\EmbeddingService;
use Laravel\Ai\Responses\Data\Meta;
use Laravel\Ai\Responses\EmbeddingsResponse;
use Laravel\Ai\Responses\QueuedEmbeddingsResponse;

afterEach(function () {
    Mockery::close();
});

it('ingests joke and executes embedding callback', function () {
    $jokeContent = new JokeContentDTO([
        'Why did the chicken cross the road?',
    ]);

    $jokeDTO = new JokeIngestDTO(
        source_id: 'test-sync-123',
        source: 'test-api',
        type: JokeType::SINGLE,
        jokeContent: $jokeContent,
        category: 'programming'
    );

    // Mock constants/config getters.
    $serviceMock = Mockery::mock('alias:'.EmbeddingService::class);
    $serviceMock->shouldReceive('getModel')->andReturn('test-model');
    $serviceMock->shouldReceive('getColumn')->andReturn('embedding_768');

    $embeddingsResponse = new EmbeddingsResponse([array_fill(0, 768, 0.1)], 10, new Meta);
    $queuedResponse = Mockery::mock(QueuedEmbeddingsResponse::class);
    $queuedResponse->shouldReceive('then')->andReturnUsing(function ($callback) use ($embeddingsResponse) {
        $callback($embeddingsResponse);

        return Mockery::self();
    })->shouldReceive('catch')->andReturnSelf();

    $serviceMock->shouldReceive('queue')->andReturn($queuedResponse);
    JokeIngestAction::handle($jokeDTO);

    expect(JokeModel::where('source', 'test-api')
        ->where('source_id', 'test-sync-123')
        ->where('status', JokeStatus::PROCESSED)
        ->exists())->toBeTrue();

    $joke = JokeModel::where('source_id', 'test-sync-123')->first();
    expect($joke->embeddings()->where('model', 'test-model')->exists())->toBeTrue();
});

it('handles embedding failure and updates status', function () {
    $jokeContent = new JokeContentDTO([
        'Failed joke',
    ]);

    $jokeDTO = new JokeIngestDTO(
        source_id: 'test-fail-123',
        source: 'test-api',
        type: JokeType::SINGLE,
        jokeContent: $jokeContent,
        category: 'programming'
    );

    $queuedResponse = Mockery::mock(QueuedEmbeddingsResponse::class);
    $queuedResponse->shouldReceive('then')->andReturnSelf();
    $queuedResponse->shouldReceive('catch')->andReturnUsing(function ($callback) {
        $callback(new Exception('Embedding failed'));

        return Mockery::self();
    });

    $serviceMock = Mockery::mock('alias:'.EmbeddingService::class);
    $serviceMock->shouldReceive('queue')->andReturn($queuedResponse);
    JokeIngestAction::handle($jokeDTO);

    expect(JokeModel::where('source_id', 'test-fail-123')
        ->where('status', JokeStatus::FAILED)
        ->exists())->toBeTrue();
});
