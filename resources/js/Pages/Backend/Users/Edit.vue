<script setup lang="ts">
    import { computed, ref, reactive } from 'vue'
    import DefaultCard from '@/Components/Forms/DefaultCard.vue';

    import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
    import InputGroup from '@/Components/Forms/InputGroup.vue';
    import InputError from '@/Components/InputError.vue';
    import { Head, useForm, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue';
    import SelectGroup from '@/Components/Forms/SelectGroup.vue';

    const roles: any = usePage().props.roles ?? null
    const userData: any = usePage().props.userData ?? null

    const status = {
        0: "Inactive",
        1: "Active",
    }

    const form: any = useForm({
        id: 0,
        name: "",
        email: "",
        password: "",
        designation: "",
        role: "",
        status: "",
        _method: "post",
    });


    if (userData !== null) {
        form.id = userData.id
        form.name = userData.name
        form.email = userData.email
        form.designation = userData.designation
        form.role = userData.roles[0].id
        form.status = userData.status
        form._method = 'put'
    }

    const submit = () => {
        if (userData !== null) {
            update();
        } else {
            create();
        }
    };


    const create = () => {
        form.post(route("users.store"), {
            onSuccess: () => form.reset(),
        });
    };


    const update = () => {
        console.log(form)
        form.post(route("users.update", form.id));
    };
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="(userData !== null) ? 'Edit User' : 'Create User'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="(userData !== null) ? 'Edit User' : 'Create User'" />
        <!-- Breadcrumb End -->
        <DefaultCard :cardTitle="(userData !== null) ? 'Edit User' : 'Create User'">
            <form @submit.prevent="submit">
                <InputGroup label="Name" class="mb-2" type="text" id="name" placeholder="Enter name"
                    v-model="form.name" required autofocus autocomplete="username">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.name" />

                <InputGroup label="Email" class="mb-2" type="email" id="email" placeholder="Enter email"
                    v-model="form.email" required autofocus autocomplete="email">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.email" />


                <InputGroup label="Designation" class="mb-2" type="text" id="designation" placeholder="Enter designation"
                    v-model="form.designation" required autofocus autocomplete="designation">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.designation" />
                
                <InputGroup label="Password" class="mb-2" type="password" id="password" placeholder="Enter password"
                    v-model="form.password" required autofocus>
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.password" />

                <SelectGroup label="Role" :options="roles" v-model="form.role"/>
                <InputError class="mt-2" :message="form.errors.role" />

                <FormCheckRadioGroup label="Status" v-model="form.status" name="for_nav" type="radio"
                :options="status" />
                <InputError class="mt-2" :message="form.errors.status" />

                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>
