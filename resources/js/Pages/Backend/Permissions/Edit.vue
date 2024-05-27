<script setup lang="ts">
    import DefaultCard from '@/Components/Forms/DefaultCard.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
    import InputGroup from '@/Components/Forms/InputGroup.vue';
    import InputError from '@/Components/InputError.vue';
    import { Head, useForm, usePage } from '@inertiajs/vue3'

    const permissionData: any = usePage().props.permission ?? null


    const form: any = useForm({
        id: 0,
        name: "",
        permission: "",
        _method: "post",
    });

    if (permissionData !== null) {
        form.id = permissionData.id
        form.name = permissionData.name
        form.permission = permissionData.permission
        form._method = 'put'
    }

    const submit = () => {
        if (permissionData !== null) {
            update();
        } else {
            create();
        }
    };

    const create = () => {
        form.post(route("permissions.store"), {
                onSuccess: () => form.reset(),
            });
    };

    const update = () => {
        form.post(route("permissions.update", form.id));
    };
</script>

<template>
    <AuthenticatedLayout>

        <Head :title="(permissionData !== null) ? 'Edit permission' : 'Create permission'" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault :pageTitle="(permissionData !== null) ? 'Edit permission' : 'Create permission'" />
        <!-- Breadcrumb End -->
        <DefaultCard :cardTitle="(permissionData !== null) ? 'Edit permission' : 'Create permission'">
            <form @submit.prevent="submit">
                <InputGroup label="Name" class="mb-2" type="text" id="name" placeholder="Enter permission name" v-model="form.name"
                    required autofocus autocomplete="username">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.name" />

                <InputGroup label="Permission" class="mb-2" type="text" id="permission" placeholder="Enter permission"
                    v-model="form.permission" required autofocus autocomplete="username">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.permission" />
                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>