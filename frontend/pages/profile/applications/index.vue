<template>
  <div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Личный кабинет</h1>

    <!-- Навигационное меню -->
    <div class="mb-6">
      <ProfileNavigation currentPage="applications" />
    </div>

    <div class="mb-8">
      <h2 class="text-xl font-bold text-gray-900">Мои отклики</h2>
      <p class="mt-2 text-gray-600">Здесь вы можете отслеживать статус ваших откликов на вакансии</p>
    </div>

    <!-- Фильтры -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
      <div class="flex flex-wrap gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
          <select 
            v-model="statusFilter" 
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
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
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
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
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>
      </div>
    </div>
    


    <!-- Индикатор загрузки -->
    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>
    
    <!-- Список откликов -->
    <div v-if="!loading && paginatedApplications.length > 0">
      <div class="grid grid-cols-1 gap-6">
        <div 
          v-for="application in paginatedApplications" 
          :key="application.id"
          class="bg-white shadow overflow-hidden sm:rounded-lg mb-4"
        >
          <div class="px-4 py-4 sm:px-6">
            <!-- Верхняя часть карточки с заголовком и кнопкой действия -->
            <div class="flex justify-between items-start mb-2">
              <div>
                <h3 class="text-lg font-medium text-gray-900">{{ application.job.title }}</h3>
                <p class="text-sm text-gray-500">{{ application.job.company }}</p>
              </div>
              
              <!-- Кнопка отозвать/детали собеседования справа вверху -->
              <div>
                <button 
                  v-if="application.status === 'pending'"
                  @click="showWithdrawModal = true; selectedApplication = application"
                  class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                  Отозвать
                </button>
                <button 
                  v-if="application.interview && application.status === 'interview'"
                  @click="showInterviewDetails(application)"
                  class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Детали собеседования
                </button>
              </div>
            </div>
            
            <!-- Статус отклика -->
            <div class="flex items-center mb-2">
              <span 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2"
                :class="{
                  'bg-yellow-100 text-yellow-800 border border-yellow-400': application.status === 'pending',
                  'bg-blue-100 text-blue-800 border border-blue-400': application.status === 'interview',
                  'bg-purple-100 text-purple-800 border border-purple-400': application.status === 'reserve',
                  'bg-red-100 text-red-800 border border-red-400': application.status === 'rejected',
                  'bg-green-100 text-green-800 border border-green-400': application.status === 'hired'
                }"
              >
                <span class="mr-1">
                  <svg v-if="application.status === 'pending'" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                  </svg>
                  <svg v-else-if="application.status === 'interview'" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                  </svg>
                  <svg v-else-if="application.status === 'reserve'" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  <svg v-else-if="application.status === 'rejected'" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                  </svg>
                  <svg v-else-if="application.status === 'hired'" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                  </svg>
                </span>
                {{ getStatusText(application.status) }}
              </span>
              <span class="text-sm text-gray-500">Отклик от {{ formatDate(application.createdAt) }}</span>
            </div>
            
            <!-- Дополнительные действия -->
            <div class="mt-3 border-t border-gray-200 pt-3 flex flex-wrap gap-2">
              <NuxtLink 
                :to="`/jobs/${application.job.slug}`" 
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50"
              >
                Просмотреть вакансию
              </NuxtLink>
              <button 
                @click="showStatusHistory(application)" 
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50"
              >
                История изменений
              </button>
            </div>
            
            <!-- Информация о статусе -->
            <div class="mt-3">
              <!-- Детали собеседования -->
              <div v-if="application.status === 'interview' && application.interview" class="mt-3 p-3 bg-blue-50 rounded-md">
                <h4 class="text-sm font-medium text-blue-700">Информация о собеседовании:</h4>
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                  <div>
                    <span class="text-blue-700">Дата:</span> 
                    {{ formatSimpleDate(application.interview.date) }}
                  </div>
                  <div>
                    <span class="text-blue-700">Время:</span> 
                    {{ application.interview.time }}
                  </div>
                  <div class="col-span-1 md:col-span-2">
                    <span class="text-blue-700">Примечания:</span> 
                    {{ application.interview.notes || 'Нет дополнительных примечаний' }}
                  </div>
                </div>
              </div>
                
              <!-- Отзыв работодателя -->
              <div v-if="application.feedback" class="mt-3 p-3 bg-gray-50 rounded-md">
                <h4 class="text-sm font-medium text-gray-700">Отзыв работодателя:</h4>
                <p class="mt-1 text-sm text-gray-600">{{ application.feedback }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Пустое состояние -->
    <div v-if="!loading && paginatedApplications.length === 0" class="bg-white shadow rounded-lg p-8 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Нет откликов</h3>
      <p class="mt-1 text-sm text-gray-500">
        У вас пока нет откликов на вакансии.
      </p>
      <div class="mt-6">
        <NuxtLink to="/jobs" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
          Найти вакансии
        </NuxtLink>
      </div>
    </div>

    <!-- Пагинация -->
    <div v-if="!loading && paginatedApplications.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-6 rounded-lg shadow">
      <div class="flex-1 flex justify-between sm:hidden">
        <button 
          @click="changePage(currentPage - 1)" 
          :disabled="currentPage === 1"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
        >
          Назад
        </button>
        <button 
          @click="changePage(currentPage + 1)" 
          :disabled="currentPage === totalPages"
          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
        >
          Вперед
        </button>
      </div>
      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Показано <span class="font-medium">{{ paginationStart }}</span> - <span class="font-medium">{{ paginationEnd }}</span> из <span class="font-medium">{{ filteredApplications.length }}</span> откликов
          </p>
        </div>
        <div v-if="totalPages > 1">
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
            >
              <span class="sr-only">Предыдущая</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
            <button
              v-for="page in getPageNumbers()"
              :key="page"
              @click="changePage(page)"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
              :class="{
                'z-10 bg-blue-50 border-blue-500 text-blue-600': page === currentPage,
                'text-gray-500 hover:bg-gray-50': page !== currentPage
              }"
            >
              {{ page }}
            </button>
            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
            >
              <span class="sr-only">Следующая</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Модальное окно с деталями собеседования -->
    <div v-if="showInterviewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Детали собеседования</h2>
            <button @click="showInterviewModal = false" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div v-if="selectedApplication && selectedApplication.interview" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <h3 class="text-sm font-medium text-gray-700">Дата</h3>
                <p class="text-gray-900">{{ formatSimpleDate(selectedApplication.interview.date) }}</p>
              </div>
              <div>
                <h3 class="text-sm font-medium text-gray-700">Время</h3>
                <p class="text-gray-900">{{ selectedApplication.interview.time }}</p>
              </div>
            </div>
            <div v-if="selectedApplication.interview.location">
              <h3 class="text-sm font-medium text-gray-700">Место проведения</h3>
              <p class="text-gray-900">{{ selectedApplication.interview.location }}</p>
            </div>
            <div v-if="selectedApplication.interview.notes">
              <h3 class="text-sm font-medium text-gray-700">Дополнительная информация</h3>
              <p class="text-gray-900">{{ selectedApplication.interview.notes }}</p>
            </div>
          </div>
          <div class="mt-6 flex justify-end">
            <button 
              @click="showInterviewModal = false" 
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              Закрыть
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Модальное окно с историей изменений статуса -->
    <div v-if="showHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">История изменений</h2>
            <button @click="showHistoryModal = false" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div v-if="selectedApplication && statusHistory.length > 0" class="space-y-4">
            <div v-for="(historyItem, index) in statusHistory" :key="index" class="border-l-4 pl-4 py-2" 
              :class="{
                'border-yellow-400': historyItem.status === 'pending',
                'border-blue-400': historyItem.status === 'interview',
                'border-purple-400': historyItem.status === 'reserve',
                'border-red-400': historyItem.status === 'rejected',
                'border-green-400': historyItem.status === 'hired'
              }">
              <div class="flex justify-between">
                <span class="font-medium">
                  {{ 
                    historyItem.status === 'pending' ? 'На рассмотрении' : 
                    historyItem.status === 'interview' ? 'Назначено собеседование' : 
                    historyItem.status === 'reserve' ? 'В резерве' : 
                    historyItem.status === 'rejected' ? 'Отклонено' : 
                    'Трудоустроен'
                  }}
                </span>
                <span class="text-sm text-gray-500">{{ formatDate(historyItem.date) }}</span>
              </div>
              <p v-if="historyItem.comment" class="text-sm mt-1">{{ historyItem.comment }}</p>
              
              <!-- Детали в зависимости от статуса -->
              <div v-if="historyItem.details" class="mt-2 text-sm bg-gray-50 p-2 rounded">
                <!-- Детали собеседования -->
                <div v-if="historyItem.status === 'interview' && historyItem.details.date" class="grid grid-cols-2 gap-2">
                  <div>
                    <span class="text-gray-500">Дата:</span> 
                    {{ formatSimpleDate(historyItem.details.date) }}
                  </div>
                  <div>
                    <span class="text-gray-500">Время:</span> 
                    {{ historyItem.details.time }}
                  </div>
                  <div v-if="historyItem.details.location" class="col-span-2">
                    <span class="text-gray-500">Место:</span> 
                    {{ historyItem.details.location }}
                  </div>
                </div>
                
                <!-- Причина отказа -->
                <div v-if="historyItem.status === 'rejected' && historyItem.details.reason">
                  <span class="text-gray-500">Причина:</span> 
                  {{ historyItem.details.reason }}
                </div>
                
                <!-- Комментарий для резерва -->
                <div v-if="historyItem.status === 'reserve' && historyItem.details.comment">
                  <span class="text-gray-500">Комментарий:</span> 
                  {{ historyItem.details.comment }}
                </div>
                
                <!-- Информация о трудоустройстве -->
                <div v-if="historyItem.status === 'hired' && (historyItem.details.startDate || historyItem.details.position)" class="grid grid-cols-2 gap-2">
                  <div v-if="historyItem.details.startDate">
                    <span class="text-gray-500">Дата выхода:</span> 
                    {{ formatSimpleDate(historyItem.details.startDate) }}
                  </div>
                  <div v-if="historyItem.details.position">
                    <span class="text-gray-500">Должность:</span> 
                    {{ historyItem.details.position }}
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="statusHistory.length === 0" class="text-gray-500 italic text-center py-4">
              История изменений пуста
            </div>
          </div>
          
          <div class="mt-6 flex justify-end">
            <button 
              @click="showHistoryModal = false" 
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              Закрыть
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Модальное окно подтверждения отзыва отклика -->
    <div v-if="showWithdrawModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Отозвать отклик</h2>
            <button @click="showWithdrawModal = false" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <p class="text-gray-700">Вы уверены, что хотите отозвать свой отклик на вакансию? Это действие нельзя будет отменить.</p>
          <div class="mt-6 flex justify-end space-x-3">
            <button 
              @click="showWithdrawModal = false" 
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
            >
              Отмена
            </button>
            <button 
              @click="confirmWithdraw" 
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
            >
              Отозвать
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import ProfileNavigation from '~/components/ProfileNavigation.vue'
import { useNotification } from '~/stores/notification'

