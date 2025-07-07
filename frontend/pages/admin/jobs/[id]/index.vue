<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
              <h1 class="text-3xl font-bold leading-tight text-gray-900">Просмотр вакансии</h1>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4" v-if="job">
              <button
                v-if="fromApplication"
                @click="returnToApplication"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Назад к отклику
              </button>
              <button
                @click="editJob"
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
          <div v-if="loading" class="flex justify-center items-center py-10">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
          </div>
          
          <div v-else-if="error" class="mt-8 bg-white shadow sm:rounded-lg p-6">
            <div class="text-center text-red-600">
              <p>{{ error }}</p>
              <button 
                @click="loadJob" 
                class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Попробовать снова
              </button>
            </div>
          </div>
          
          <div class="mt-8" v-else>
            <div class="bg-white shadow sm:rounded-lg" v-if="job">
              <div class="px-4 py-5 sm:p-6">
                <!-- Статус -->
                <div class="mb-6">
                  <span
                    :class="[
                      job.is_active
                        ? 'bg-green-100 text-green-800'
                        : 'bg-yellow-100 text-yellow-800',
                      'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium'
                    ]"
                  >
                    {{ job.is_active ? 'Активная' : 'Неактивная' }}
                  </span>
                </div>

                <!-- Основная информация -->
                <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2 mb-8">
                  <div>
                    <dt class="text-sm font-medium text-gray-500">Название должности</dt>
                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ job.title }}</dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500">Компания</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ job.company }}</dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500">Местоположение</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ job.location }}</dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500">Зарплата</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ job.salary }}</dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500">Тип занятости</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ formatJobType(job.type) }}</dd>
                  </div>
                </div>

                <!-- Описание -->
                <div class="mb-8">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Описание вакансии</h3>
                  <div class="prose max-w-none" v-html="job.description"></div>
                </div>

                <!-- Требования -->
                <div class="mb-8">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Требования</h3>
                  <div class="prose max-w-none" v-html="job.requirements"></div>
                </div>

                <!-- Мета-информация -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Создано</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDate(job.created_at) }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Обновлено</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDate(job.updated_at) }}</dd>
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
import RichTextEditor from '~/components/RichTextEditor.vue'
import AdminHeader from '~/components/AdminHeader.vue'

definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})
const route = useRoute()
const router = useRouter()
const notificationStore = useNotification()

const job = ref(null)
const loading = ref(true)
const error = ref(null)
const fromApplication = ref(false)
const applicationId = ref(null)

// Проверка, пришли ли мы со страницы отклика
const checkFromApplication = () => {
  fromApplication.value = route.query.from_application === 'true'
  if (fromApplication.value && route.query.application_id) {
    applicationId.value = route.query.application_id
  }
}

// Функция для возврата к отклику
const returnToApplication = () => {
  if (applicationId.value) {
    router.push(`/admin/view-application?id=${applicationId.value}`)
  } else {
    router.push('/admin/applications')
  }
}

// Загрузка данных вакансии
const loadJob = async () => {
  loading.value = true
  error.value = null
  try {
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    const jobId = route.params.id
    
    // Моковые данные для разных ID вакансий
    const mockJobs = {
      '201': {
        id: 201,
        title: 'Врач-терапевт',
        company: 'Медицинский центр "Здоровье"',
        location: 'Москва',
        salary: '80 000 - 120 000 ₽',
        type: 'full-time',
        is_active: true,
        description: '<p>Требуется врач-терапевт для работы в современной клинике.</p><p>Обязанности:</p><ul><li>Прием и консультация пациентов</li><li>Диагностика и лечение заболеваний</li><li>Ведение медицинской документации</li></ul>',
        requirements: '<p>Требования:</p><ul><li>Высшее медицинское образование</li><li>Опыт работы от 3 лет</li><li>Действующий сертификат по специальности</li></ul>',
        created_at: '2023-01-15T10:30:00.000Z',
        updated_at: '2023-02-10T14:45:00.000Z'
      },
      '202': {
        id: 202,
        title: 'Хирург',
        company: 'Городская клиническая больница',
        location: 'Санкт-Петербург',
        salary: '150 000 - 200 000 ₽',
        type: 'full-time',
        is_active: true,
        description: '<p>Требуется опытный хирург для работы в отделении общей хирургии.</p><p>Обязанности:</p><ul><li>Проведение хирургических операций</li><li>Консультация пациентов</li><li>Ведение медицинской документации</li></ul>',
        requirements: '<p>Требования:</p><ul><li>Высшее медицинское образование</li><li>Опыт работы от 5 лет</li><li>Специализация в области хирургии</li></ul>',
        created_at: '2023-03-05T09:15:00.000Z',
        updated_at: '2023-03-20T11:30:00.000Z'
      }
    }
    
    // Проверяем, есть ли данные для запрошенного ID
    if (mockJobs[jobId]) {
      // Имитируем задержку загрузки данных
      setTimeout(() => {
        job.value = mockJobs[jobId]
        loading.value = false
      }, 300)
    } else {
      // Если данных нет, показываем ошибку
      error.value = 'Вакансия не найдена'
      loading.value = false
      notificationStore.error('Вакансия не найдена')
    }
  } catch (err) {
    console.error('Ошибка при загрузке вакансии:', err)
    error.value = 'Ошибка при загрузке вакансии'
    loading.value = false
    notificationStore.error('Ошибка при загрузке вакансии')
  }
}

const editJob = () => {
  router.push(`/admin/jobs/${route.params.id}/edit`)
}

const formatJobType = (type) => {
  const types = {
    'full-time': 'Полная занятость',
    'part-time': 'Частичная занятость',
    'contract': 'Контракт',
    'internship': 'Стажировка'
  }
  return types[type] || type
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
  checkFromApplication()
  loadJob()
})
</script> 