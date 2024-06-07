<script setup lang="ts">
import { computed, ref, reactive } from 'vue';
import DefaultCard from '@/Components/Forms/DefaultCard.vue';
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
import InputGroup from '@/Components/Forms/InputGroup.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const appointmentId: any = usePage().props.customerFeedback;

const form: any = useForm({
    appointment_id: appointmentId.id,
    rating: null,
    comments: '',
});

const submit = () => {
    form.post(route('feedback.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Submit Feedback'" />
        <BreadcrumbDefault :pageTitle="'Submit Feedback'" />
        <DefaultCard cardTitle="Submit Feedback">
            <form @submit.prevent="submit">
                <InputGroup label="Rating" class="mb-2" type="number" min="1" max="5" id="rating" placeholder="Enter Rating"
                    v-model="form.rating" required autofocus autocomplete="rating">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.rating" />

                <InputGroup label="Comments" class="mb-2" type="textarea" id="comments" placeholder="Enter Comments"
                    v-model="form.comments" autofocus autocomplete="comments">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.comments" />

                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Submit</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Submitted.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>
