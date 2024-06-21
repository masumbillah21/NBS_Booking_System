<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3';
import BaseIcon from '../BaseIcon.vue';

const target = ref(null)
const dropdownOpen = ref(false)

const user: any = usePage().props.auth.user;
const profile: any = usePage().props.auth.profile;
const url: any = usePage().props.urls

onClickOutside(target, () => {
  dropdownOpen.value = false
})

const form = useForm({
})

const logout = () => {
  form.post(route('logout'))
}
</script>

<template>
  <div class="relative" ref="target">
    <a
      class="flex items-center gap-4"
      href="#"
      @click.prevent="dropdownOpen = !dropdownOpen"
    >
      <span class="hidden text-right lg:block">
        <span class="block text-sm font-medium text-black dark:text-white">{{ user.name }}</span>
        <span class="block text-xs font-medium">{{ user.designation }}</span>
      </span>

      <span class="h-12 w-12 rounded-full">
        <img v-if="profile && profile.photo" :src="url.storeUrl + profile.photo" alt="User" class="rounded-full" />
        <img v-else src="@/assets/images/user/user-01.png" alt="User" />
      </span>
      <BaseIcon path="fas fa-chevron-down" />
    </a>

    <!-- Dropdown Start -->
    <div
      v-show="dropdownOpen"
      class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
    >
      <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-7.5 dark:border-strokedark">
        <li>
          <Link
            href="/profile"
            class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
          >
          <BaseIcon path="fas fa-user" />
            My Profile
          </Link>
        </li>
        
        <li>
          <Link
            href="/pages/settings"
            class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
          >
          <BaseIcon path="fas fa-cog" />
            Account Settings
          </Link>
        </li>
      </ul>
      <button
        @click="logout()"
        class="flex items-center gap-3.5 py-4 px-6 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
      >
        <BaseIcon path="fas fa-sign-out-alt" />
        Log Out
      </button>
    </div>
    <!-- Dropdown End -->
  </div>
</template>
