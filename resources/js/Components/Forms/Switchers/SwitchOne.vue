<script setup lang="ts">
import { ref, watch } from 'vue'
import { defineProps, defineEmits } from 'vue'

const props = defineProps<{
  modelValue: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const switcherToggle = ref<boolean>(props.modelValue)

watch(() => props.modelValue, (newValue) => {
  switcherToggle.value = newValue
})

const toggleSwitch = () => {
  switcherToggle.value = !switcherToggle.value
  emit('update:modelValue', switcherToggle.value)
}
</script>

<template>
  <div>
    <label for="toggle1" class="flex cursor-pointer select-none items-center">
      <div class="relative">
        <input
          type="checkbox"
          id="toggle1"
          class="sr-only"
          @change="toggleSwitch"
          :checked="switcherToggle"
        />
        <div class="block h-8 w-14 rounded-full bg-meta-9 dark:bg-[#5A616B]"></div>
        <div
          :class="switcherToggle && '!right-1 !translate-x-full !bg-primary dark:!bg-white'"
          class="absolute left-1 top-1 h-6 w-6 rounded-full bg-white transition"
        ></div>
      </div>
    </label>
  </div>
</template>
