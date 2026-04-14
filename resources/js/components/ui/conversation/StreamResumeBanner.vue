<script setup lang="ts">
interface Props {
    pendingPrompt: string;
}

defineProps<Props>();

const emit = defineEmits<{
    resume: [];
    dismiss: [];
}>();
</script>

<template>
    <div class="flex flex-col gap-3 rounded-lg border border-primary/20 bg-primary/5 p-4">
        <div class="flex items-start justify-between gap-4">
            <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-foreground">
                    Continue your previous conversation?
                </span>
                <span class="line-clamp-2 text-xs text-muted-foreground">
                    "{{ pendingPrompt }}"
                </span>
            </div>
        </div>
        <div class="flex gap-2">
            <button
                class="rounded-md bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground transition hover:bg-primary/90"
                @click="emit('resume')"
            >
                Resume
            </button>
            <button
                class="rounded-md border border-border bg-background px-3 py-1.5 text-xs font-medium text-foreground transition hover:bg-muted"
                @click="emit('dismiss')"
            >
                Dismiss
            </button>
        </div>
    </div>
</template>
