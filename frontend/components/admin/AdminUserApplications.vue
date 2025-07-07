<template>
  <div>
    <div v-if="loading" class="flex justify-center items-center py-10">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>
    
    <div v-else>
      <!-- Фильтры -->
      <div class="mb-6 flex flex-wrap gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
          <select 
            v-model="statusFilter" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
          >
            <option value="">Все статусы</option>
            <option value="pending">На рассмотрении</option>
            <option value="interview">Назначено собеседование</option>
            <option value="reserve">В резерве</option>
            <option value="rejected">Отклонено</option>
            <option value="hired">Трудоустроен</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Период</label>
          <select 
            v-model="periodFilter" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
          >
            <option value="">Все время</option>
            <option value="week">За неделю</option>
            <option value="month">За месяц</option>
            <option value="quarter">За 3 месяца</option>
            <option value="year">За год</option>
          </select>
        </div>
        <div class="flex-grow">
          <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Поиск по названию вакансии" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
          />
        </div>
      </div>

      <!-- Список откликов -->
      <div v-if="filteredApplications.length > 0">
        <div class="bg-white overflow-hidden rounded-lg">
          <ul class="divide-y divide-gray-200">
            <li v-for="application in filteredApplications" :key="application.id" class="hover:bg-gray-50">
              <div class="px-4 py-5">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ application.job.title }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ application.job.company }}</p>
                  </div>
                  <div class="flex flex-col items-end">
                    <select 
                      v-model="application.status" 
                      class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      @change="updateApplicationStatus(application)"
                    >
                      <option value="pending">На рассмотрении</option>
                      <option value="interview">Назначено собеседование</option>
                      <option value="reserve">В резерве</option>
                      <option value="rejected">Отклонено</option>
                      <option value="hired">Трудоустроен</option>
                    </select>
                    <span class="mt-1 text-xs text-gray-500">Отклик от {{ formatDate(application.created_at) }}</span>
                  </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-4">
                  <a 
                    :href="`/jobs/${application.job.slug}`" 
                    target="_blank"
                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150"
                  >
                    Просмотр вакансии
                  </a>
                  <button
                    v-if="application.status === 'interview'"
                    @click="showInterviewDetails(application)"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150"
                  >
                    Детали собеседования
                  </button>
                </div>
                <div class="mt-3">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Отзыв работодателя</label>
                  <textarea 
                    v-model="application.feedback" 
                    rows="2" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                    placeholder="Добавьте отзыв для кандидата"
                    @blur="updateApplicationFeedback(application)"
                  ></textarea>
                </div>
                <div v-if="application.status === 'interview'" class="mt-3 p-3 bg-gray-50 rounded-md">
                  <h4 class="text-sm font-medium text-gray-700">Информация о собеседовании:</h4>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">Дата и время</label>
                      <input 
                        type="datetime-local" 
                        v-model="application.interview.datetime" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                        @change="updateInterviewDetails(application)"
                      />
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-700 mb-1">Место/Ссылка</label>
                      <input 
                        type="text" 
                        v-model="application.interview.location" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm"
                        placeholder="Адрес или ссылка на видеоконференцию"
                        @blur="updateInterviewDetails(application)"
                      />
                    </div>
                  </div>
                  <div class="mt-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Примечания</label>
                    <textarea 
                      v-model="application.interview.notes" 
                      rows="2" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                      placeholder="Дополнительная информация о собеседовании"
                      @blur="updateInterviewDetails(application)"
                    ></textarea>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Пустое состояние -->
      <div v-else class="bg-white shadow rounded-lg p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Нет откликов</h3>
        <p class="mt-1 text-sm text-gray-500">У пользователя еще нет откликов на вакансии</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useNotification } from '@/stores/notification'
import { useRuntimeConfig } from '#app'

const props = defineProps({
  userId: {
    type: String,
    required: true
  }
})

const notification = useNotification()
const config = useRuntimeConfig()
const loading = ref(false)

// Фильтры
const statusFilter = ref('')
const periodFilter = ref('')
const searchQuery = ref('')

// Данные
const applications = ref([])

