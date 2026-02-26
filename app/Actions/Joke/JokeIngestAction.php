<?php

namespace App\Actions\Joke;

use App\Dto\Joke\JokeIngestDTO;
use App\Enums\Joke\JokeStatus;
use App\Helpers\LogHelper;
use App\Models\Joke;
use App\Services\Embedding\EmbeddingService;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Responses\EmbeddingsResponse;
use Throwable;

class JokeIngestAction
{
    /**
     * Handle joke ingestion and embeddings.
     */
    public static function handle(JokeIngestDTO $jokeDTO): void
    {
        try {
            $joke = Joke::updateOrCreate([
                'source' => $jokeDTO->source,
                'source_id' => $jokeDTO->source_id,
            ], [
                'category' => $jokeDTO->category,
                'type' => $jokeDTO->type,
                'content' => $jokeDTO->jokeContent->parts,
                'status' => JokeStatus::PROCESSING,
            ]);

            EmbeddingService::queue([$jokeDTO->jokeContent->getEmbeddingText()])
                ->then(function (EmbeddingsResponse $response) use ($joke) {
                    DB::transaction(function () use ($joke, $response) {
                        $joke->embeddings()->updateOrCreate(
                            ['model' => EmbeddingService::getModel()],
                            [EmbeddingService::getColumn() => $response->first()]
                        );

                        $joke->update(['status' => JokeStatus::PROCESSED]);
                    });
                })
                ->catch(function (Throwable $e) use ($joke) {
                    $joke->update(['status' => JokeStatus::FAILED]);

                    LogHelper::error('Failed to process joke embedding.', [
                        'source' => $joke->source,
                        'source_id' => $joke->source_id,
                        'error' => $e->getMessage(),
                    ]);
                });
        } catch (Throwable $e) {
            LogHelper::error('Failed to handle joke ingestion.', [
                'source' => $jokeDTO->source,
                'source_id' => $jokeDTO->source_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
