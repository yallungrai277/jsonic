<?php

use App\Services\Conversation\ConversationService;

return [
    [
        'id' => 'new-conversation',
        'title' => 'New conversation',
        'route' => 'conversation.index',
        'route_params' => [],
        'icon' => 'MessageSquare',
        'weight' => 1,
        'children' => [],
        'permission' => [],
    ],
    [
        'id' => 'recent-conversations',
        'title' => 'Recent conversations',
        'icon' => 'MessageSquare',
        'weight' => 2,
        'children_callback' => [ConversationService::class, 'recentConversationsToNavMenu'],
        'permission' => [],
    ],
    [
        'id' => 'admin',
        'title' => 'Admin',
        'icon' => 'MessageSquare',
        'weight' => 3,
        'children' => [],
        'permission' => [],
    ],
];
