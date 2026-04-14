<?php

namespace App\Http\Controllers\Conversation;

use App\Ai\Agents\JokesGenerator;
use App\Contracts\ConversationStore;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conversation\ConversationStoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Ai\Responses\StreamableAgentResponse;

class ConversationController extends Controller
{
    const STREAM_PROMPT_CACHE_KEY = 'streamPrompt';

    /**
     * Index view.
     */
    public function index(): Response
    {
        $greetings = config('app.conversation_greeting', []);

        return Inertia::render('conversation/Index', [
            'greeting' => ! empty($greetings) ? Arr::random($greetings) : null,
        ]);
    }

    /**
     * Store a new conversation.
     */
    public function store(ConversationStoreRequest $request): RedirectResponse
    {
        $message = $request->validated()['message'] ?? '';
        $conversationId = app(ConversationStore::class)->storeConversation($request->user()->getKey(), $message);

        $this->storePendingPromptOnCache($conversationId, $message);

        return redirect()->route('conversation.show', ['conversation' => $conversationId])
            ->with('streamPrompt', $message);
    }

    /**
     * Update a conversation.
     */
    public function update(ConversationStoreRequest $request, string $conversation): RedirectResponse
    {
        $message = $request->validated()['message'] ?? '';

        $this->storePendingPromptOnCache($conversation, $message);

        return redirect()->route('conversation.show', ['conversation' => $conversation])
            ->with('streamPrompt', $message);
    }

    /**
     * Show a conversation.
     */
    public function show(string $conversation)
    {
        $this->authorizeConversation($conversation);

        return Inertia::render('conversation/Show', [
            'conversationId' => $conversation,
            'messages' => app(ConversationStore::class)->getLatestConversationMessages($conversation, 50),
            'streamPrompt' => $this->retrievePendingPromptFromCache($conversation),
        ]);
    }

    /**
     * Stream a conversation.
     */
    public function stream(string $conversation): StreamableAgentResponse|HttpResponse
    {
        $this->authorizeConversation($conversation);

        $prompt = $this->retrievePendingPromptFromCache($conversation);
        if (blank($prompt)) {
            return response()->noContent();
        }

        return (new JokesGenerator)->continue($conversation, request()->user())
            ->stream($prompt)
            ->then(fn () => $this->forgetPendingPromptFromCache($conversation));
    }

    /**
     * Clear the pending prompt from cache.
     */
    public function clearPrompt(string $conversation): HttpResponse
    {
        $this->authorizeConversation($conversation);

        $this->forgetPendingPromptFromCache($conversation);

        return response()->noContent();
    }

    /**
     * Authorize conversation.
     */
    protected function authorizeConversation(string $conversationId): void
    {
        $conversation = app(ConversationStore::class)->getConversation($conversationId);
        if (! $conversation) {
            abort(404);
        }

        if ($conversation->user_id !== request()->user()->getKey()) {
            abort(403);
        }
    }

    protected function storePendingPromptOnCache(string $conversationId, string $prompt): void
    {
        Cache::put(self::STREAM_PROMPT_CACHE_KEY.$conversationId, $prompt, now()->addMinutes(5));
    }

    protected function retrievePendingPromptFromCache(string $conversationId, $method = 'get'): ?string
    {
        $method = strtolower($method);
        if (! in_array($method, ['get', 'pull'])) {
            return '';
        }

        return Cache::{$method}(self::STREAM_PROMPT_CACHE_KEY.$conversationId);
    }

    protected function forgetPendingPromptFromCache(string $conversationId): void
    {
        Cache::forget(self::STREAM_PROMPT_CACHE_KEY.$conversationId);
    }
}
