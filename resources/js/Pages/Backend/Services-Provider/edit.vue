<script setup lang="ts">
import DefaultCard from '@/Components/Forms/DefaultCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
import InputGroup from '@/Components/Forms/InputGroup.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SelectGroup from '@/Components/Forms/SelectGroup.vue';
import FormFilePicker from '@/Components/Forms/FormFilePicker.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3'

const serviceProviderData: any = usePage().props.serviceProvider ?? null

const users: any = usePage().props.users ?? null
const status = [{
    id:'0',label:'inactive'
},
{
    id:'1',label:'active'
}];

const form: any = useForm({
    id: 0,
    'company_name': '',
    'email': '',
    'phone_number': '',
    'description': '',
    'logo': '',
    'address': '',
    'status': '',
    'user_id': '',
    _method: "post",
});

if (serviceProviderData !== null) {
    form.id = serviceProviderData.id
    form.company_name = serviceProviderData.company_name
    form.email = serviceProviderData.email
    form.phone_number = serviceProviderData.phone_number
    form.description = serviceProviderData.description
   
    form.address = serviceProviderData.address
    form.status = serviceProviderData.status
    form.user_id = serviceProviderData.user_id
    form._method = 'put'
}

const submit = () => {
    if (serviceProviderData !== null) {
        update();
    } else {
        create();
    }
};

const create = () => {
    form.post(route("services-provider.store"), {
        onSuccess: () => form.reset(),
    });
};

const update = () => {
    form.post(route("services-provider.update", form.id));
};
</script>

<template>
    <AuthenticatedLayout>

        <Head :title="(serviceProviderData !== null) ? 'Edit Provider' : 'Create Provider'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="(serviceProviderData !== null) ? 'Edit Provider' : 'Create Provider'" />
        <!-- Breadcrumb End -->
        <DefaultCard :cardTitle="(serviceProviderData !== null) ? 'Edit Provider' : 'Create Provider'">
            <form @submit.prevent="submit">

                <SelectGroup label="Users" :options="users" v-model="form.user_id"/>
                <InputError class="mt-2" :message="form.errors.user_id" />
                
                <InputGroup label="Company Name" class="mb-2" type="text" id="company_name" placeholder="Enter Company Name"
                    v-model="form.company_name" required autofocus autocomplete="company_name">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.company_name" />

                <InputGroup label="Email" class="mb-2" type="email" id="email" placeholder="Enter Email"
                    v-model="form.email" required autofocus autocomplete="email">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.email" />

                <InputGroup label="Phone Number" class="mb-2" type="text" id="phone_number" placeholder="Enter Phone Number"
                    v-model="form.phone_number" required autofocus autocomplete="phone_number">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.phone_number" />

                <InputGroup label="Description" class="mb-2" type="text" id="description" placeholder="Enter Description"
                    v-model="form.description" required autofocus autocomplete="description">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.description" />

                <InputGroup label="Address" class="mb-2" type="text" id="address" placeholder="Enter Address"
                    v-model="form.address" required autofocus autocomplete="address">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.address" />

                <img v-if="serviceProviderData && serviceProviderData.logo" :src="'http://127.0.0.1:8000/'+ serviceProviderData.logo" alt="">
               
                <FormFilePicker label="Upload Logo" color="success" @update:modelValue="form.logo = $event" />
                <InputError class="mt-2" :message="form.errors.logo" />

                <SelectGroup label="Status" :options="status" v-model="form.status"/>
                <InputError class="mt-2" :message="form.errors.status" />

                
                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>