<script setup lang="ts">
import { ref, computed } from 'vue'

const isOptionSelected = ref<boolean>(false)

const changeTextColor = () => {
  isOptionSelected.value = true
}

interface Option {
  id: string | number;
  label: string;
}
const props = defineProps<{
  modelValue: string;
  label: string;
  options: Option[];
}>();


const emit = defineEmits(['update:modelValue'])

const computedValue = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit('update:modelValue', value)
  }
})
</script>

<template>
  <div class="mb-4.5">
    <label class="mb-2.5 block font-medium text-black dark:text-white"> {{ label }} </label>

    <div class="relative z-20 bg-transparent dark:bg-form-input">
      <select
        v-model="computedValue"
        class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 px-5 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
        :class="{ 'text-black dark:text-white': isOptionSelected }"
        @change="changeTextColor"
      >
        <option value="" disabled selected>Select one...</option>
        <option v-for="(option, index) in options" :key="index" :value="option.id">{{ option.label }}</option>
      </select>
    </div>
  </div>
</template>
