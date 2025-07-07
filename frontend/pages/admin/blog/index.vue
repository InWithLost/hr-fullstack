<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление блогом</h1>
            <button
              @click="navigateToCreate"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Добавить статью
            </button>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Поиск и фильтры -->
          <div class="mt-4 space-y-4">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Поиск статей..."
              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
            />
            
            <!-- Фильтр по статусу -->
            <div class="flex flex-wrap gap-2">
              <span class="text-sm font-medium text-gray-700">Статус:</span>
              <label class="inline-flex items-center ml-2">
                <input 
                  type="checkbox" 
                  v-model="statusFilters.published"
                  class="form-checkbox h-4 w-4 text-indigo-600 rounded"
                />
                <span class="ml-2 text-sm text-gray-700">Опубликованные</span>
              </label>
              <label class="inline-flex items-center ml-2">
                <input 
                  type="checkbox" 
                  v-model="statusFilters.draft"
                  class="form-checkbox h-4 w-4 text-indigo-600 rounded"
                />
                <span class="ml-2 text-sm text-gray-700">Черновики</span>
              </label>
            </div>
          </div>

          <!-- Список статей -->
          <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Заголовок
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Отрывок
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Дата создания
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Дата публикации
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Статус
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                      <span class="sr-only">Действия</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900 hover:text-indigo-600 cursor-pointer" @click="viewPost(post.id)">
                        {{ post.title }}
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm text-gray-900">{{ post.excerpt }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ formatDate(post.created_at) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ post.is_published ? formatDate(post.updated_at) : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="[
                          post.is_published
                            ? 'bg-green-100 text-green-800'
                            : 'bg-gray-100 text-gray-800',
                          'px-2 inline-flex text-xs leading-5 font-semibold rounded-full'
                        ]"
                      >
                        {{ post.is_published ? 'Опубликована' : 'Черновик' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="relative action-menu-container">
                        <!-- Иконка троеточия -->
                        <button 
                          @click="toggleActionMenu(post.id)" 
                          class="p-1 rounded-full hover:bg-gray-200 transition-colors"
                          title="Действия"
                        >
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                          </svg>
                        </button>
                        
                        <!-- Выпадающее меню -->
                        <div 
                          v-if="activeActionMenu === post.id" 
                          class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border"
                          style="z-index: 100;"
                          :class="{
                            'bottom-full mb-2': isLastRows(post.id)
                          }"
                        >
                          <div class="py-1">
                            <button
                              @click="viewPost(post.id); activeActionMenu = null"
                              class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                              Просмотреть
                            </button>
                            <button
                              @click="editPost(post.id); activeActionMenu = null"
                              class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                              Редактировать
                            </button>
                            <button
                              @click="deletePost(post.id); activeActionMenu = null"
                              class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                            >
                              Удалить
                            </button>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                  <button 
                    @click="loadPosts(posts.current_page - 1)" 
                    :disabled="!posts.prev_page_url" 
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': !posts.prev_page_url }"
                  >
                    Назад
                  </button>
                  <button 
                    @click="loadPosts(posts.current_page + 1)" 
                    :disabled="!posts.next_page_url" 
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': !posts.next_page_url }"
                  >
                    Вперед
                  </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Показано <span class="font-medium">{{ posts.from || 0 }}</span> - <span class="font-medium">{{ posts.to || 0 }}</span> из <span class="font-medium">{{ posts.total || 0 }}</span> статей
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button 
                        @click="loadPosts(posts.current_page - 1)" 
                        :disabled="!posts.prev_page_url" 
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !posts.prev_page_url }"
                      >
                        <span class="sr-only">Предыдущая</span>
                        &lt;
                      </button>
                      <button 
                        v-for="page in getPageNumbers()" 
                        :key="page" 
                        @click="loadPosts(page)" 
                        :class="[
                          page === posts.current_page ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]"
                      >
                        {{ page }}
                      </button>
                      <button 
                        @click="loadPosts(posts.current_page + 1)" 
                        :disabled="!posts.next_page_url" 
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !posts.next_page_url }"
                      >
                        <span class="sr-only">Следующая</span>
                        &gt;
                      </button>
                    </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Модальное окно для редактирования/создания статьи -->
  <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Фоновое наложение -->
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75" @click="closeModal"></div>
      </div>

      <!-- Центрирование модального окна -->
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <!-- Содержимое модального окна -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ editingPost ? 'Редактирование статьи' : 'Создание статьи' }}
              </h3>
              <div class="mt-4">
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
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button 
            type="button" 
            @click="savePost" 
            :disabled="isSubmitting"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
          >
            {{ isSubmitting ? 'Сохранение...' : 'Сохранить' }}
          </button>
          <button 
            type="button" 
            @click="closeModal" 
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Отмена
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineAsyncComponent, watch } from 'vue'
import { useRouter } from 'vue-router'
import RichTextEditor from '~/components/RichTextEditor.vue'
import ImageUploader from '~/components/ImageUploader.vue'

