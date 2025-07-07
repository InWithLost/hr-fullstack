<template>
  <div class="min-h-screen bg-gray-100">
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
              <h1 class="text-3xl font-bold leading-tight text-gray-900">Просмотр статьи</h1>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4" v-if="post">
              <button
                @click="editPost"
                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Редактировать
              </button>
            </div>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg" v-if="post">
              <div class="px-4 py-5 sm:p-6">
                <!-- Статус публикации -->
                <div class="mb-6">
                  <span
                    :class="[
                      post.is_published
                        ? 'bg-green-100 text-green-800'
                        : 'bg-yellow-100 text-yellow-800',
                      'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium'
                    ]"
                  >
                    {{ post.is_published ? 'Опубликовано' : 'Черновик' }}
                  </span>
                </div>

                <!-- Изображение -->
                <div class="mb-6" v-if="post.image_url">
                  <img :src="post.image_url" alt="Изображение статьи" class="w-full h-64 object-cover rounded-lg" />
                </div>

                <!-- Заголовок -->
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ post.title }}</h2>

                <!-- Slug -->
                <div class="mb-4">
                  <span class="text-sm text-gray-500">Slug: </span>
                  <span class="text-sm text-gray-900">{{ post.slug }}</span>
                </div>

                <!-- Отрывок -->
                <div class="mb-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Отрывок</h3>
                  <div class="prose max-w-none">
                    {{ post.excerpt }}
                  </div>
                </div>

                <!-- Содержание -->
                <div>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Содержание</h3>
                  <div class="prose max-w-none" v-html="post.content"></div>
                </div>

                <!-- Мета-информация -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Создано</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDate(post.created_at) }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Обновлено</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDate(post.updated_at) }}</dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'

const route = useRoute()
const router = useRouter()
const notificationStore = useNotification()

const post = ref(null)

// Загрузка данных статьи
const loadPost = async () => {
  try {
    const response = await fetch(`http://localhost:8000/api/admin/blog/${route.params.id}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      post.value = await response.json()
    } else {
      notificationStore.error('Ошибка при загрузке статьи')
      router.push('/admin/blog')
    }
  } catch (error) {
    console.error('Ошибка при загрузке статьи:', error)
    notificationStore.error('Ошибка при загрузке статьи')
    router.push('/admin/blog')
  }
}

const editPost = () => {
  router.push(`/admin/blog/${route.params.id}/edit`)
}

const formatDate = (date) => {
  return new Date(date).toLocaleString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadPost()
})
</script> 