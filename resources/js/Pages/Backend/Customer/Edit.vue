<script setup lang="ts">
import { ref } from 'vue'
import DefaultCard from '@/Components/Forms/DefaultCard.vue';
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
import InputGroup from '@/Components/Forms/InputGroup.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SelectGroup from '@/Components/Forms/SelectGroup.vue';

const services: any = usePage().props.services;
const users: any = usePage().props.users;
const appointmentData: any = usePage().props.customerAppointment;
const statusOptions: any = usePage().props.status;

const form: any = useForm({
    id: '',
    client_id: '',
    staff_id: '',
    service_id: '',
    appointment_date: '',
    appointment_time: '',
    status: '',
});

if (appointmentData !== null) {
    form.id = appointmentData.id;
    form.client_id = appointmentData.client_id;
    form.staff_id = appointmentData.staff_id;
    form.service_id = appointmentData.service_id;
    form.appointment_date = appointmentData.appointment_date;
    form.appointment_time = appointmentData.appointment_time;
    form.status = appointmentData.status;
}

// Submit form
const submit = () => {
    if (appointmentData !== null) {
        update();
    }
};

// Update appointment
const update = () => {
    form.put(route("customer.update", form.id), {
        onSuccess: () => {
            form.reset(); // Reset the form after success
        },
        onError: (errors) => {
            if (errors.message) {
                alert(errors.message); // Show alert on error
            }
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Edit Customer Appointment'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="'Edit Appointment'" />
        <!-- Breadcrumb End -->
        <DefaultCard cardTitle="Edit Appointment">
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

                <!-- Use a SelectGroup for status for better UX -->
                <div class="mb-2">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <input type="text" id="status" :value="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly />
                </div>
                <InputError class="mt-2" :message="form.errors.status" />

                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing || appointmentData === null">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>
