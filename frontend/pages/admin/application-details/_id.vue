<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление откликом</h1>
            <NuxtLink
              to="/admin/applications"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Вернуться к списку
            </NuxtLink>
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
                @click="loadApplication" 
                class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Попробовать снова
              </button>
            </div>
          </div>

          <div v-else class="mt-8 space-y-6">
            <!-- Информация о вакансии и кандидате -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Информация о вакансии -->
                  <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Информация о вакансии</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                      <div class="mb-4">
                        <h3 class="text-xl font-bold">{{ application.job.title }}</h3>
                        <p class="text-gray-600">{{ application.job.company }}</p>
                      </div>
                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <p class="text-sm text-gray-500">Зарплата</p>
                          <p>{{ application.job.salary || 'Не указана' }}</p>
                        </div>
                        <div>
                          <p class="text-sm text-gray-500">Локация</p>
                          <p>{{ application.job.location || 'Не указана' }}</p>
                        </div>
                      </div>
                      <div class="mt-4">
                        <NuxtLink 
                          :to="`/admin/jobs/${application.job.id}`"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          Просмотреть вакансию
                        </NuxtLink>
                      </div>
                    </div>
                  </div>

                  <!-- Информация о кандидате -->
                  <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Информация о кандидате</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                      <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                          <img 
                            v-if="application.user.avatar" 
                            :src="application.user.avatar" 
                            :alt="application.user.name"
                            class="h-full w-full object-cover"
                          />
                          <span v-else class="text-lg font-medium text-gray-500">
                            {{ application.user.fullName ? application.user.fullName.charAt(0) : 'U' }}
                          </span>
                        </div>
                        <div class="ml-4">
                          <h3 class="text-lg font-medium">{{ application.user.fullName }}</h3>
                          <p class="text-gray-600">{{ application.user.email }}</p>
                        </div>
                      </div>
                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <p class="text-sm text-gray-500">Телефон</p>
                          <p>{{ application.user.phone || 'Не указан' }}</p>
                        </div>
                        <div>
                          <p class="text-sm text-gray-500">Город</p>
                          <p>{{ application.user.city || 'Не указан' }}</p>
                        </div>
                      </div>
                      <div class="mt-4">
                        <NuxtLink 
                          :to="`/admin/users/${application.user.id}/profile`"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          Просмотреть профиль
                        </NuxtLink>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Управление статусом отклика -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Статус отклика</h2>
                <div class="flex items-center justify-between">
                  <div>
                    <span 
                      class="px-3 py-1 rounded-full text-sm font-medium"
                      :class="[
                        application.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                        application.status === 'interview' ? 'bg-blue-100 text-blue-800' : 
                        application.status === 'reserve' ? 'bg-purple-100 text-purple-800' : 
                        application.status === 'rejected' ? 'bg-red-100 text-red-800' : 
                        'bg-green-100 text-green-800'
                      ]"
                    >
                      {{ 
                        application.status === 'pending' ? 'На рассмотрении' : 
                        application.status === 'interview' ? 'Назначено собеседование' : 
                        application.status === 'reserve' ? 'В резерве' : 
                        application.status === 'rejected' ? 'Отклонено' : 
                        'Трудоустроен'
                      }}
                    </span>
                    <p class="mt-2 text-sm text-gray-500">
                      Отклик создан: {{ formatDate(application.created_at) }}
                    </p>
                  </div>
                  <div>
                    <select 
                      v-model="newStatus" 
                      class="mr-2 px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                      <option value="pending">На рассмотрении</option>
                      <option value="interview">Назначено собеседование</option>
                      <option value="reserve">В резерве</option>
                      <option value="rejected">Отклонено</option>
                      <option value="hired">Трудоустроен</option>
                    </select>
                    <button 
                      @click="updateStatus" 
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      :disabled="newStatus === application.status || isProcessing"
                    >
                      {{ isProcessing ? 'Обновление...' : 'Обновить статус' }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Детали собеседования (если статус "Назначено собеседование") -->
            <div v-if="application.status === 'interview'" class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Информация о собеседовании</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Дата собеседования</label>
                    <input 
                      type="date" 
                      v-model="interviewDetails.date"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Время собеседования</label>
                    <input 
                      type="time" 
                      v-model="interviewDetails.time"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Место проведения</label>
                    <input 
                      type="text" 
                      v-model="interviewDetails.location"
                      placeholder="Адрес или ссылка на видеоконференцию"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Примечания</label>
                    <textarea 
                      v-model="interviewDetails.notes"
                      rows="3"
                      placeholder="Дополнительная информация о собеседовании"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    ></textarea>
                  </div>
                </div>
                <div class="mt-4 flex justify-end">
                  <button 
                    @click="saveInterviewDetails" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="isProcessing"
                  >
                    {{ isProcessing ? 'Сохранение...' : 'Сохранить детали собеседования' }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Отзыв работодателя -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Отзыв работодателя</h2>
                <textarea 
                  v-model="feedback"
                  rows="4"
                  placeholder="Добавьте отзыв для кандидата"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                ></textarea>
                <div class="mt-4 flex justify-end">
                  <button 
                    @click="saveFeedback" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="isProcessing"
                  >
                    {{ isProcessing ? 'Сохранение...' : 'Сохранить отзыв' }}
                  </button>
                </div>
              </div>
            </div>

            <!-- Сопроводительное письмо кандидата -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Сопроводительное письмо кандидата</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <p v-if="application.cover_letter">{{ application.cover_letter }}</p>
                  <p v-else class="text-gray-500 italic">Кандидат не оставил сопроводительное письмо</p>
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
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import { useRuntimeConfig } from '#app'

const route = useRoute()
const router = useRouter()
const notification = useNotification()
const runtimeConfig = useRuntimeConfig()

// Состояние
const loading = ref(true)
const error = ref(null)
const application = ref({
  id: null,
  user: {},
  job: {},
  status: 'pending',
  created_at: '',
  updated_at: '',
  cover_letter: '',
  feedback: '',
  interview: null
})
const newStatus = ref('')
const feedback = ref('')
const interviewDetails = ref({
  date: '',
  time: '',
  location: '',
  notes: ''
})
const isProcessing = ref(false)

// Загрузка данных отклика
async function loadApplication() {
  loading.value = true
  error.value = null
  
  try {
    // Для демонстрации используем моковые данные
    // В реальном приложении здесь будет API запрос
    setTimeout(() => {
      // Имитация загрузки данных с сервера
      const mockData = {
        id: parseInt(route.params.id),
        user: {
          id: 101,
          fullName: 'Иванов Иван Иванович',
          email: 'ivanov@example.com',
          phone: '+7 (900) 123-45-67',
          city: 'Москва',
          avatar: null
        },
        job: {
          id: 201,
          title: 'Врач-терапевт',
          company: 'Городская клиника №1',
          salary: '80 000 - 120 000 ₽',
          location: 'Москва',
          slug: 'therapist-moscow'
        },
        status: 'pending',
        created_at: '2025-05-10T14:30:00',
        updated_at: '2025-05-10T14:30:00',
        feedback: '',
        cover_letter: 'Уважаемый работодатель! Я врач-терапевт с опытом работы 5 лет. Имею высшую квалификационную категорию. Буду рад стать частью вашей команды.',
        interview: null
      }
      
      application.value = mockData
      newStatus.value = mockData.status
      feedback.value = mockData.feedback || ''
      
      if (mockData.interview) {
        interviewDetails.value = {
          date: mockData.interview.date || '',
          time: mockData.interview.time || '',
          location: mockData.interview.location || '',
          notes: mockData.interview.notes || ''
        }
      }
      
      loading.value = false
    }, 1000)
  } catch (err) {
    console.error('Ошибка при загрузке данных отклика:', err)
    error.value = 'Не удалось загрузить данные отклика. Пожалуйста, попробуйте позже.'
    loading.value = false
  }
}

// Обновление статуса отклика
async function updateStatus() {
  if (newStatus.value === application.value.status || isProcessing.value) return
  
  isProcessing.value = true
  
  try {
    // Имитация API запроса
    setTimeout(() => {
      application.value.status = newStatus.value
      application.value.updated_at = new Date().toISOString()
      
      notification.show('Статус отклика успешно обновлен', 'success')
      isProcessing.value = false
    }, 1000)
  } catch (err) {
    console.error('Ошибка при обновлении статуса:', err)
    notification.show('Не удалось обновить статус отклика', 'error')
    isProcessing.value = false
  }
}

// Сохранение деталей собеседования
async function saveInterviewDetails() {
  if (isProcessing.value) return
  
  isProcessing.value = true
  
  try {
    // Имитация API запроса
    setTimeout(() => {
      application.value.interview = { ...interviewDetails.value }
      application.value.updated_at = new Date().toISOString()
      
      notification.show('Детали собеседования успешно сохранены', 'success')
      isProcessing.value = false
    }, 1000)
  } catch (err) {
    console.error('Ошибка при сохранении деталей собеседования:', err)
    notification.show('Не удалось сохранить детали собеседования', 'error')
    isProcessing.value = false
  }
}

// Сохранение отзыва
async function saveFeedback() {
  if (isProcessing.value) return
  
  isProcessing.value = true
  
  try {
    // Имитация API запроса
    setTimeout(() => {
      application.value.feedback = feedback.value
      application.value.updated_at = new Date().toISOString()
      
      notification.show('Отзыв успешно сохранен', 'success')
      isProcessing.value = false
    }, 1000)
  } catch (err) {
    console.error('Ошибка при сохранении отзыва:', err)
    notification.show('Не удалось сохранить отзыв', 'error')
    isProcessing.value = false
  }
}

// Форматирование даты
function formatDate(dateString) {
  const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }
  return new Date(dateString).toLocaleDateString('ru-RU', options)
}

// Загрузка данных при монтировании компонента
onMounted(() => {
  loadApplication()
})

definePageMeta({ middleware: 'admin' })
</script>
