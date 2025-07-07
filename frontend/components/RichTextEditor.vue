<template>
  <div>
    <Editor
      v-model="content"
      :init="customConfig"
      @onChange="handleChange"
    />
  </div>
</template>

<script setup>
import Editor from '@tinymce/tinymce-vue'
import { ref, watch } from 'vue'
import { useNotification } from '~/stores/notification'
import editorConfig from '~/config/tinymce'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])
const notificationStore = useNotification()
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
  emit('update:modelValue', e.target.getContent())
}

// Переопределяем конфигурацию для обработки загрузки изображений
const customConfig = {
  ...editorConfig,
  images_upload_handler: async (blobInfo, progress) => {
    try {
      const formData = new FormData()
      formData.append('file', blobInfo.blob(), blobInfo.filename())

      const response = await fetch('http://localhost:8000/api/admin/files/upload', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: formData
      })

      if (response.ok) {
        const data = await response.json()
        return data.url
      } else {
        const error = await response.json()
        notificationStore.error(error.message || 'Ошибка при загрузке изображения')
        throw new Error('Ошибка при загрузке изображения')
      }
    } catch (error) {
      console.error('Ошибка при загрузке изображения:', error)
      notificationStore.error('Ошибка при загрузке изображения')
      throw error
    }
  }
}
</script> 