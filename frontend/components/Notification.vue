<template>
  <div
    v-if="show"
    class="fixed bottom-0 right-0 m-6 p-4 rounded-lg shadow-lg"
    :class="[
      type === 'success' ? 'bg-green-500' : 'bg-red-500',
      'text-white'
    ]"
  >
    <div class="flex items-center">
      <div class="flex-shrink-0">
        <CheckCircleIcon v-if="type === 'success'" class="h-6 w-6" />
        <XCircleIcon v-else class="h-6 w-6" />
      </div>
      <div class="ml-3">
        <p class="text-sm font-medium">{{ message }}</p>
      </div>
      <div class="ml-auto pl-3">
        <button
          @click="close"
          class="inline-flex text-white focus:outline-none"
        >
          <XMarkIcon class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { CheckCircleIcon, XCircleIcon, XMarkIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  message: {
    type: String,
    required: false,
    default: ''
  },
  type: {
    type: String,
    default: 'success',
    validator: (value) => ['success', 'error'].includes(value)
  },
  duration: {
    type: Number,
    default: 3000
  }
})

const emit = defineEmits(['close'])
const show = ref(true)

const close = () => {
  show.value = false
  emit('close')
}

onMounted(() => {
  if (props.duration > 0) {
    setTimeout(() => {
      close()
    }, props.duration)
  }
})
</script> 