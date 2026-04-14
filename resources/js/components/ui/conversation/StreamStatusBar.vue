<script setup lang="ts">
import { computed } from 'vue';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import type { StreamState } from './types';
import { cn } from '@/lib/utils';

interface Props {
    state: StreamState;
    reconnectAttempts?: number;
    maxReconnectAttempts?: number;
    errorDetail?: string | null;
    wrapperClass: string;
}

const props = withDefaults(defineProps<Props>(), {
    reconnectAttempts: 0,
    maxReconnectAttempts: 5,
    errorDetail: null,
    wrapperClass: ''
});

const statusLabel = computed(() => {
    switch (props.state) {
        case 'connecting':
            return props.reconnectAttempts > 1 ? 'Reconnecting' : 'Connecting';
        case 'streaming':
            return 'Generating';
        case 'finishing':
            return 'Finalizing';
        case 'error':
            return 'Connection closed';
        default:
            return 'Idle';
    }
});

const tooltipContent = computed(() => {
    const parts: string[] = [statusLabel.value];

    if (props.state === 'connecting' && props.reconnectAttempts > 0) {
        parts.push(`Attempt ${props.reconnectAttempts}/${props.maxReconnectAttempts}`);
    }

    if (props.state === 'error') {
        parts.push(`Max reconnect attempts reached (${props.maxReconnectAttempts})`);
        if (props.errorDetail) {
            parts.push(props.errorDetail);
        }
    }

    return parts.join('\n');
});

const statusClass = computed(() => {
    switch (props.state) {
        case 'connecting':
            return 'bg-primary';
        case 'streaming':
            return 'bg-green-500';
        case 'finishing':
            return 'bg-green-500';
        case 'error':
            return 'bg-red-500';
        default:
            return 'bg-yellow-500';
    }
});

const wrapperClasses = computed(() => {
    return cn('text-sm flex items-center bg-secondary px-4 py-2 rounded-2xl gap-2', props.wrapperClass);
});

</script>

<template>
    <Tooltip>
        <TooltipTrigger as-child>
            <div :class="wrapperClasses">
                <span
                    class="inline-block rounded-full p-0.75"
                    :class="statusClass"
                />
                <span class="stream-status-bar__label">
                    {{ statusLabel }}
                </span>
            </div>
        </TooltipTrigger>
        <TooltipContent>
            <p class="text-xs whitespace-pre-line">{{ tooltipContent }}</p>
        </TooltipContent>
    </Tooltip>
</template>
