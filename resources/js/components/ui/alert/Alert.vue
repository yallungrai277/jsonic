<script setup lang="ts">
import { cva, type VariantProps } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const alertVariants = cva(
    'relative w-full rounded-lg border px-4 py-3 text-sm',
    {
        variants: {
            variant: {
                default: 'bg-background text-foreground',
                destructive: 'bg-red-50 border-red-100 text-red-600 dark:bg-red-900/20 dark:border-red-800/30 dark:text-red-400',
            },
        },
        defaultVariants: {
            variant: 'default',
        },
    },
);

defineProps<{
    class?: string;
    variant?: VariantProps<typeof alertVariants>['variant'];
}>();
</script>

<template>
    <div :class="cn(alertVariants({ variant }), props.class)">
        <slot />
    </div>
</template>