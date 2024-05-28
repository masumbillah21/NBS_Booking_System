<script setup>
import DataStatsOne from '@/Components/DataStats/DataStatsOne.vue'
import ChartOne from '@/Components/Charts/ChartOne.vue'
import ChartThree from '@/Components/Charts/ChartThree.vue'
import ChartTwo from '@/Components/Charts/ChartTwo.vue'
import ChatCard from '@/Components/ChatCard.vue'
import MapOne from '@/Components/Maps/MapOne.vue'
import TableOne from '@/Components/Tables/TableOne.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue'
import DefaultCard from '@/Components/Forms/DefaultCard.vue'
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <!-- Breadcrumb Start -->
        <BreadcrumbDefault pageTitle="Settings" />
        <!-- Breadcrumb End -->
        <DefaultCard cardTitle="Settings">
            Settings form
        </DefaultCard>
    </AuthenticatedLayout>
</template>
