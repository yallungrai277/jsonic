<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

test('authenticated user can view conversation index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $conversationId = (string) Str::uuid();
    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $user->id,
        'title' => 'Test Conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get(route('conversation.index'));

    $response->assertOk();
    $response->assertSee('Test Conversation');
});

test('authenticated user can view a specific conversation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $conversationId = (string) Str::uuid();
    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $user->id,
        'title' => 'My Test Conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $messageId = (string) Str::uuid();
    DB::table('agent_conversation_messages')->insert([
        'id' => $messageId,
        'conversation_id' => $conversationId,
        'user_id' => $user->id,
        'agent' => \App\Ai\Agents\JokesGenerator::class,
        'role' => 'user',
        'content' => 'Hello',
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '[]',
        'meta' => '[]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get(route('conversation.show', ['conversation' => $conversationId]));

    $response->assertOk();
    $response->assertSee('My Test Conversation');
    $response->assertSee('Hello');
});

test('authenticated user cannot view another users conversation', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $this->actingAs($user);

    $conversationId = (string) Str::uuid();
    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $otherUser->id,
        'title' => 'Private Conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get(route('conversation.show', ['conversation' => $conversationId]));

    $response->assertStatus(403);
});

test('guests are redirected when accessing conversation routes', function (string $method, string $routeName, array $parameters, array $payload = []) {
    $response = match ($method) {
        'get' => $this->get(route($routeName, $parameters)),
        'post' => $this->post(route($routeName, $parameters), $payload),
        'patch' => $this->patch(route($routeName, $parameters), $payload),
    };

    $response->assertRedirect(route('login'));
})->with([
    'index' => ['get', 'conversation.index', []],
    'show' => ['get', 'conversation.show', ['conversation' => 'conversation-id']],
    'store' => ['post', 'conversation.store', [], ['message' => 'Tell me something funny']],
    'update' => ['patch', 'conversation.update', ['conversation' => 'conversation-id'], ['message' => 'Tell me another']],
    'stream' => ['get', 'conversation.stream', ['conversation' => 'conversation-id']],
]);

test('conversation update caches prompt without persisting user message before stream', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $chatId = (string) Str::uuid();

    DB::table('agent_conversations')->insert([
        'id' => $chatId,
        'user_id' => $user->id,
        'title' => 'Test conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->patch(route('conversation.update', ['conversation' => $chatId]), [
        'message' => 'Tell me a clean joke',
    ]);

    $response->assertRedirect(route('conversation.show', ['conversation' => $chatId]));
    expect(Cache::get('streamPrompt'.$chatId))->toBe('Tell me a clean joke');
    expect(DB::table('agent_conversation_messages')->count())->toBe(0);
});

test('conversation stream returns no content when no pending prompt exists', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $chatId = (string) Str::uuid();

    DB::table('agent_conversations')->insert([
        'id' => $chatId,
        'user_id' => $user->id,
        'title' => 'Test conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->get(route('conversation.stream', ['conversation' => $chatId]));

    $response->assertNoContent();
});
