<?php

namespace App\Stores;

use App\Contracts\ConversationStore;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Storage\DatabaseConversationStore as BaseConversationStore;

class DatabaseConversationStore extends BaseConversationStore implements ConversationStore
{
    /**
     * {@inheritDoc}
     */
    public function getConversation(string $conversationId): ?object
    {
        return DB::table('agent_conversations')->where('id', $conversationId)->first();
    }

    /**
     * Get the latest conversations for a user.
     */
    public function getLatestConversations(int $userId, int $limit): Collection
    {
        return DB::table('agent_conversations')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->limit($limit)
            ->select(['id', 'title', 'created_at'])
            ->get();
    }
}
