<template>
  <div>
    <textarea
      v-model="content"
      class="w-full h-40 p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
      @input="handleChange"
    ></textarea>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])
const content = ref(props.modelValue)

// Отслеживаем изменения в родительском компоненте
watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue
  }
})

// Отслеживаем изменения в редакторе
watch(content, (newValue) => {
  emit('update:modelValue', newValue)
})

const handleChange = (e) => {
  emit('update:modelValue', e.target.value)
}
</script>
