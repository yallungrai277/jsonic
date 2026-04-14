<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/ui/app/AppLogoIcon.vue';
import { login, register } from '@/routes';
import { index as conversationIndex } from '@/routes/conversation';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background font-sans text-foreground selection:bg-primary/20">
        <!-- Navigation -->
        <header class="absolute top-0 z-50 w-full px-6 py-6 lg:px-12">
            <nav class="mx-auto flex max-w-7xl items-center justify-between">
                <div class="flex items-center gap-2">
                    <AppLogoIcon class="h-8 w-8 text-primary" />
                    <span class="hidden text-xl font-bold tracking-tight sm:inline-block">jChat</span>
                </div>

                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="conversationIndex()"
                        class="text-sm font-medium transition-colors duration-200 hover:text-primary"
                    >
                        Go to Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()" class="text-sm font-medium transition-colors duration-200 hover:text-primary"> Log in </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="inline-flex h-9 items-center justify-center rounded-full bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                        >
                            Get Started Free
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="relative flex flex-1 flex-col items-center justify-center overflow-hidden py-32 lg:py-48">
            <!-- Background Gradients -->
            <div class="pointer-events-none absolute inset-0 z-0 flex items-center justify-center overflow-hidden">
                <div class="absolute -top-[10%] -left-[10%] h-[50vh] w-[50vh] animate-pulse rounded-full bg-primary/20 blur-[100px]"></div>
                <div
                    class="absolute -right-[10%] bottom-[10%] h-[40vh] w-[40vh] animate-pulse rounded-full bg-chart-2/20 blur-[100px]"
                    style="animation-delay: 2s"
                ></div>
                <div
                    class="absolute bottom-[20%] left-[20%] h-[30vh] w-[30vh] animate-pulse rounded-full bg-chart-5/10 blur-[80px]"
                    style="animation-delay: 4s"
                ></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 mx-auto w-full max-w-7xl px-6 text-center lg:px-8">
                <div class="mx-auto max-w-3xl">
                    <div
                        class="mb-4 inline-flex items-center rounded-full border border-primary/30 bg-primary/10 px-3 py-1 text-sm text-primary shadow-sm backdrop-blur-md"
                    >
                        <span class="mr-2 flex h-2 w-2 animate-ping rounded-full bg-primary"></span>
                        <span class="font-medium">The funniest chat app on the web</span>
                    </div>

                    <h1 class="mb-6 text-5xl font-extrabold tracking-tight text-foreground sm:text-6xl lg:text-7xl">
                        Laugh loudly.
                        <br class="hidden sm:inline" />
                        <span class="bg-gradient-to-r from-primary to-chart-2 bg-clip-text text-transparent"> Chat endlessly. </span>
                    </h1>

                    <p class="mx-auto mb-10 max-w-2xl text-lg leading-relaxed text-muted-foreground sm:text-xl">
                        Join the ultimate community where every conversation is packed with humor. Our AI-powered joke engine guarantees giggles,
                        chuckles, and full-blown belly laughs.
                    </p>

                    <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="conversationIndex()"
                            class="inline-flex h-12 w-full items-center justify-center rounded-full bg-primary px-8 text-base font-semibold text-primary-foreground shadow-lg transition-all hover:scale-105 hover:bg-primary/90 active:scale-95 sm:w-auto"
                        >
                            Open Web App
                        </Link>
                        <template v-else>
                            <Link
                                :href="register()"
                                class="inline-flex h-12 w-full items-center justify-center rounded-full bg-primary px-8 text-base font-semibold text-primary-foreground shadow-lg transition-all hover:scale-105 hover:bg-primary/90 active:scale-95 sm:w-auto"
                            >
                                Start Laughing Now
                            </Link>
                            <Link
                                :href="login()"
                                class="inline-flex h-12 w-full items-center justify-center rounded-full border-2 border-border bg-background px-8 text-base font-semibold transition-all hover:border-primary/50 hover:bg-muted sm:w-auto"
                            >
                                Log back in
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
