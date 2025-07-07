<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление вакансиями</h1>
            <NuxtLink
              to="/admin/job-create"
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
                  type="checkbox" 
                  v-model="statusFilters.active"
                  class="form-checkbox h-4 w-4 text-indigo-600 rounded"
                />
                <span class="ml-2 text-sm text-gray-700">Активные</span>
              </label>
              <label class="inline-flex items-center ml-2">
                <input 
                  type="checkbox" 
                  v-model="statusFilters.inactive"
                  class="form-checkbox h-4 w-4 text-indigo-600 rounded"
                />
                <span class="ml-2 text-sm text-gray-700">Неактивные</span>
              </label>
              <label class="inline-flex items-center ml-2">
                <input 
                  type="checkbox" 
                  v-model="statusFilters.moderation"
                  class="form-checkbox h-4 w-4 text-indigo-600 rounded"
                />
                <span class="ml-2 text-sm text-gray-700">На модерации</span>
                <span v-if="moderationCount > 0" class="ml-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">{{ moderationCount }}</span>
              </label>
            </div>
          </div>

          <!-- Список вакансий -->
          <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Название
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Компания
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Специализация
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Тип
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
                  <tr v-for="job in filteredJobs" :key="job.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ job.title }}</div>
                      <div class="text-sm text-gray-500">{{ job.salary }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ job.company?.name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ job.specialty?.name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span v-if="job.is_candidate_request" 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                      >
                        Заявка на подбор
                      </span>
                      <div v-if="job.is_candidate_request && job.contact_name" class="text-xs text-gray-500 mt-1">
                        {{ job.contact_name }}
                      </div>
                      <div v-if="job.is_candidate_request && job.contact_phone" class="text-xs text-gray-500">
                        {{ job.contact_phone }}
                      </div>
                      <span v-if="!job.is_candidate_request" class="text-sm text-gray-500">Обычная вакансия</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="[
                          job.status === 'active'
                            ? 'bg-green-100 text-green-800'
                            : job.status === 'inactive'
                              ? 'bg-gray-100 text-gray-800'
                              : 'bg-yellow-100 text-yellow-800',
                          'px-2 inline-flex text-xs leading-5 font-semibold rounded-full'
                        ]"
                      >
                        {{ 
                          job.status === 'active' ? 'Активна' : 
                          job.status === 'inactive' ? 'Неактивна' : 'На модерации' 
                        }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="relative action-menu-container">
                        <!-- Иконка троеточия -->
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
                              :to="`/admin/job-edit?id=${job.id}`"
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
                      Показано <span class="font-medium">{{ jobs.from }}</span> по <span class="font-medium">{{ jobs.to }}</span> из <span class="font-medium">{{ jobs.total }}</span> вакансий
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button 
                        @click="loadJobs(jobs.current_page - 1)" 
                        :disabled="!jobs.prev_page_url" 
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !jobs.prev_page_url }"
                      >
                        <span class="sr-only">Предыдущая</span>
                        &lt;
                      </button>
                      <button 
                        v-for="page in getPageNumbers()" 
                        :key="page" 
                        @click="loadJobs(page)" 
                        :class="[
                          page === jobs.current_page ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]"
                      >
                        {{ page }}
                      </button>
                      <button 
                        @click="loadJobs(jobs.current_page + 1)" 
                        :disabled="!jobs.next_page_url" 
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !jobs.next_page_url }"
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

    <!-- Модальное окно удалено, теперь используются отдельные страницы -->
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import AdminHeader from '~/components/AdminHeader.vue'

definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

// Данные вакансий
const jobs = ref({
  data: [
    {
      id: 1,
      title: 'Врач-терапевт',
      company: { id: 1, name: 'Городская клиника №1' },
      specialty: { id: 1, name: 'Терапия' },
      status: 'active',
      description: 'Прием пациентов, диагностика и лечение заболеваний',
      requirements: 'Высшее медицинское образование, опыт работы от 3 лет',
      salary: '80 000 - 120 000 руб.',
      created_at: '2025-04-15T10:30:00Z',
      updated_at: '2025-05-01T14:45:00Z'
    },
    {
      id: 2,
      title: 'Медсестра в отделение кардиологии',
      company: { id: 2, name: 'Медицинский центр "Здоровье"' },
      specialty: { id: 2, name: 'Сестринское дело' },
      status: 'active',
      created_at: '2025-04-20T09:15:00Z',
      updated_at: '2025-05-02T11:30:00Z'
    },
    {
      id: 3,
      title: 'Врач-педиатр',
      company: { id: 1, name: 'Городская клиника №1' },
      specialty: { id: 3, name: 'Педиатрия' },
      status: 'moderation',
      created_at: '2025-04-25T14:20:00Z',
      updated_at: '2025-05-03T09:10:00Z'
    }
  ],
  current_page: 1,
  from: 1,
  to: 3,
  total: 10,
  per_page: 10,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
})

// Компании и специальности
const companies = ref([])
const specialties = ref([])

// Фильтры
const searchQuery = ref('')
const companyFilter = ref('')
const activeActionMenu = ref(null)
const statusFilters = ref({
  active: true,
  inactive: true,
  moderation: true
})

// Функции для работы с выпадающим меню
function toggleActionMenu(jobId) {
  if (activeActionMenu.value === jobId) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = jobId
  }
}

