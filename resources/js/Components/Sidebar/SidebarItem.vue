<script setup lang="ts">
import { ref } from 'vue'
import { useSidebarStore } from '@/stores/sidebar'
import { Link } from '@inertiajs/vue3';
import SidebarDropdown from './SidebarDropdown.vue'
import BaseIcon from '../BaseIcon.vue';

const sidebarStore = useSidebarStore()

const props = defineProps(['item', 'index'])

const currentPage = ref(props.item.label)

interface SidebarItem {
  label: string
}

const handleItemClick = () => {
  const pageName = sidebarStore.page === props.item.label ? '' : props.item.label
  sidebarStore.page = pageName

  if (props.item.children) {
    return props.item.children.some((child: SidebarItem) => sidebarStore.selected === child.label)
  }
}
</script>

<template>
  <li>
    <Link
      :href="item.route != '#' ? route(item.route) : item.route"
      class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
      @click.prevent="handleItemClick"
      :class="{
        'bg-graydark dark:bg-meta-4': sidebarStore.page === item.label
      }"
    >
      <BaseIcon :path="item.icon"/>

      {{ item.label }}
      
      <BaseIcon class="absolute right-4 top-1/2 -translate-y-1/2 fill-current"
         v-if="item.children" path="fas fa-chevron-down"/>
    </Link>

    <!-- Dropdown Menu Start -->
    <div class="translate transform overflow-hidden" v-show="sidebarStore.page === item.label">
      <SidebarDropdown
        v-if="item.children"
        :items="item.children"
        :currentPage="currentPage"
        :page="item.label"
      />
      <!-- Dropdown Menu End -->
    </div>
  </li>
</template>