const router = useRouter()
const notification = useNotification()

// Фильтры
const statusFilter = ref('')
const periodFilter = ref('')
const searchQuery = ref('')

// Пагинация
const currentPage = ref(1)
const pageSize = ref(5)

// Модальные окна
const showInterviewModal = ref(false)
const showWithdrawModal = ref(false)
const showHistoryModal = ref(false)
const selectedApplication = ref(null)
const applicationToWithdraw = ref(null)
const statusHistory = ref([])

// Данные откликов
const applications = ref([])
const loading = ref(false)

// Пример данных для демонстрации
onMounted(async () => {
  loading.value = true
  try {
    // В реальном приложении здесь будет API запрос
    // const response = await fetch('http://localhost:8000/api/applications', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // applications.value = await response.json()
    
    // Демо-данные с разными статусами и комментариями
    applications.value = [
      {
        id: 1,
        job: {
          id: 101,
          title: 'Врач-терапевт',
          company: 'Городская клиника №1',
          slug: 'therapist-moscow'
        },
        status: 'pending',
        created_at: '2025-06-01T14:30:00',
        feedback: null,
        statusHistory: [
          { status: 'pending', date: '2025-06-01T14:30:00', comment: 'Ваше резюме получено и находится на рассмотрении.' }
        ]
      },
      {
        id: 2,
        job: {
          id: 102,
          title: 'Медсестра в отделение кардиологии',
          company: 'Медицинский центр "Здоровье"',
          slug: 'nurse-cardiology'
        },
        status: 'interview',
        created_at: '2025-05-25T10:15:00',
        feedback: null,
        interview: {
          datetime: '2025-06-10T15:00:00',
          format: 'online',
          link: 'https://zoom.us/j/123456789',
          contact: 'Иванова Мария, HR-менеджер',
          notes: 'Подготовьте, пожалуйста, документы о вашем образовании и опыте работы.'
        }
      },
      {
        id: 3,
        job: {
          id: 103,
          title: 'Фармацевт',
          company: 'Аптечная сеть "Здоровье+"',
          slug: 'pharmacist'
        },
        status: 'rejected',
        created_at: '2025-04-28T09:45:00',
        feedback: 'К сожалению, мы выбрали кандидата с большим опытом работы. Благодарим за интерес к нашей компании!',
        statusHistory: [
          { status: 'pending', date: '2025-04-28T09:45:00', comment: 'Ваше резюме получено и находится на рассмотрении.' },
          { status: 'interview', date: '2025-05-05T14:20:00', comment: 'Приглашаем вас на собеседование.' },
          { status: 'rejected', date: '2025-05-10T11:30:00', comment: 'К сожалению, мы выбрали другого кандидата.' }
        ]
      },
      {
        id: 4,
        job: {
          id: 104,
          title: 'Врач-педиатр',
          company: 'Детская поликлиника №3',
          slug: 'pediatrician'
        },
        status: 'reserve',
        created_at: '2025-05-01T11:20:00',
        feedback: 'Ваша кандидатура включена в резерв. Мы свяжемся с вами, если появится подходящая вакансия.',
        statusHistory: [
          { status: 'pending', date: '2025-05-01T11:20:00', comment: 'Ваше резюме получено.' },
          { status: 'interview', date: '2025-05-10T13:00:00', comment: 'Приглашаем вас на собеседование.' },
          { status: 'reserve', date: '2025-05-15T17:45:00', comment: 'Ваша кандидатура включена в резерв.' }
        ]
      },
      {
        id: 5,
        job: {
          id: 105,
          title: 'Врач-лаборант',
          company: 'Диагностический центр "ЛабМед"',
          slug: 'lab-doctor'
        },
        status: 'hired',
        created_at: '2025-04-15T10:00:00',
        feedback: 'Поздравляем! Вы приняты на должность. Ожидаем вас для оформления документов 15 июня в 10:00.',
        statusHistory: [
          { status: 'pending', date: '2025-04-15T10:00:00', comment: 'Ваше резюме получено.' },
          { status: 'interview', date: '2025-04-20T11:30:00', comment: 'Приглашаем вас на собеседование.' },
          { status: 'interview', date: '2025-04-25T14:00:00', comment: 'Приглашаем вас на второе собеседование с руководителем отдела.' },
          { status: 'hired', date: '2025-05-01T09:15:00', comment: 'Поздравляем! Вы приняты на должность.' }
        ]
      }
    ];
    loading.value = false;
  } catch (error) {
    console.error('Error loading applications:', error);
    notification.show('Ошибка при загрузке откликов', 'error');
  } finally {
    loading.value = false;
  }
});

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
    
    // Фильтр по поиску
    if (searchQuery.value && !app.job.title.toLowerCase().includes(searchQuery.value.toLowerCase())) {
      return false
    }
    
    return true
  })
})

