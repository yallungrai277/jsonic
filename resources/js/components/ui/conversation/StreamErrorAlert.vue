<script setup lang="ts">
interface Props {
    message: string;
    onRetry?: () => void;
}

defineProps<Props>();
</script>

<template>
    <div
        class="flex w-full items-center justify-between gap-2 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm dark:border-red-800/30 dark:bg-red-900/20"
    >
        <span class="text-xs font-medium text-red-600 dark:text-red-400">{{ message }}</span>
        <button
            v-if="onRetry"
            class="shrink-0 rounded-md bg-red-100 px-3 py-1 text-xs font-medium text-red-600 transition hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50"
            @click="onRetry"
        >
            Retry
        </button>
    </div>
</template>