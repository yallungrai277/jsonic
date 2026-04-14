<?php

namespace App\Services\Ai;

use Laravel\Ai\Ai;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Responses\QueuedEmbeddingsResponse;

class EmbeddingService
{
    /**
     * Queue embedding.
     */
    public static function queue(array $text): QueuedEmbeddingsResponse
    {
        return Embeddings::for($text)
            ->dimensions(self::getDimensions())
            ->queue();
    }

    /**
     * Get the default model for embedding.
     */
    public static function getModel(): string
    {
        return Ai::embeddingProvider()->defaultEmbeddingsModel();
    }

    /**
     * Get default column for embedding.
     */
    public static function getColumn(): string
    {
        $provider = config('ai.default_for_embeddings');

        return config('ai.providers.'.$provider.'.models.embeddings.db_column');
    }

    /**
     * Get default dimension for embedding.
     */
    public static function getDimensions(): int
    {
        return Ai::embeddingProvider()->defaultEmbeddingsDimensions();
    }
}
