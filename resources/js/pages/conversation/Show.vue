<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { nextTick, onMounted, onUnmounted, ref } from 'vue';
import ChatMessage from '@/components/ui/conversation/ChatMessage.vue';
import Prompt from '@/components/ui/conversation/Prompt.vue';
import StreamingMessage from '@/components/ui/conversation/StreamingMessage.vue';
import StreamStatusBar from '@/components/ui/conversation/StreamStatusBar.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useConversationStream } from '@/composables/useConversationStream';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as conversationIndex, update as updateRoute, stream as streamRoute } from '@/routes/conversation';
import { type BreadcrumbItem } from '@/types';

type MessageRole = 'user' | 'assistant';

type RawMessage = {
    id?: string;
    role: MessageRole;
    content: string;
};

type UiMessage = RawMessage & {
    localId: string;
};

const props = defineProps<{
    conversationId: string;
    messages: Array<RawMessage>;
    streamPrompt?: string | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: conversationIndex().url,
    },
    {
        title: 'Conversation',
        href: '#',
    },
];

const conversationHistory = ref<UiMessage[]>([]);
const messageCounter = ref(0);

const createLocalId = (): string => {
    messageCounter.value += 1;
    return `msg-${Date.now()}-${messageCounter.value}`;
};

const toUiMessages = (messages: RawMessage[]): UiMessage[] => {
    return messages.map((message) => ({
        id: message.id,
        role: message.role,
        content: message.content,
        localId: createLocalId(),
    }));
};

const normalizeMessageContent = (content: string): string => {
    return content.trim();
};

const syncMessagesFromServer = (messages: RawMessage[]): void => {
    conversationHistory.value = toUiMessages(messages);
};

/**
 * Append a user bubble. Only skips a duplicate when it is the same as the last message (submit + success double-fire).
 */
const appendUserMessage = (content: string): void => {
    const trimmed = normalizeMessageContent(content);
    if (trimmed === '') {
        return;
    }

    const last = conversationHistory.value[conversationHistory.value.length - 1];
    if (last && last.role === 'user' && normalizeMessageContent(last.content) === trimmed) {
        return;
    }

    conversationHistory.value.push({
        id: undefined,
        localId: createLocalId(),
        role: 'user',
        content: trimmed,
    });
};

const appendAssistantMessage = (content: string): void => {
    const trimmed = normalizeMessageContent(content);
    if (trimmed === '') {
        return;
    }

    const last = conversationHistory.value[conversationHistory.value.length - 1];
    if (last && last.role === 'assistant' && normalizeMessageContent(last.content) === trimmed) {
        return;
    }

    conversationHistory.value.push({
        id: undefined,
        localId: createLocalId(),
        role: 'assistant',
        content: trimmed,
    });
};

const { startStreaming, streamState, streamError, reconnectAttempts, maxReconnectAttempts, streamingResponse, stopStream } = useConversationStream(
    streamRoute({ conversation: props.conversationId }).url,
    (assistantText: string) => {
        appendAssistantMessage(assistantText);
        scrollToBottom();
    },
);

const scrollContainer = ref<HTMLElement | null>(null);

const scrollToBottom = () => {
    nextTick(() => {
        if (scrollContainer.value) {
            scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
        }
    });
};

onMounted(() => {
    syncMessagesFromServer(props.messages);
    scrollToBottom();

    if (props.streamPrompt) {
        appendUserMessage(props.streamPrompt);
        startStreaming();
    }
});

type PageProps = {
    streamPrompt?: string | null;
    messages?: unknown;
};

const isRawMessageArray = (messages: unknown): messages is RawMessage[] => {
    if (!Array.isArray(messages)) {
        return false;
    }

    return messages.every((message) => {
        return typeof message === 'object' && message !== null && 'role' in message && 'content' in message;
    });
};

const handlePromptSubmit = (message: string): void => {
    appendUserMessage(message);
    scrollToBottom();
};

const handlePromptSuccess = (pageProps: PageProps): void => {
    if (!pageProps.streamPrompt && isRawMessageArray(pageProps.messages)) {
        syncMessagesFromServer(pageProps.messages);
    }

    if (pageProps.streamPrompt) {
        startStreaming();
    }

    scrollToBottom();
};

const handleRetry = (): void => {
    startStreaming();
};

onUnmounted(() => {
    stopStream();
});
</script>

<template>
    <Head title="Chat" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="relative flex h-full flex-1 flex-col overflow-hidden bg-gradient-to-b from-background via-background to-muted/20">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_hsl(var(--primary)/0.10),_transparent_40%)]" />

            <div class="relative flex flex-1 flex-col overflow-hidden">
                <div class="absolute top-4 left-4 z-10 md:hidden">
                    <SidebarTrigger class="bg-background/80 shadow-sm backdrop-blur-sm" />
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-10 sm:px-10 lg:px-20 lg:py-12" ref="scrollContainer">
                    <div class="mx-auto flex max-w-3xl flex-col gap-8">
                        <div class="flex items-center justify-center">
                            <StreamStatusBar
                                :state="streamState"
                                :reconnect-attempts="reconnectAttempts"
                                :max-reconnect-attempts="maxReconnectAttempts"
                                :error-detail="streamError"
                                wrapper-class="fixed top-2 shadow-sm"
                            />
                        </div>

                        <ChatMessage v-for="message in conversationHistory" :key="message.localId" :role="message.role" :content="message.content" />

                        <StreamingMessage
                            v-if="streamState !== 'idle' || streamingResponse"
                            :content="streamingResponse"
                            :state="streamState"
                            :error-message="streamError"
                            :on-retry="streamState === 'error' ? handleRetry : undefined"
                        />
                    </div>
                </div>

                <Prompt
                    :submit-url="updateRoute({ conversation: props.conversationId }).url"
                    :disabled="streamState === 'connecting' || streamState === 'streaming' || streamState === 'finishing'"
                    method="patch"
                    @prompt-submit="handlePromptSubmit"
                    @prompt-success="handlePromptSuccess"
                />
            </div>
        </div>
    </AppLayout>
</template>
