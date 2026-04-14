<script setup lang="ts">
import AppContent from '@/components/ui/app/AppContent.vue';
import AppShell from '@/components/ui/app/AppShell.vue';
import AppSidebar from '@/components/ui/app/AppSidebar.vue';
import { TooltipProvider } from '@/components/ui/tooltip';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <TooltipProvider :delay-duration="300">
                <slot />
            </TooltipProvider>
        </AppContent>
    </AppShell>
</template>
