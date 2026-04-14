<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use Laravel\Ai\Contracts\ConversationStore as BaseConversationStore;

interface ConversationStore extends BaseConversationStore
{
    /**
     * Get a single conversation.
     */
    public function getConversation(string $conversationId): ?object;

    /**
     * Get latest conversations.
     */
    public function getLatestConversations(int $userId, int $limit): Collection;
}
