<script setup lang="ts">
import { ref, reactive } from 'vue';
import DefaultCard from '@/Components/Forms/DefaultCard.vue';
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
import InputGroup from '@/Components/Forms/InputGroup.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SelectGroup from '@/Components/Forms/SelectGroup.vue';

// Fetch data from the page's props
const services: any = usePage().props.services ?? [];
const users: any = usePage().props.users ?? [];
const appointmentData: any = usePage().props.appointment ?? null;

// Initialize the form with default values or the existing appointment data
const form = useForm({
    id: appointmentData?.id || '',
    client_id: appointmentData?.client_id || '',
    staff_id: appointmentData?.staff_id || '',
    service_id: appointmentData?.service_id || '',
    appointment_date: appointmentData?.appointment_date || '',
    appointment_time: appointmentData?.appointment_time || '',
    status: 'pending', // Always set status to 'pending' for reappointments
});

// Define the submit handler
const submit = () => {
    // Post the form data to the reappointment route
    form.post(route("customer.reappointment.store", form.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Reappoint Appointment'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="'Reappoint Appointment'" />
        <!-- Breadcrumb End -->
        <DefaultCard cardTitle="Reappoint Appointment">
            <form @submit.prevent="submit">
                <InputGroup label="Appointment Date" class="mb-2" type="date" id="appointment_date" placeholder="Enter Appointment Date"
                    v-model="form.appointment_date" required autofocus autocomplete="appointment_date">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.appointment_date" />

                <InputGroup label="Appointment Time" class="mb-2" type="time" id="appointment_time" placeholder="Enter Appointment Time"
                    v-model="form.appointment_time" required autofocus autocomplete="appointment_time">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.appointment_time" />

                <SelectGroup label="Client Name" class="mb-2" id="client"
                    v-model="form.client_id" :options="users" />
                <InputError class="mt-2" :message="form.errors.client_id" />

                <SelectGroup label="Staff Name" class="mb-2" id="staff"
                    v-model="form.staff_id" :options="users" />
                <InputError class="mt-2" :message="form.errors.staff_id" />

                <SelectGroup label="Service Name" class="mb-2" id="service"
                    v-model="form.service_id" :options="services" />
                <InputError class="mt-2" :message="form.errors.service_id" />

                <!-- Display the status as a read-only input -->
                <div class="mb-2">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <input type="text" id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly />
                </div>
                <InputError class="mt-2" :message="form.errors.status" />

                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Reappoint</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>