function isLastRows(jobId) {
  const index = filteredJobs.value.findIndex(j => j.id === jobId)
  return index >= filteredJobs.value.length - 2
}

// Вычисляемые свойства
const filteredJobs = computed(() => {
  let filtered = jobs.value.data
  
  // Фильтр по поиску
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(job => 
      job.title.toLowerCase().includes(query) || 
      (job.company?.name && job.company.name.toLowerCase().includes(query))
    )
  }
  
  // Фильтр по компании
  if (companyFilter.value) {
    filtered = filtered.filter(job => job.company?.id === parseInt(companyFilter.value))
  }
  
  // Фильтр по статусу
  const activeStatuses = []
  if (statusFilters.value.active) activeStatuses.push('active')
  if (statusFilters.value.inactive) activeStatuses.push('inactive')
  if (statusFilters.value.moderation) activeStatuses.push('moderation')
  
  filtered = filtered.filter(job => activeStatuses.includes(job.status))
  
  return filtered
})

const moderationCount = computed(() => {
  return jobs.value.data.filter(job => job.status === 'moderation').length
})

// Функции для пагинации
function getPageNumbers() {
  const pages = []
  const maxVisiblePages = 5
  
  if (!jobs.value.last_page) return [1]
  
  if (jobs.value.last_page <= maxVisiblePages) {
    for (let i = 1; i <= jobs.value.last_page; i++) {
      pages.push(i)
    }
  } else {
    let startPage = Math.max(1, jobs.value.current_page - Math.floor(maxVisiblePages / 2))
    let endPage = startPage + maxVisiblePages - 1
    
    if (endPage > jobs.value.last_page) {
      endPage = jobs.value.last_page
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
  }
  
  return pages
}

// API функции
async function loadJobs(page = 1) {
  try {
    // В реальном приложении здесь был бы API-запрос
    // const response = await fetch(`http://localhost:8000/api/admin/jobs?page=${page}`, {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // const data = await response.json()
    // jobs.value = data
    
    // Для демонстрации просто обновляем текущую страницу
    jobs.value.current_page = page
  } catch (error) {
    console.error('Ошибка при загрузке вакансий:', error)
  }
}

async function loadCompanies() {
  try {
    // В реальном приложении здесь был бы API-запрос
    // const response = await fetch('http://localhost:8000/api/admin/companies', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // const data = await response.json()
    // companies.value = data.data
    
    // Демо-данные для компаний
    companies.value = [
      {
        id: 1,
        name: 'Городская клиника №1',
        inn: '7701234567',
        contact_name: 'Иванов Иван Иванович',
        position: 'Главный врач',
        phone: '+7 (495) 123-45-67',
        email: 'info@clinic1.ru',
        created_at: '2023-01-15T10:00:00.000Z',
        jobs_count: 5
      },
      {
        id: 2,
        name: 'Медицинский центр "Здоровье"',
        inn: '7702345678',
        contact_name: 'Петров Петр Петрович',
        position: 'Директор',
        phone: '+7 (495) 234-56-78',
        email: 'info@zdorovie.ru',
        created_at: '2023-02-20T11:30:00.000Z',
        jobs_count: 3
      },
      {
        id: 3,
        name: 'Стоматологическая клиника "Дента-Эксперт"',
        inn: '7703456789',
        contact_name: 'Сидоров Алексей Владимирович',
        position: 'Генеральный директор',
        phone: '+7 (495) 345-67-89',
        email: 'info@dentalexpert.ru',
        created_at: '2023-03-10T09:15:00.000Z',
        jobs_count: 2
      }
    ]
  } catch (error) {
    console.error('Ошибка при загрузке компаний:', error)
  }
}

async function loadSpecialties() {
  try {
    // В реальном приложении здесь был бы API-запрос
    // const response = await fetch('http://localhost:8000/api/admin/specialties', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // const data = await response.json()
    // specialties.value = data.data
    
    // Демо-данные для специальностей
    specialties.value = [
      { id: 1, name: 'Терапия' },
      { id: 2, name: 'Хирургия' },
      { id: 3, name: 'Педиатрия' },
      { id: 4, name: 'Стоматология' },
      { id: 5, name: 'Неврология' }
    ]
  } catch (error) {
    console.error('Ошибка при загрузке специальностей:', error)
  }
}

async function deleteJob(job) {
  if (!confirm('Вы уверены, что хотите удалить эту вакансию?')) return
  activeActionMenu.value = null

  try {
    // В реальном приложении здесь был бы API-запрос
    // await fetch(`http://localhost:8000/api/admin/jobs/${job.id}`, {
    //   method: 'DELETE',
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    
    // Для демонстрации просто удаляем из локального массива
    jobs.value.data = jobs.value.data.filter(j => j.id !== job.id)
    alert('Вакансия успешно удалена!')
  } catch (error) {
    console.error('Ошибка при удалении вакансии:', error)
  }
}

// Отслеживание изменений в поиске и фильтрах
watch([searchQuery, statusFilters, companyFilter], () => {
  loadJobs(1)
}, { deep: true })

// Инициализация
onMounted(async () => {
  await loadJobs()
  await loadCompanies()
  await loadSpecialties()
  
  // Добавляем обработчик клика для закрытия меню при клике вне его
  document.addEventListener('click', (e) => {
    if (activeActionMenu.value && !e.target.closest('.action-menu-container')) {
      activeActionMenu.value = null
    }
  })
})
</script>
