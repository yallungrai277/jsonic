<script setup lang="ts">
type MessageRole = 'user' | 'assistant';

interface Props {
    role: MessageRole;
    content: string;
}

defineProps<Props>();
</script>

<template>
    <div :class="role === 'user' ? 'flex w-full justify-end' : 'flex w-full'">
        <div
            class="text-sm"
            :class="
                role === 'user'
                    ? 'flex max-w-[82%] flex-col gap-2'
                    : 'max-w-[86%] overflow-hidden px-1 py-1'
            "
        >
            <div
                :class="
                    role === 'user'
                        ? 'rounded-[1.125rem] rounded-tr-sm bg-primary/95 px-5 py-2 text-primary-foreground shadow-sm'
                        : 'rounded-[1.125rem] text-foreground'
                "
            >
                {{ content }}
            </div>
        </div>
    </div>
</template>
