<script setup lang="ts">
    import { computed, ref, reactive } from 'vue'
    import DefaultCard from '@/Components/Forms/DefaultCard.vue';
    import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
    import InputGroup from '@/Components/Forms/InputGroup.vue';
    import InputError from '@/Components/InputError.vue';
    import { Head, useForm, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import SelectGroup from '@/Components/Forms/SelectGroup.vue';


    const serviceData: any = usePage().props.service || {};
    const categories: any = usePage().props.categories || [];
    const providers: any = usePage().props.providers || [];

    const form: any = useForm({
        id: serviceData.id || 0,
        service_name: serviceData.service_name || "",
        description: serviceData.description || "",
        duration: serviceData.duration || "",
        price: serviceData.price || "",
        category_id: serviceData.category ? serviceData.category[0].id : null,
        provider_id: providers.provider_id || null,
        _method: serviceData.id ? "put" : "post",
    });

    const isUpdate = computed(() =>  form.id !== 0);

    const submit = () => {
        if (isUpdate.value) {
            update();
        } else {
            create();
        }
    };


    const create = () => {
        form.post(route("services.store"), {
            onSuccess: () => form.reset(),
        });
    };


    const update = () => {
        form.put(route("services.update", form.id), {
            onSuccess: () => form.reset(),
        });
    };
</script>

<template>
     <AuthenticatedLayout>
        <Head title="Create Services" />
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault pageTitle="Create Services" />
        <!-- Breadcrumb End -->
        <DefaultCard cardTitle="Create Service">
            <form @submit.prevent="submit">
                <InputGroup label="Service Name" class="mb-2" type="text" id="service_name" placeholder="Enter service name"
                    v-model="form.service_name" required autofocus autocomplete="service_name">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.service_name" />

                <InputGroup label="Description" class="mb-2" type="text" id="description" placeholder="Enter description"
                    v-model="form.description" required autofocus autocomplete="description">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.description" />

                <InputGroup label="Duration of Service" class="mb-2" id="duration" placeholder="Select service duration"
                    v-model="form.duration">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.duration" />

                <InputGroup label="Price of Service" class="mb-2" id="price" placeholder="Select service price"
                    v-model="form.price">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.price" />

                <SelectGroup label="Service Category" class="mb-2" id="category"
                    v-model="form.category_id" :options="categories" />
                <InputError class="mt-2" :message="form.errors.category_id" />

                <SelectGroup label="Service Provider" class="mb-2" id="provider"
                    v-model="form.provider_id" :options="providers" />
                <InputError class="mt-2" :message="form.errors.provider_id" />

                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>

