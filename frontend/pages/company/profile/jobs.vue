<!--
  Страница управления вакансиями в профиле компании.
  Позволяет просматривать список вакансий компании, создавать новые,
  редактировать и удалять существующие. Также отображает статус вакансий
  (активна, не активна, на модерации) и предоставляет фильтрацию.
  В режиме администратора показывает дополнительную панель с возможностью
  вернуться в админ-панель.
-->
<template>
  <div class="min-h-screen bg-gray-100">
    <CompanyProfileHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Индикатор режима администратора -->
          <div v-if="isAdminMode" class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-yellow-700">
                  Вы управляете этой компанией в режиме администратора
                </p>
              </div>
              <div class="ml-auto">
                <button 
                  @click="returnToAdmin" 
                  class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                >
                  Вернуться в админ-панель
                </button>
              </div>
            </div>
          </div>
          
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Вакансии компании</h1>
            <button
              @click="createJob"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              Добавить вакансию
            </button>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Список вакансий -->
          <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div v-if="jobs.length === 0" class="p-6 text-center text-gray-500">
              <p>Нет вакансий, соответствующих выбранным критериям</p>
              <button
                @click="createJob"
                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                Добавить вакансию
              </button>
            </div>
            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Специальность</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Местоположение</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата создания</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="job in paginatedJobs" :key="job.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ job.title }}</div>
                      <div class="text-sm text-gray-500">{{ job.location }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ job.specialty }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ job.location }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="[
                          job.status === 'active' ? 'bg-green-100 text-green-800' : 
                          job.status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                          'bg-yellow-100 text-yellow-800',
                          'px-2 inline-flex text-xs leading-5 font-semibold rounded-full'
                        ]"
                      >
                        {{ getStatusName(job.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ formatDate(job.created_at) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="relative action-menu-container">
                        <button 
                          @click="toggleActionMenu(job.id)" 
                          class="p-1 rounded-full hover:bg-gray-200 transition-colors"
                          title="Действия"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                          </svg>
                        </button>
                        
                        <div 
                          v-if="activeActionMenu === job.id" 
                          class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border"
                          :class="{
                            'bottom-full mb-2': isLastRows(job.id)
                          }"
                        >
                          <div class="py-1">
                            <button 
                              @click="editJob(job)"
                              class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                              Редактировать
                            </button>
                            <button
                              v-if="job.status === 'inactive'"
                              @click="activateJob(job)"
                              class="w-full text-left block px-4 py-2 text-sm text-green-600 hover:bg-gray-100"
                            >
                              Активировать
                            </button>
                            <button
                              v-if="job.status === 'active'"
                              @click="deactivateJob(job)"
                              class="w-full text-left block px-4 py-2 text-sm text-yellow-600 hover:bg-gray-100"
                            >
                              Деактивировать
                            </button>
                            <button
                              @click="deleteJob(job)"
                              class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
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
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import CompanyProfileHeader from '~/components/CompanyProfileHeader.vue'
import { useNotification } from '~/stores/notification'

// Middleware для проверки авторизации
definePageMeta({
  middleware: ['auth-check']
})

const router = useRouter()
const notification = useNotification()
const activeActionMenu = ref(null)

// Проверка режима администратора
const isAdminMode = ref(false)
const adminCompanyData = ref(null)

// Оригинальный список вакансий для фильтрации
const originalJobs = ref([])

// Функция для перехода на страницу создания вакансии
const createJob = () => {
  router.push('/company/profile/jobs/create')
}

// Функция для перехода на страницу редактирования вакансии
const editJob = (job) => {
  router.push(`/company/profile/jobs/${job.id}/edit`)
}

// Функция возврата в админ-панель
const returnToAdmin = () => {
  // Удаляем информацию о режиме администратора
  localStorage.removeItem('admin_managing_company')
  
  // Возвращаемся на страницу компаний в админ-панели
  router.push('/admin/companies')
}

// Списки компаний и специальностей для фильтрации
const companies = ref([])
const specialties = ref([])

// Данные о балансе компании
const companyData = ref({
  balance: 15000, // Демо-данные для баланса
  free_job_postings: 2 // Демо-данные для бесплатных размещений
})

// Фильтры
const filters = ref({
  status: '',
  search: ''
})

// Пагинация
const currentPage = ref(1)
const pageSize = 5
const totalJobs = ref(0)

// Список вакансий
const jobs = ref([
  {
    id: 1,
    title: 'Врач-терапевт',
    specialty: 'Терапия',
    location: 'Москва',
    status: 'active',
    created_at: '2025-04-15T10:30:00Z'
  },
  {
    id: 2,
    title: 'Медицинская сестра',
    specialty: 'Сестринское дело',
    location: 'Москва',
    status: 'active',
    created_at: '2025-04-20T14:45:00Z'
  },
  {
    id: 3,
    title: 'Врач-хирург',
    specialty: 'Хирургия',
    location: 'Санкт-Петербург',
    status: 'inactive',
    created_at: '2025-04-10T09:20:00Z'
  }
])

// Вычисляемое свойство для отображения вакансий с учетом пагинации
const paginatedJobs = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize
  const endIndex = startIndex + pageSize
  return filteredJobs.value.slice(startIndex, endIndex)
})

