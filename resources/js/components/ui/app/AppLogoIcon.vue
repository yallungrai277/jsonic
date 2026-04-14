<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" :class="className" v-bind="$attrs">
        <rect width="48" height="48" rx="12" fill="currentColor" fill-opacity="0.1" />
        <rect x="2" y="2" width="44" height="44" rx="10" stroke="currentColor" stroke-width="2.5" fill="none" />
        <path fill="currentColor" d="M21.5 16v12.5a3.5 3.5 0 01-7 0V26h-3v2.5a6.5 6.5 0 0013 0V16h-3z" />
        <path fill="currentColor" d="M36.5 19.5c-2.5-3.5-7.5-3.5-10 0v9c2.5 3.5 7.5 3.5 10 0l-2-2.5c-1 1.5-3.5 1.5-5 0v-4c1.5-1.5 4-1.5 5 0l2-2.5z" />
    </svg>
</template>
