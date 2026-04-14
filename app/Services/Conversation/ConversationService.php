<?php

namespace App\Services\Conversation;

use App\Contracts\ConversationStore;
use Illuminate\Support\Facades\Auth;

class ConversationService
{
    /**
     * Convert recent conversations to nav menu.
     */
    public static function recentConversationsToNavMenu(): array
    {
        if (! Auth::check()) {
            return [];
        }

        return app(ConversationStore::class)->getLatestConversations(Auth::id(), 10)
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'title' => $message->title,
                    'url' => route('conversation.show', $message->id),
                ];
            })->toArray();
    }
}