// Пагинированные отклики
const paginatedApplications = computed(() => {
  const start = (currentPage.value - 1) * pageSize.value
  const end = start + pageSize.value
  return filteredApplications.value.slice(start, end)
})

// Общее количество страниц
const totalPages = computed(() => {
  return Math.ceil(filteredApplications.value.length / pageSize.value)
})

// Информация о текущем диапазоне отображаемых элементов
const paginationStart = computed(() => {
  if (filteredApplications.value.length === 0) return 0
  return (currentPage.value - 1) * pageSize.value + 1
})

const paginationEnd = computed(() => {
  if (filteredApplications.value.length === 0) return 0
  return Math.min(currentPage.value * pageSize.value, filteredApplications.value.length)
})

// Функция для генерации номеров страниц для пагинации
function getPageNumbers() {
  const pages = []
  const maxVisiblePages = 5
  
  if (totalPages.value <= maxVisiblePages) {
    // Если страниц мало, показываем все
    for (let i = 1; i <= totalPages.value; i++) {
      pages.push(i)
    }
  } else {
    // Иначе показываем текущую страницу и несколько соседних
    let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2))
    let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1)
    
    // Корректируем начальную страницу, если мы у конца
    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
  }
  
  return pages
}

// Функция изменения страницы
function changePage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    // Прокручиваем страницу наверх
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Сброс страницы при изменении фильтров
watch([statusFilter, periodFilter, searchQuery], () => {
  currentPage.value = 1
})

