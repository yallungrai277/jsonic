<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import SpotifyController from '@/actions/App/Http/Controllers/App/SpotifyController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { useAxios } from '@/composables/useAxios';

const processing = ref(false);
const form = useForm({
    title: '',
    artist: '',
});

const submit = () => {
    processing.value = true;
    useAxios(
        SpotifyController.searchTracks.form().action,
        { method: SpotifyController.searchTracks.form().method },
        form.data(),
        {
            onSuccess: (response) => {
                console.log(response);
            },
            onError: (error: any) => {
                if (error.response?.data?.errors) {
                    form.errors = error.response.data.errors;
                } else {
                    console.error('An unexpected error occurred:', error);
                }
            },
            onFinish: () => {
                processing.value = false;
            },
        },
    );
};
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-4">
            <div class="grid gap-2">
                <Label for="title">Track</Label>
                <Input
                    id="title"
                    type="text"
                    required
                    autocomplete="title"
                    name="title"
                    placeholder="E.g. Stairway to Heaven"
                    v-model="form.title"
                    @update:modelValue="form.clearErrors('title')"
                />
                <InputError :message="form.errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="artist">Artist</Label>
                <Input
                    id="artist"
                    type="artist"
                    autocomplete="artist"
                    name="artist"
                    placeholder="E.g. Led Zeppelin"
                    v-model="form.artist"
                    @update:modelValue="form.clearErrors('artist')"
                />
                <InputError :message="form.errors.artist" />
            </div>

            <Button type="submit" class="mt-2 w-1/3" :disabled="processing">
                Search for tracks
            </Button>
        </div>
    </form>
</template>
