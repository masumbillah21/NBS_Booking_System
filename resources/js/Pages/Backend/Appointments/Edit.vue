<script setup lang="ts">
    import { computed, ref, reactive, watch } from 'vue'
    import DefaultCard from '@/Components/Forms/DefaultCard.vue';
    import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
    import InputGroup from '@/Components/Forms/InputGroup.vue';
    import InputError from '@/Components/InputError.vue';
    import { Head, useForm, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import SelectGroup from '@/Components/Forms/SelectGroup.vue';
    import DatePicker from '@/Components/Forms/DatePicker/DatePickerOne.vue';
    import axios from 'axios';


    const services = ref([]);
    const users: any = usePage().props.users ?? null;
    const appointmentData: any = usePage().props.appointment ?? null;
    const statusOptions: any = usePage().props.status ?? null;
    const service: any = usePage().props.service ?? null;
    const appointmentsTimeSlot: any = usePage().props.appointmentsTimeSlot ?? null;
    services.value = service

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

    const fetchServices = async (userId) => {
        try {
            const response = await axios.get(route('services.by.user', { userId }));
            services.value = response.data;
        } catch (error) {
            console.error("There was an error fetching the services!", error);
        }
    };

    watch(() => form.staff_id, (newStaffId) => {
        if (newStaffId) {
            fetchServices(newStaffId);
        } else {
            services.value = [];
        }
    });

</script>

<template>
     <AuthenticatedLayout>
        <Head :title="(appointmentData !== null) ? 'Edit Appointment' : 'Create Appointment'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="(appointmentData !== null) ? 'Edit Appointment' : 'Create Appointment'" />
        <!-- Breadcrumb End -->
        <DefaultCard cardTitle="Create Appointment">
            <form @submit.prevent="submit">
                <InputGroup label="Appointment Date" class="mb-2" type="date" id="appointment_date" placeholder="Enter Appointment Date"
                    v-model="form.appointment_date" required autofocus autocomplete="appointment_date">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.appointment_date" />

                <SelectGroup label="Appointment Time" class="mb-2" id="appointment_time"
                v-model="form.appointment_time" :options="appointmentsTimeSlot" placeholder="Enter Appointment Time"/>
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

                <SelectGroup v-if="form.status" label="Status Name" class="mb-2" id="status" 
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

