<script setup lang="ts">
import AppContent from '@/components/ui/app/AppContent.vue';
import AppHeader from '@/components/ui/app/AppHeader.vue';
import AppShell from '@/components/ui/app/AppShell.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell class="flex-col">
        <AppHeader :breadcrumbs="breadcrumbs" />
        <AppContent>
            <slot />
        </AppContent>
    </AppShell>
</template>
