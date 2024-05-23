<script setup lang="ts">

import { computed } from 'vue'

const props = defineProps(['id', 'label', 'type', 'placeholder', 'modelValue'])
const emit = defineEmits(['update:modelValue'])

const computedValue = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit('update:modelValue', value)
  }
})

</script>
<template>
  <div class="mb-4">
    <label :for="props.id" class="mb-2.5 block font-medium text-black dark:text-white">{{ props.label }}</label>
    <div class="relative">
      <input
        :id="props.id"
        :type="props.type"
        :placeholder="props.placeholder"
        v-model="computedValue"
        class="w-full rounded-lg border border-stroke bg-transparent py-4 pl-6 pr-10 outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary text-black dark:text-white"
      />

      <span class="absolute right-4 top-4">
        <slot></slot>
      </span>
    </div>
  </div>
</template>
