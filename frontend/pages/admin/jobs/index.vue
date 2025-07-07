<!--
  Страница управления вакансиями в административной панели.
  Отображает список всех вакансий с возможностью фильтрации и поиска.
  Позволяет создавать новые вакансии, редактировать и удалять существующие.
  Администратор может изменять статус вакансий (активна, не активна, на модерации)
  и просматривать детали вакансий.
-->
<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление вакансиями</h1>
            <NuxtLink
              to="/admin/jobs/create"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Добавить вакансию
            </NuxtLink>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Поиск и фильтры -->
          <div class="mt-4 space-y-4">
            <!-- Поиск и фильтр по компаниям -->
            <div class="flex flex-wrap gap-4">
              <div class="flex-grow">
                <input
                  type="text"
                  v-model="searchQuery"
                  placeholder="Поиск вакансий..."
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                />
              </div>
              <div class="w-64">
                <select
                  v-model="companyFilter"
                  class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                  <option value="">Все компании</option>
                  <option v-for="company in companies" :key="company.id" :value="company.id">
                    {{ company.name }}
                  </option>
                </select>
              </div>
            </div>
            
            <!-- Фильтр по статусу -->
            <div class="flex flex-wrap gap-2">
              <span class="text-sm font-medium text-gray-700">Статус:</span>
              <label class="inline-flex items-center ml-2">
                <input 
                  type="radio" 
                  v-model="statusFilter" 
                  value="" 
                  class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                />
                <span class="ml-2 text-sm text-gray-700">Все</span>
              </label>
              <label class="inline-flex items-center ml-2">
                <input 
                  type="radio" 
                  v-model="statusFilter" 
                  value="active" 
                  class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                />
                <span class="ml-2 text-sm text-gray-700">Активные</span>
              </label>
              <label class="inline-flex items-center ml-2">
                <input 
                  type="radio" 
                  v-model="statusFilter" 
                  value="inactive" 
                  class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                />
                <span class="ml-2 text-sm text-gray-700">Неактивные</span>
              </label>
            </div>
          </div>

          <!-- Список вакансий -->
          <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div v-if="filteredJobs.length === 0" class="p-6 text-center text-gray-500">
              <p>Нет вакансий, соответствующих выбранным критериям</p>
            </div>
            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Компания</th>
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ getCompanyName(job.company_id) }}</div>
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
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                            <NuxtLink
                              :to="`/admin/jobs/${job.id}/edit`"
                              class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                              @click="activeActionMenu = null"
                            >
                              Редактировать
                            </NuxtLink>
                            <button
                              @click="deleteJob(job); activeActionMenu = null"
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
              
              <!-- Пагинация -->
              <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                  <button 
                    @click="loadJobs(jobs.current_page - 1)" 
                    :disabled="!jobs.prev_page_url" 
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': !jobs.prev_page_url }"
                  >
                    Предыдущая
                  </button>
                  <button 
                    @click="loadJobs(jobs.current_page + 1)" 
                    :disabled="!jobs.next_page_url" 
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': !jobs.next_page_url }"
                  >
                    Следующая
                  </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Показано <span class="font-medium">{{ (currentPage - 1) * pageSize + 1 }}</span> - <span class="font-medium">{{ Math.min(currentPage * pageSize, filteredJobs.length) }}</span> из <span class="font-medium">{{ filteredJobs.length }}</span> вакансий
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button 
                        @click="currentPage--" 
                        :disabled="currentPage === 1" 
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                      >
                        <span class="sr-only">Предыдущая</span>
                        &lt;
                      </button>
                      <button
                        v-for="page in totalPages"
                        :key="page"
                        @click="currentPage = page"
                        :class="[
                          page === currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]"
                      >
                        {{ page }}
                      </button>
                      <button 
                        @click="currentPage++" 
                        :disabled="currentPage === totalPages" 
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
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
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useNotification } from '~/stores/notification'

// Middleware для проверки авторизации
definePageMeta({
  layout: 'admin',
  middleware: 'jobs-admin'
})

const notification = useNotification()
const activeActionMenu = ref(null)

// Фильтры
const searchQuery = ref('')
const companyFilter = ref('')
const statusFilter = ref('')