definePageMeta({ middleware: 'admin' })

const router = useRouter()

const posts = ref({
  data: [],
  current_page: 1,
  prev_page_url: null,
  next_page_url: null
})

const searchQuery = ref('')
const statusFilters = ref({
  published: true,
  draft: true
})
const activeActionMenu = ref(null)
const showModal = ref(false)
const editingPost = ref(null)
const isSubmitting = ref(false)
const errors = ref({})

const form = ref({
  id: null,
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  image_url: '',
  is_published: false
})

// Функция для форматирования даты
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

// Загрузка статей
const loadPosts = async (page = 1) => {
  try {
    // В реальном приложении здесь будет API запрос
    // const response = await fetch(`http://localhost:8000/api/admin/blog?page=${page}&search=${searchQuery.value}`, {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // posts.value = await response.json()
    
    // Демо-данные
    const demoPosts = [
      {
        id: 1,
        title: 'Как подготовиться к собеседованию в медицинской сфере',
        slug: 'how-to-prepare-for-medical-interview',
        excerpt: 'Советы и рекомендации по подготовке к собеседованию в медицинской организации.',
        content: 'Собеседование в медицинской сфере имеет свои особенности. Вот несколько советов, которые помогут вам успешно пройти собеседование...',
        is_published: true,
        created_at: '2025-04-15T10:30:00Z',
        updated_at: '2025-05-01T14:45:00Z'
      },
      {
        id: 2,
        title: 'Тренды в медицинском образовании 2025',
        slug: 'medical-education-trends-2025',
        excerpt: 'Обзор современных трендов в области медицинского образования и повышения квалификации.',
        content: 'Медицинское образование постоянно развивается. В 2025 году мы наблюдаем следующие тренды...',
        is_published: true,
        created_at: '2025-03-20T09:15:00Z',
        updated_at: '2025-04-10T11:30:00Z'
      },
      {
        id: 3,
        title: 'Карьерный рост в фармацевтике',
        slug: 'career-growth-in-pharmacy',
        excerpt: 'Как построить успешную карьеру в фармацевтической отрасли.',
        content: 'Фармацевтическая отрасль предлагает множество возможностей для карьерного роста...',
        is_published: false,
        created_at: '2025-05-05T13:45:00Z',
        updated_at: '2025-05-05T13:45:00Z'
      },
      {
        id: 4,
        title: 'Баланс работы и личной жизни в медицине',
        slug: 'work-life-balance-in-medicine',
        excerpt: 'Как найти баланс между напряженной работой в медицине и личной жизнью.',
        content: 'Работа в медицине часто связана с высокими нагрузками и стрессом. Вот несколько стратегий, которые помогут вам найти баланс...',
        is_published: true,
        created_at: '2025-04-25T08:30:00Z',
        updated_at: '2025-05-10T09:15:00Z'
      }
    ]
    
    // Фильтрация по поисковому запросу и статусу
    let filteredPosts = demoPosts
    
    // Фильтрация по статусу
    filteredPosts = filteredPosts.filter(post => {
      if (post.is_published && statusFilters.value.published) return true
      if (!post.is_published && statusFilters.value.draft) return true
      return false
    })
    
    // Фильтрация по поисковому запросу
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      filteredPosts = filteredPosts.filter(post => 
        post.title.toLowerCase().includes(query) || 
        post.excerpt.toLowerCase().includes(query)
      )
    }
    
    // Имитируем ответ API с пагинацией
    const perPage = 10
    const total = filteredPosts.length
    const lastPage = Math.ceil(total / perPage)
    const from = (page - 1) * perPage + 1
    const to = Math.min(page * perPage, total)
    
    posts.value = {
      data: filteredPosts,
      current_page: page,
      per_page: perPage,
      total: total,
      last_page: lastPage,
      from: from,
      to: to,
      prev_page_url: page > 1 ? `?page=${page-1}` : null,
      next_page_url: page < lastPage ? `?page=${page+1}` : null
    }
  } catch (error) {
    console.error('Ошибка при загрузке статей:', error)
  }
}

