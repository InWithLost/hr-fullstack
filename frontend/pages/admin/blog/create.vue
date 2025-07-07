<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Создание статьи</h1>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <form @submit.prevent="savePost">
                  <div class="space-y-6">
                    <div>
                      <label for="title" class="block text-sm font-medium text-gray-700">Заголовок</label>
                      <input
                        type="text"
                        id="title"
                        v-model="form.title"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.title ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.title" class="mt-2 text-sm text-red-600">{{ errors.title }}</p>
                    </div>

                    <div>
                      <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                      <input
                        type="text"
                        id="slug"
                        v-model="form.slug"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.slug ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.slug" class="mt-2 text-sm text-red-600">{{ errors.slug }}</p>
                    </div>

                    <div>
                      <label for="excerpt" class="block text-sm font-medium text-gray-700">Отрывок</label>
                      <textarea
                        id="excerpt"
                        v-model="form.excerpt"
                        rows="2"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.excerpt ? 'border-red-300' : ''
                        ]"
                      ></textarea>
                      <p v-if="errors.excerpt" class="mt-2 text-sm text-red-600">{{ errors.excerpt }}</p>
                    </div>

                    <div>
                      <label for="content" class="block text-sm font-medium text-gray-700">Содержание</label>
                      <RichTextEditor
                        v-model="form.content"
                        :class="[
                          errors.content ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.content" class="mt-2 text-sm text-red-600">{{ errors.content }}</p>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700">Изображение</label>
                      <ImageUploader
                        v-model="form.image_url"
                        @upload="handleImageUpload"
                      />
                    </div>

                    <div>
                      <label class="inline-flex items-center">
                        <input
                          type="checkbox"
                          v-model="form.is_published"
                          class="form-checkbox h-4 w-4 text-indigo-600"
                        />
                        <span class="ml-2 text-sm text-gray-700">Опубликовать</span>
                      </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                      <button
                        type="button"
                        @click="goBack"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      >
                        Отмена
                      </button>
                      <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                      >
                        {{ isSubmitting ? 'Сохранение...' : 'Сохранить' }}
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import ImageUploader from '~/components/ImageUploader.vue'
import RichTextEditor from '~/components/RichTextEditor.vue'
import AdminHeader from '~/components/AdminHeader.vue'

const router = useRouter()
const notificationStore = useNotification()

const form = ref({
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  image_url: '',
  is_published: false
})

const errors = ref({})
const isSubmitting = ref(false)

const handleImageUpload = (data) => {
  form.value.image_url = data.url
}

const savePost = async () => {
  // Очищаем предыдущие ошибки
  errors.value = {}
  isSubmitting.value = true

  try {
    const response = await fetch('http://localhost:8000/api/admin/blog', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(form.value)
    })

    if (response.ok) {
      notificationStore.success('Статья успешно создана')
      router.push('/admin/blog')
    } else {
      const error = await response.json()
      if (error.errors) {
        errors.value = error.errors
      } else {
        notificationStore.error(error.message || 'Ошибка при создании статьи')
      }
    }
  } catch (error) {
    console.error('Ошибка при создании статьи:', error)
    notificationStore.error('Ошибка при создании статьи')
  } finally {
    isSubmitting.value = false
  }
}

const goBack = () => {
  router.back()
}
</script> 