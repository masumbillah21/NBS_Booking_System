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
  <div class="flex items-stretch justify-start relative mt-5">
      <input
        ref="root"
        type="file"
        class="cursor-pointer rounded-lg border-[1.5px] border-stroke bg-transparent font-medium outline-none transition file:mr-5 file:border-collapse file:cursor-pointer file:border-0 file:border-r file:border-solid file:border-stroke file:bg-whiter file:py-3 file:px-5 file:hover:bg-primary file:hover:bg-opacity-10 focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:file:border-form-strokedark dark:file:bg-white/30 dark:file:text-white dark:focus:border-primary"
        :accept="accept"
        @input="upload"
      />
  </div>
</template>
