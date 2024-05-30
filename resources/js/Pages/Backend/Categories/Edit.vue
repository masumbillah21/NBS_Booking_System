<script setup lang="ts">
    import { computed, ref, reactive } from 'vue'
    import DefaultCard from '@/Components/Forms/DefaultCard.vue';
    import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
    import InputGroup from '@/Components/Forms/InputGroup.vue';
    import InputError from '@/Components/InputError.vue';
    import { Head, useForm, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import SelectGroup from '@/Components/Forms/SelectGroup.vue';


    const categoryData: any = usePage().props.category || {};
    const categories: any = usePage().props.categories || [];

    const form: any = useForm({
        id: categoryData.id || 0,
        category_name: categoryData.category_name || "",
        parent_id: categoryData.parent_id || null,
        description: categoryData.description || "",
        _method: categoryData.id ? "put" : "post",
        // id: 0,
        // category_name: "",
        // parent_id: "",
        // description: "",
        // _method: "post",
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
        form.post(route("categories.store"), {
            onSuccess: () => form.reset(),
        });
    };


    const update = () => {
        form.put(route("categories.update", form.id), {
            onSuccess: () => form.reset(),
        });
    };
</script>

<template>
     <AuthenticatedLayout>
        <DefaultCard cardTitle="Create Category">
            
            <form @submit.prevent="submit">
                <InputGroup label="Category Name" class="mb-2" type="text" id="category_name" placeholder="Enter category name"
                    v-model="form.category_name" required autofocus autocomplete="category_name">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.category_name" />

                <SelectGroup label="Parent Category" class="mb-2" id="parent_id" placeholder="Select parent category"
                    v-model="form.parent_id" :options="categories" />
                <InputError class="mt-2" :message="form.errors.parent_id" />

                <InputGroup label="Description" class="mb-2" type="text" id="description" placeholder="Enter description"
                    v-model="form.description" required autofocus autocomplete="description">
                </InputGroup>
                <InputError class="mt-2" :message="form.errors.description" />

                <button type="submit"
                    class="mt-3 cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                    :disabled="form.processing">Save</button>
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
            </form>
        </DefaultCard>
    </AuthenticatedLayout>
</template>

