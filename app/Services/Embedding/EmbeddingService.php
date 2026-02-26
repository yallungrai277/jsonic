<?php

namespace App\Services\Embedding;

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
            ->dimensions(self::getDimension())
            ->queue(self::getProvider(), self::getModel());
    }

    /**
     * Get default provider for embedding.
     */
    public static function getProvider(): string
    {
        return config('ai.default_for_embeddings');
    }

    /**
     * Get default model for embedding.
     */
    public static function getModel(): string
    {
        return config('ai.embedding_models.'.self::getProvider().'.model');
    }

    /**
     * Get default column for embedding.
     */
    public static function getColumn(): string
    {
        return config('ai.embedding_models.'.self::getProvider().'.column');
    }

    /**
     * Get default dimension for embedding.
     */
    public static function getDimension(): int
    {
        return config('ai.embedding_models.'.self::getProvider().'.dimensions');
    }
}
