<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Prompt from '@/components/ui/conversation/Prompt.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as conversationIndex } from '@/routes/conversation';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: conversationIndex().url,
    },
];

defineProps({
    greeting: String,
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
                <div class="flex flex-1 items-center justify-center px-4 sm:px-8">
                    <div class="w-full max-w-3xl">
                        <h1 class="text-center text-2xl font-semibold tracking-tight text-foreground">
                            {{ greeting ? greeting : 'Greetings, shall we chase some chuckles?' }}
                        </h1>
                        <Prompt help-text="" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