// Загрузка откликов пользователя
const loadApplications = async () => {
  try {
    loading.value = true
    
    // В реальном приложении здесь будет запрос к API
    const response = await fetch(`${config.public.apiBase}/api/admin/users/${props.userId}/applications`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    if (!response.ok) throw new Error('Ошибка загрузки откликов')
    
    const data = await response.json()
    applications.value = data.applications || []
    
    // Инициализируем данные о собеседовании для каждого отклика
    applications.value.forEach(app => {
      if (!app.interview) {
        app.interview = {
          datetime: '',
          location: '',
          notes: ''
        }
      }
    })
  } catch (error) {
    console.error('Ошибка при загрузке откликов:', error)
    notification.show('Не удалось загрузить отклики', 'error')
  } finally {
    loading.value = false
  }
}

// Фильтрация откликов
const filteredApplications = computed(() => {
  return applications.value.filter(app => {
    // Фильтр по статусу
    if (statusFilter.value && app.status !== statusFilter.value) {
      return false
    }
    
    // Фильтр по периоду
    if (periodFilter.value) {
      const appDate = new Date(app.created_at)
      const now = new Date()
      
      if (periodFilter.value === 'week' && (now - appDate) > 7 * 24 * 60 * 60 * 1000) {
        return false
      } else if (periodFilter.value === 'month' && (now - appDate) > 30 * 24 * 60 * 60 * 1000) {
        return false
      } else if (periodFilter.value === 'quarter' && (now - appDate) > 90 * 24 * 60 * 60 * 1000) {
        return false
      } else if (periodFilter.value === 'year' && (now - appDate) > 365 * 24 * 60 * 60 * 1000) {
        return false
      }
    }
    
    // Поиск по названию вакансии
    if (searchQuery.value && !app.job.title.toLowerCase().includes(searchQuery.value.toLowerCase())) {
      return false
    }
    
    return true
  })
})

// Форматирование даты
function formatDate(dateString) {
  const options = { day: 'numeric', month: 'long', year: 'numeric' }
  return new Date(dateString).toLocaleDateString('ru-RU', options)
}

// Обновление статуса отклика
async function updateApplicationStatus(application) {
  try {
    // В реальном приложении здесь будет запрос к API
    const response = await fetch(`${config.public.apiBase}/api/admin/applications/${application.id}/status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ status: application.status })
    })
    
    if (!response.ok) throw new Error('Ошибка обновления статуса')
    
    notification.show('Статус отклика обновлен', 'success')
  } catch (error) {
    console.error('Ошибка при обновлении статуса:', error)
    notification.show('Не удалось обновить статус отклика', 'error')
  }
}

// Обновление отзыва работодателя
async function updateApplicationFeedback(application) {
  try {
    // В реальном приложении здесь будет запрос к API
    const response = await fetch(`${config.public.apiBase}/api/admin/applications/${application.id}/feedback`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ feedback: application.feedback })
    })
    
    if (!response.ok) throw new Error('Ошибка обновления отзыва')
    
    notification.show('Отзыв обновлен', 'success')
  } catch (error) {
    console.error('Ошибка при обновлении отзыва:', error)
    notification.show('Не удалось обновить отзыв', 'error')
  }
}

// Обновление данных о собеседовании
async function updateInterviewDetails(application) {
  try {
    // В реальном приложении здесь будет запрос к API
    const response = await fetch(`${config.public.apiBase}/api/admin/applications/${application.id}/interview`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ interview: application.interview })
    })
    
    if (!response.ok) throw new Error('Ошибка обновления данных о собеседовании')
    
    notification.show('Данные о собеседовании обновлены', 'success')
  } catch (error) {
    console.error('Ошибка при обновлении данных о собеседовании:', error)
    notification.show('Не удалось обновить данные о собеседовании', 'error')
  }
}

// Показать детали собеседования
function showInterviewDetails(application) {
  // Если нужно, здесь может быть дополнительная логика
  console.log('Показаны детали собеседования для отклика:', application.id)
}

// Наблюдатель за изменениями в фильтрах
watch([statusFilter, periodFilter, searchQuery], () => {
  // Если нужно, здесь может быть дополнительная логика
})

// Загрузка данных при монтировании
onMounted(() => {
  loadApplications()
})
</script>
