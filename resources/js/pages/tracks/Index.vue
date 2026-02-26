<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Form from '@/components/tracks/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as appIndex } from '@/routes/app';
import { type BreadcrumbItem } from '@/types';

// import { Button } from '@/components/ui/button';
// import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tracks',
        href: appIndex().url,
    },
];
</script>

<template>
    <Head title="Tracks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                <div
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <Form />
                </div>
                <div
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    Upload an audio.
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
                List goes here.
            </div>
        </div>
    </AppLayout>
</template>