// Пагинация
const currentPage = ref(1)
const pageSize = 10

// Список компаний
const companies = ref([
  { id: 1, name: 'Клиника "Здоровье"' },
  { id: 2, name: 'Медицинский центр "Доктор+"' },
  { id: 3, name: 'Стоматология "Улыбка"' }
])

// Список вакансий (в реальном приложении будет загружаться с сервера)
const jobs = ref([
  {
    id: 1,
    title: 'Врач-терапевт',
    company_id: 1,
    location: 'Москва',
    status: 'active',
    created_at: '2025-04-15T10:30:00Z'
  },
  {
    id: 2,
    title: 'Медицинская сестра',
    company_id: 1,
    location: 'Москва',
    status: 'active',
    created_at: '2025-04-20T14:45:00Z'
  },
  {
    id: 3,
    title: 'Врач-хирург',
    company_id: 2,
    location: 'Санкт-Петербург',
    status: 'inactive',
    created_at: '2025-04-10T09:20:00Z'
  },
  {
    id: 4,
    title: 'Стоматолог',
    company_id: 3,
    location: 'Москва',
    status: 'active',
    created_at: '2025-04-05T11:15:00Z'
  }
])

// Фильтрованные вакансии
const filteredJobs = computed(() => {
  let result = [...jobs.value]
  
  // Фильтр по поисковому запросу
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(job => 
      job.title.toLowerCase().includes(query) || 
      getCompanyName(job.company_id).toLowerCase().includes(query) ||
      job.location.toLowerCase().includes(query)
    )
  }
  
  // Фильтр по компании
  if (companyFilter.value) {
    result = result.filter(job => job.company_id === parseInt(companyFilter.value))
  }
  
  // Фильтр по статусу
  if (statusFilter.value) {
    result = result.filter(job => job.status === statusFilter.value)
  }
  
  return result
})

// Вакансии с учетом пагинации
const paginatedJobs = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize
  const endIndex = startIndex + pageSize
  return filteredJobs.value.slice(startIndex, endIndex)
})

// Общее количество страниц
const totalPages = computed(() => {
  return Math.ceil(filteredJobs.value.length / pageSize)
})

// Функция для получения названия компании по ID
const getCompanyName = (companyId) => {
  const company = companies.value.find(c => c.id === companyId)
  return company ? company.name : 'Неизвестная компания'
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

// Функция для определения, находится ли строка в последних рядах таблицы
const isLastRows = (jobId) => {
  const index = paginatedJobs.value.findIndex(job => job.id === jobId)
  return index >= paginatedJobs.value.length - 2
}

// Функция для удаления вакансии
const deleteJob = (job) => {
  // В реальном приложении здесь будет API-запрос
  const index = jobs.value.findIndex(j => j.id === job.id)
  if (index !== -1) {
    jobs.value.splice(index, 1)
    notification.success('Вакансия успешно удалена')
  }
}

// Загрузка данных при монтировании компонента
onMounted(() => {
  // В реальном приложении здесь будет загрузка данных с сервера
  // loadJobs()
  
  // Добавляем обработчик клика для закрытия выпадающих меню
  document.addEventListener('click', (event) => {
    if (!event.target.closest('.action-menu-container')) {
      activeActionMenu.value = null
    }
  })
})

// Функция для загрузки вакансий (в реальном приложении)
const loadJobs = async (page = 1) => {
  try {
    // В реальном приложении здесь будет API-запрос
    // const token = localStorage.getItem('token')
    // const response = await fetch(`http://localhost:8000/api/admin/jobs?page=${page}`, {
    //   headers: {
    //     'Authorization': `Bearer ${token}`
    //   }
    // })
    // 
    // if (!response.ok) {
    //   throw new Error('Не удалось загрузить вакансии')
    // }
    // 
    // const data = await response.json()
    // jobs.value = data.data
    // jobs.current_page = data.current_page
    // jobs.prev_page_url = data.prev_page_url
    // jobs.next_page_url = data.next_page_url
    // jobs.total = data.total
  } catch (error) {
    notification.error('Ошибка при загрузке вакансий: ' + error.message)
    console.error('Ошибка при загрузке вакансий:', error)
  }
}
</script>
