<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue'
import DefaultCard from '@/Components/Forms/DefaultCard.vue'
import General from './Partials/General.vue';
import Payment from './Partials/Payment.vue';

const tabIndex = ref(0)

</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault pageTitle="Settings" />
        <!-- Breadcrumb End -->
        <DefaultCard cardTitle="Settings">
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                <li class="me-2">
                    <a href="#" @click.prevent="tabIndex = 0" class="inline-block p-4 bg-gray-100 rounded-t-lg dark:bg-gray-800" :class="tabIndex === 0 ? 'active text-blue-600 dark:text-blue-500' : ''">General</a>
                </li>
                <li class="me-2">
                    <a href="#" @click.prevent="tabIndex = 1" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300" :class="tabIndex === 1 ? 'active text-blue-600 dark:text-blue-500' : ''">Payment</a>
                </li>
            </ul>

            <div class="p-4">
                <div v-if="tabIndex === 0">
                    <General />
                </div>
                <div v-else-if="tabIndex === 1">
                    <Payment />
                </div>
            </div>

        </DefaultCard>
    </AuthenticatedLayout>
</template>
