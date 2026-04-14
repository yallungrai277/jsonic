<script setup lang="ts">
import StreamErrorAlert from './StreamErrorAlert.vue';
import type { StreamState } from './types';

interface Props {
    content: string;
    state: StreamState;
    errorMessage?: string | null;
    onRetry?: () => void;
}

defineProps<Props>();

const streamingText = () => {
    const items = ['Cooking...', 'Saucing up...', 'Thinking...']

    return items[Math.floor(Math.random() * items.length)];
}

</script>

<template>
    <div class="streaming-message flex flex-col gap-4 text-sm">
        <div
            v-if="content || state === 'connecting' || state === 'streaming'"
            class="inline-flex flex-col gap-4"
        >
            <span>{{ content }}</span>
            <span
                v-if="state === 'connecting' || (state === 'streaming' && !content)"
                class="flex gap-0.5"
            >
                    <span class="inline-block animate-pulse rounded-full bg-primary px-4 py-2 text-primary-foreground">
                         {{ streamingText() }}
                    </span>
                </span>
        </div>
        <StreamErrorAlert
            v-if="state === 'error'"
            :message="errorMessage || 'Connection failed'"
            :on-retry="onRetry"
        />
    </div>
</template>
