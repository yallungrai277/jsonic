<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { showToast } from '@/composables/useToast';
import { store as promptSubmitUrl } from '@/routes/conversation';

type PromptSuccessPageProps = {
    streamPrompt?: string | null;
    messages?: unknown;
};

const emit = defineEmits<{
    (event: 'prompt-success', pageProps: PromptSuccessPageProps): void;
    (event: 'prompt-submit', message: string): void;
}>();

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
    submitUrl: {
        type: String,
        default: promptSubmitUrl().url
    },
    helpText: {
        type: String,
        default: ''
    },
    method: {
        type: String as () => 'post' | 'put' | 'patch',
        default: 'post'
    },
    initialValue: {
        type: String,
        default: ''
    }
});

const form = useForm({
    message: '',
});

onMounted(() => {
    if (props.initialValue) {
        form.message = props.initialValue;
    }
});

const messageIsEmpty = computed(() => {
    return form.message.trim().length === 0;
});

const canBeSubmitted = computed(() => {
    return ! props.disabled && ! form.processing && ! messageIsEmpty.value;
});

const submit = (): void => {
    if (! canBeSubmitted.value) {
        return;
    }

    const prompt = form.message.trim();

    emit('prompt-submit', prompt);

    const method = props.method as 'post' | 'put' | 'patch';
    form[method](props.submitUrl, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            form.reset('message');
            emit('prompt-success', page.props as PromptSuccessPageProps);
        },
        onError: () => {
            showToast('Unable to send your message. Please try again.', 'error');
        }
    });
};

</script>
<template>
   <div class="relative mb-2 p-4 sm:p-6 lg:px-20">
        <div class="mx-auto max-w-3xl">
            <div class="relative flex w-full flex-col rounded-3xl border border-input/70 bg-card/95 shadow-sm backdrop-blur transition-all duration-300 focus-within:ring-1 focus-within:ring-ring focus-within:shadow-md">
                <form @submit.prevent="submit">
                    <textarea
                        rows="1"
                        placeholder="Ask for a joke..."
                        class="field-sizing-content min-h-[56px] max-h-[200px] w-full resize-none border-0 bg-transparent px-5 py-4 pr-14 text-base text-foreground placeholder-muted-foreground focus:outline-none focus:ring-0 disabled:opacity-50"
                        v-model="form.message"
                        :disabled="props.disabled || form.processing"
                        @keydown.enter.exact.prevent="submit"
                    ></textarea>
                    <div class="absolute bottom-2 right-2 flex items-center justify-center">
                        <button
                            type="submit"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-sm transition duration-200 hover:scale-105 hover:opacity-90 disabled:hover:scale-100 disabled:opacity-50"
                            :disabled="!canBeSubmitted"
                        >
                            <svg v-if="form.processing" class="size-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/>
                                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3"/>
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up shrink-0"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>
                        </button>
                    </div>
                </form>
            </div>
            <div class="mt-3 flex items-center justify-between gap-2 text-xs text-muted-foreground">
                <span v-if="props.helpText" v-text="props.helpText" />
            </div>
        </div>
    </div>
</template>