// Форматирование даты
function formatDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(date)
}

// Получение текста статуса
function getStatusText(status) {
  switch (status) {
    case 'pending': return 'На рассмотрении'
    case 'interview': return 'Назначено собеседование'
    case 'reserve': return 'В резерве'
    case 'rejected': return 'Отклонено'
    case 'hired': return 'Трудоустроен'
    default: return 'Неизвестный статус'
  }
}

// Форматирование даты и времени
function formatDateTime(dateTimeString) {
  if (!dateTimeString) return ''
  const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }
  return new Date(dateTimeString).toLocaleString('ru-RU', options)
}

// Форматирование только даты без времени
function formatSimpleDate(dateString) {
  if (!dateString) return ''
  const options = { day: 'numeric', month: 'long', year: 'numeric' }
  return new Date(dateString).toLocaleDateString('ru-RU', options)
}

// Показать детали собеседования
function showInterviewDetails(application) {
  selectedApplication.value = application
  showInterviewModal.value = true
}

// Показать историю изменений статуса
function showStatusHistory(application) {
  selectedApplication.value = application
  
  // Загрузка истории из localStorage или создание базовой истории
  const savedHistory = localStorage.getItem(`application_history_${application.id}`)
  
  if (savedHistory) {
    statusHistory.value = JSON.parse(savedHistory)
  } else {
    // Если истории нет, создаем базовую запись
    statusHistory.value = [{
      status: application.status,
      date: application.created_at,
      comment: 'Отклик создан',
      details: null
    }]
    
    // Если есть детали собеседования, добавляем запись о назначении собеседования
    if (application.status === 'interview' && application.interview) {
      statusHistory.value.unshift({
        status: 'interview',
        date: application.updated_at || new Date().toISOString(),
        comment: 'Назначено собеседование',
        details: { ...application.interview }
      })
    }
    
    // Если есть отзыв, добавляем запись об отклонении или резерве
    if (application.feedback && (application.status === 'rejected' || application.status === 'reserve')) {
      statusHistory.value.unshift({
        status: application.status,
        date: application.updated_at || new Date().toISOString(),
        comment: '',
        details: application.status === 'rejected' 
          ? { reason: application.feedback }
          : { comment: application.feedback }
      })
    }
  }
  
  showHistoryModal.value = true
}

// Отозвать отклик
function withdrawApplication(id) {
  applicationToWithdraw.value = id
  showWithdrawModal.value = true
}

// Подтверждение отзыва отклика
async function confirmWithdraw() {
  try {
    // В реальном приложении здесь будет API запрос
    // await fetch(`http://localhost:8000/api/applications/${withdrawApplicationId.value}/withdraw`, {
    //   method: 'POST',
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    
    // Для демонстрации просто удаляем из массива
    applications.value = applications.value.filter(app => app.id !== withdrawApplicationId.value)
    
    notification.show('Отклик успешно отозван', 'success')
    showWithdrawModal.value = false
  } catch (error) {
    console.error('Ошибка при отзыве отклика:', error)
    notification.show('Не удалось отозвать отклик', 'error')
  }
}
</script>
