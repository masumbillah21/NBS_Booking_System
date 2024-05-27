<script setup lang="ts">
    import { computed, ref, reactive } from 'vue'
    import DefaultCard from '@/Components/Forms/DefaultCard.vue';

    import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
    import InputGroup from '@/Components/Forms/InputGroup.vue';
    import InputError from '@/Components/InputError.vue';
    import { Head, useForm, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue';

    const roleData: any = usePage().props.role ?? null
    const permissionList: any = usePage().props.permissionList ?? null


    const form: any = useForm({
        id: 0,
        name: "",
        permissions: [],
        _method: "post",
    });


    if (roleData !== null) {
        form.id = roleData.id
        form.name = roleData.name
        form.permissions = roleData.permissions.map((permission: any) => permission.id);
        form._method = 'put'
    }

    const submit = () => {
        if (roleData !== null) {
            update();
        } else {
            create();
        }
    };


    const create = () => {
        form.post(route("roles.store"), {
            onSuccess: () => form.reset(),
        });
    };


    const update = () => {
        console.log(form)
        form.post(route("roles.update", form.id));
    };
</script>

<template>
    <AuthenticatedLayout>

        <Head :title="(roleData !== null) ? 'Edit Role' : 'Create Role'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="(roleData !== null) ? 'Edit role' : 'Create role'" />
        <!-- Breadcrumb End -->
        <DefaultCard :cardTitle="(roleData !== null) ? 'Edit role' : 'Create role'">
            <form @submit.prevent="submit">
                <InputGroup label="Name" class="mb-2" type="text" id="name" placeholder="Enter role name"
                    v-model="form.name" required autofocus autocomplete="username">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.name" />
                
                <FormCheckRadioGroup v-model="form.permissions" name="permissions" :options="permissionList" />

                <InputError class="mt-2" :message="form.errors.permission" />
                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>
