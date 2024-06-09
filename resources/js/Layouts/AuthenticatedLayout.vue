<script setup>
import { ref } from 'vue';
import HeaderArea from '@/Components/Header/HeaderArea.vue'
import SidebarArea from '@/Components/Sidebar/SidebarArea.vue'
import { Head, usePage } from '@inertiajs/vue3';
import { getSettings } from '@/utils/settings';

const showingNavigationDropdown = ref(false);
const urls = usePage().props.urls
</script>

<template>

  <Head v-if="getSettings('site_favicon')">
    <link rel="icon" type="image/png" :href="urls.storeUrl + getSettings('site_favicon')" />
  </Head>
    <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <!-- ===== Sidebar Start ===== -->
    <SidebarArea />
    <!-- ===== Sidebar End ===== -->

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <!-- ===== Header Start ===== -->
      <HeaderArea />
      <!-- ===== Header End ===== -->

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <slot></slot>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
  </div>
  <!-- ===== Page Wrapper End ===== -->
</template>