// Функции редактирования перенесены на отдельные страницы

// Удаление статьи
const deletePost = async (post) => {
  if (!confirm('Вы уверены, что хотите удалить эту статью?')) return

  try {
    // В реальном приложении здесь будет API запрос
    // await fetch(`http://localhost:8000/api/admin/blog/${post.id}`, {
    //   method: 'DELETE',
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    
    // Для демо-режима просто показываем успешное сообщение
    alert(`Статья "${post.title}" успешно удалена!`)
    
    loadPosts(posts.value.current_page)
  } catch (error) {
    console.error('Ошибка при удалении статьи:', error)
  }
}

// Функция для генерации номеров страниц в пагинации
const getPageNumbers = () => {
  const pages = []
  const maxVisiblePages = 5
  
  if (!posts.value.last_page) return [1]
  
  if (posts.value.last_page <= maxVisiblePages) {
    for (let i = 1; i <= posts.value.last_page; i++) {
      pages.push(i)
    }
  } else {
    let startPage = Math.max(1, posts.value.current_page - Math.floor(maxVisiblePages / 2))
    let endPage = startPage + maxVisiblePages - 1
    
    if (endPage > posts.value.last_page) {
      endPage = posts.value.last_page
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
  }
  
  return pages
}

// Отслеживание изменений в поиске
watch(searchQuery, () => {
  loadPosts()
})

// Отслеживание изменений в фильтрах статуса
watch(statusFilters, () => {
  loadPosts()
}, { deep: true })

// Функция для переключения меню действий
const toggleActionMenu = (postId) => {
  if (activeActionMenu.value === postId) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = postId
  }
}

// Функция для определения, является ли статья одной из последних в списке
const isLastRows = (postId) => {
  const index = posts.value.data.findIndex(p => p.id === postId)
  return index >= posts.value.data.length - 2
}

// Функция для перехода на страницу создания статьи
const navigateToCreate = () => {
  router.push('/admin/blog/create')
}

// Функция для перехода на страницу просмотра статьи
const viewPost = (postId) => {
  router.push(`/admin/blog/${postId}/`)
}

// Функция для перехода на страницу редактирования статьи
const editPost = (postId) => {
  router.push(`/admin/blog/${postId}/edit`)
}

// Функция для закрытия модального окна
const closeModal = () => {
  showModal.value = false
  editingPost.value = null
  errors.value = {}
}

// Функция для обработки загрузки изображения
const handleImageUpload = (data) => {
  form.value.image_url = data.url
}

// Функция для сохранения статьи
const savePost = async () => {
  // Очищаем предыдущие ошибки
  errors.value = {}
  isSubmitting.value = true

  try {
    // В реальном приложении здесь будет API запрос
    if (editingPost.value) {
      // Редактирование существующей статьи
      // Для демо-режима просто обновляем статью в локальном массиве
      const index = posts.value.data.findIndex(p => p.id === form.value.id)
      if (index !== -1) {
        posts.value.data[index] = { ...posts.value.data[index], ...form.value }
      }
      alert(`Статья "${form.value.title}" успешно обновлена!`)
    } else {
      // Создание новой статьи
      // Для демо-режима просто добавляем статью в локальный массив
      const newPost = {
        ...form.value,
        id: Date.now(),
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
      posts.value.data.unshift(newPost)
      alert(`Статья "${form.value.title}" успешно создана!`)
    }
    
    // Закрываем модальное окно
    closeModal()
  } catch (error) {
    console.error('Ошибка при сохранении статьи:', error)
    alert('Ошибка при сохранении статьи')
  } finally {
    isSubmitting.value = false
  }
}

// Функция для просмотра опубликованной статьи на сайте
const viewPublishedPost = (post) => {
  // В реальном приложении здесь будет переход на страницу статьи на сайте
  if (post.is_published) {
    // Открываем статью в новой вкладке
    const slug = post.slug || post.id
    const url = `/blog/${slug}`
    
    // Используем window.open для открытия в новой вкладке
    if (process.client) {
      window.open(url, '_blank')
    }
  }
}

onMounted(() => {
  loadPosts()
  
  // Закрытие меню при клике вне его
  if (process.client) {
    window.addEventListener('click', (event) => {
      const menus = document.querySelectorAll('.action-menu-container')
      let clickedInsideMenu = false
      
      menus.forEach(menu => {
        if (menu.contains(event.target)) {
          clickedInsideMenu = true
        }
      })
      
      if (!clickedInsideMenu) {
        activeActionMenu.value = null
      }
    })
  }
})
</script>