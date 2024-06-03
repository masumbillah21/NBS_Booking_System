<script setup lang="ts">
    import { computed, ref, reactive } from 'vue'
    import DefaultCard from '@/Components/Forms/DefaultCard.vue';
    import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
    import InputGroup from '@/Components/Forms/InputGroup.vue';
    import InputError from '@/Components/InputError.vue';
    import { Head, useForm, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import SelectGroup from '@/Components/Forms/SelectGroup.vue';


    const services: any = usePage().props.services ?? null;
    const users: any = usePage().props.users ?? null;
    const appointmentData: any = usePage().props.appointment ?? null;
    const statusOptions: any = usePage().props.status ?? null;

    const form: any = useForm({
        id: '',
        client_id: '',
        staff_id:  '',
        service_id: '',
        appointment_date: '',
        appointment_time: '',
        status: '',
    });


    if (appointmentData !== null) {
        form.id = appointmentData.id 
        form.client_id = appointmentData.client_id
        form.staff_id = appointmentData.staff_id
        form.service_id = appointmentData.service_id
        form.appointment_date = appointmentData.appointment_date
        form.appointment_time = appointmentData.appointment_time
        form.status = appointmentData.status
    }


    console.log(form.appointment_date)
    const submit = () => {
        if (appointmentData !== null) {
            update();
        } else {
            create();
        }
    };


    const create = () => {
        form.post(route("appointments.store"), {
            onSuccess: () => form.reset(),
        });
    };


    const update = () => {
        form.put(route("appointments.update", form.id), {
            onSuccess: () => form.reset(),
        });
    };

</script>

<template>
     <AuthenticatedLayout>
        <Head :title="(appointmentData !== null) ? 'Edit Service' : 'Create Service'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="(appointmentData !== null) ? 'Edit Service' : 'Create Service'" />
        <!-- Breadcrumb End -->
        <DefaultCard cardTitle="Create Service">
            <form @submit.prevent="submit">
                <InputGroup label="Appointment Date" class="mb-2" type="date" id="appointment_date" placeholder="Enter Appointment Date"
                    v-model="form.appointment_date" required autofocus autocomplete="appointment_date">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.appointment_date" />

                <InputGroup label="Appointment Time" class="mb-2" type="time" id="appointment_time" placeholder="Enter Appointment Time"
                    v-model="form.appointment_time" required autofocus autocomplete="appointment_time">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.appointment_date" />


                <SelectGroup label="Client Name" class="mb-2" id="client"
                    v-model="form.client_id" :options="users" />
                <InputError class="mt-2" :message="form.errors.client_id" />

                <SelectGroup label="Staff Name" class="mb-2" id="staff"
                    v-model="form.staff_id" :options="users" />
                <InputError class="mt-2" :message="form.errors.staff_id" />

                <SelectGroup label="Service Name" class="mb-2" id="service"
                    v-model="form.service_id" :options="services" />
                <InputError class="mt-2" :message="form.errors.service_id" />

                <SelectGroup label="Status Name" class="mb-2" id="status"
                    v-model="form.status" :options="statusOptions" />
                <InputError class="mt-2" :message="form.errors.status" />
                
                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>

