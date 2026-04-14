<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    message?: string | Array<string>;
}>();

const errorMessage = computed(() => {
    if (Array.isArray(props.message) && props.message.length > 0) {
        return props.message[0];
    }
    return props.message;
});
</script>

<template>
    <div v-show="errorMessage" class="input-error">
        <p class="text-sm text-red-600 dark:text-red-500">
            {{ errorMessage }}
        </p>
    </div>
</template>
