<template>
  <div>
    <div
      class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
      :class="{ 'border-indigo-500': isDragging }"
      @dragenter.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @dragover.prevent
      @drop.prevent="handleDrop"
    >
      <div class="space-y-1 text-center">
        <svg
          class="mx-auto h-12 w-12 text-gray-400"
          stroke="currentColor"
          fill="none"
          viewBox="0 0 48 48"
          aria-hidden="true"
        >
          <path
            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
        <div class="flex text-sm text-gray-600">
          <label
            for="file-upload"
            class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
          >
            <span>Загрузить файл</span>
            <input
              id="file-upload"
              type="file"
              class="sr-only"
              accept="image/*"
              @change="handleFileSelect"
            />
          </label>
          <p class="pl-1">или перетащите</p>
        </div>
        <p class="text-xs text-gray-500">
          PNG, JPG, GIF до 2MB
        </p>
      </div>
    </div>

    <!-- Предпросмотр изображения -->
    <div v-if="imageUrl" class="mt-4">
      <div class="relative inline-block">
        <img
          :src="imageUrl"
          class="h-32 w-32 object-cover rounded-lg"
          alt="Preview"
        />
        <button
          type="button"
          class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none"
          @click="removeImage"
        >
          <XMarkIcon class="h-4 w-4" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/solid'
import { useNotification } from '~/stores/notification'

const props = defineProps({
  value: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:value', 'upload'])
const notificationStore = useNotification()

const isDragging = ref(false)
const imageUrl = ref(props.value)
const imagePath = ref('')
const fileId = ref(null)

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    uploadFile(file)
  }
}

const handleDrop = (event) => {
  isDragging.value = false
  const file = event.dataTransfer.files[0]
  if (file) {
    uploadFile(file)
  }
}

const uploadFile = async (file) => {
  try {
    const formData = new FormData()
    formData.append('file', file)

    const config = useRuntimeConfig()
    const response = await fetch(`${config.public.apiBase}/api/v1/admin/files/upload`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: formData
    })

    if (response.ok) {
      const data = await response.json()
      imageUrl.value = data.url
      imagePath.value = data.path
      fileId.value = data.id
      emit('update:value', data.url)
      emit('upload', data)
      notificationStore.success('Изображение успешно загружено')
    } else {
      const error = await response.json()
      notificationStore.error(error.message || 'Ошибка при загрузке изображения')
    }
  } catch (error) {
    console.error('Ошибка при загрузке изображения:', error)
    notificationStore.error('Ошибка при загрузке изображения')
  }
}

const removeImage = async () => {
  if (imagePath.value) {
    try {
      const config = useRuntimeConfig()
      const response = await fetch(`${config.public.apiBase}/api/v1/files/${fileId.value}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({ path: imagePath.value })
      })

      if (response.ok) {
        imageUrl.value = ''
        imagePath.value = ''
        fileId.value = null
        emit('update:value', '')
        emit('upload', null)
        notificationStore.success('Изображение успешно удалено')
      } else {
        const error = await response.json()
        notificationStore.error(error.message || 'Ошибка при удалении изображения')
      }
    } catch (error) {
      console.error('Ошибка при удалении изображения:', error)
      notificationStore.error('Ошибка при удалении изображения')
    }
  } else {
    imageUrl.value = ''
    emit('update:value', '')
    emit('upload', null)
  }
}
</script> 