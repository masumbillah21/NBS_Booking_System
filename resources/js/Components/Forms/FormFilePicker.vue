<script setup>
import { computed, ref, watch } from 'vue'
import BaseButtonLink from '@/Components/BaseButtonLink.vue'

const props = defineProps({
  modelValue: {
    type: [Object, File, Array, String],
    default: null
  },
  label: {
    type: String,
    default: null
  },
  icon: {
    type: String,
    default: 'fas fa-cloud-upload-alt'
  },
  accept: {
    type: String,
    default: null
  },
  color: {
    type: String,
    default: 'info'
  },
  isRoundIcon: Boolean
})

const emit = defineEmits(['update:modelValue'])

const root = ref(null)

const file = ref(props.modelValue)

const url = ref(props.modelValue)

if (file.value && typeof file.value === 'object' && file.value instanceof File) {
  url.value = URL.createObjectURL(file.value)
}else{
  file.value = null
}

const showFilename = computed(() => !props.isRoundIcon && file.value)

const modelValueProp = computed(() => props.modelValue)

watch(modelValueProp, (value) => {
  file.value = value

  if (file.value) {
    url.value = URL.createObjectURL(file.value)
  } else {
    url.value = URL.createObjectURL(url.value)
  }

  if (!value) {
    root.value.input.value = null
  }
})

const upload = (event) => {
  const value = event.target.files || event.dataTransfer.files

  file.value = value[0]

  if (file.value) {
    url.value = URL.createObjectURL(file.value)
  } else {
    url.value = URL.createObjectURL(url.value)
  }

  emit('update:modelValue', file.value)
}

</script>

<template>
  <img v-if="url" :src="url" alt="" class="mb-2 max-w-52">
  <div class="flex items-stretch justify-start relative">
    <label class="inline-flex">
      <BaseButtonLink
        as="a"
        :class="{ 'w-12 h-12': isRoundIcon, 'rounded-r-none': showFilename }"
        :icon-size="isRoundIcon ? 24 : undefined"
        :label="isRoundIcon ? null : label"
        :icon="icon"
        :color="color"
        :rounded-full="isRoundIcon"
      />
      <input
        ref="root"
        type="file"
        class="absolute top-0 left-0 w-full h-full opacity-0 outline-none cursor-pointer -z-1"
        :accept="accept"
        @input="upload"
      />
    </label>
    <div
      v-if="showFilename"
      class="px-4 py-2 bg-gray-100 dark:bg-slate-800 border-gray-200 dark:border-slate-700 border rounded-r"
    >
      <span class="text-ellipsis line-clamp-1">
        {{ file.name }}
      </span>
    </div>
  </div>
</template>