// Вычисляемое свойство для общего количества страниц
const totalPages = computed(() => {
  return Math.ceil(filteredJobs.value.length / pageSize)
})

// Вычисляемое свойство для отфильтрованных вакансий
const filteredJobs = computed(() => {
  let result = [...jobs.value]
  
  // Фильтрация по статусу
  if (filters.value.status) {
    result = result.filter(job => job.status === filters.value.status)
  }
  
  // Фильтрация по поисковому запросу
  if (filters.value.search) {
    const searchLower = filters.value.search.toLowerCase()
    result = result.filter(job => 
      job.title.toLowerCase().includes(searchLower) || 
      job.specialty.toLowerCase().includes(searchLower) ||
      job.location.toLowerCase().includes(searchLower)
    )
  }
  
  return result
})

// Функция для применения фильтров
const applyFilters = () => {
  currentPage.value = 1 // Сбрасываем на первую страницу при применении фильтров
}

// Функция для сброса фильтров
const resetFilters = () => {
  filters.value.status = ''
  filters.value.search = ''
  currentPage.value = 1
}

// Функция для получения названия статуса
const getStatusName = (status) => {
  switch (status) {
    case 'active': return 'Активна'
    case 'inactive': return 'Неактивна'
    case 'draft': return 'Черновик'
    default: return status
  }
}

// Функция для форматирования даты
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date)
}

// Функция для переключения меню действий
const toggleActionMenu = (jobId) => {
  if (activeActionMenu.value === jobId) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = jobId
  }
}

// Функция для закрытия всех меню действий
const closeActionMenus = (event) => {
  // Проверяем, что клик был не по меню действий
  if (!event.target.closest('.action-menu-container')) {
    activeActionMenu.value = null
  }
}

// Функция для определения, находится ли строка в последних рядах таблицы
const isLastRows = (jobId) => {
  const index = paginatedJobs.value.findIndex(job => job.id === jobId)
  return index >= paginatedJobs.value.length - 2
}

// Функция для активации вакансии
const activateJob = (job) => {
  // В реальном приложении здесь будет API-запрос
  job.status = 'active'
  notification.show('Вакансия активирована', 'success')
  activeActionMenu.value = null
}

// Функция для деактивации вакансии
const deactivateJob = (job) => {
  // В реальном приложении здесь будет API-запрос
  job.status = 'inactive'
  notification.show('Вакансия деактивирована', 'success')
  activeActionMenu.value = null
}

// Функция для удаления вакансии
const deleteJob = (job) => {
  // В реальном приложении здесь будет API-запрос
  const index = jobs.value.findIndex(j => j.id === job.id)
  if (index !== -1) {
    jobs.value.splice(index, 1)
    notification.show('Вакансия удалена', 'success')
  }
  activeActionMenu.value = null
}

// Загрузка вакансий
const loadJobs = async () => {
  try {
    // В реальном приложении здесь будет API запрос
    // Для демо-режима используем тестовые данные, которые уже заданы
    originalJobs.value = [...jobs.value]
    totalJobs.value = jobs.value.length
  } catch (error) {
    notification.show('Ошибка при загрузке вакансий: ' + error.message, 'error')
    console.error('Ошибка при загрузке вакансий:', error)
  }
}

// Загрузка вакансий и справочников при монтировании компонента
onMounted(() => {
  // Проверяем, есть ли информация о режиме администратора
  const adminCompanyDataStr = localStorage.getItem('admin_managing_company')
  if (adminCompanyDataStr) {
    try {
      // Парсим данные о компании, которой управляет администратор
      adminCompanyData.value = JSON.parse(adminCompanyDataStr)
      isAdminMode.value = adminCompanyData.value.admin_mode === true
      console.log('Режим администратора:', isAdminMode.value)
    } catch (error) {
      console.error('Ошибка при парсинге данных компании администратора:', error)
    }
  }
  
  // Загружаем данные
  loadJobs()
  
  // Добавляем обработчик клика для закрытия выпадающих меню
  document.addEventListener('click', closeActionMenus)
})

// Удаляем обработчик клика при размонтировании компонента
onUnmounted(() => {
  document.removeEventListener('click', closeActionMenus)
})

// Инициализация тестовых данных для компаний и специальностей
companies.value = [
  { id: 1, name: 'ООО "Моя компания"' }
]

specialties.value = [
  { id: 1, name: 'Frontend-разработчик' },
  { id: 2, name: 'Backend-разработчик' },
  { id: 3, name: 'UX/UI дизайнер' },
  { id: 4, name: 'DevOps инженер' },
  { id: 5, name: 'QA инженер' },
  { id: 6, name: 'Project Manager' },
  { id: 7, name: 'Product Manager' },
  { id: 8, name: 'HR специалист' }
]
</script>
