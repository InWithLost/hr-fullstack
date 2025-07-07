<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление откликами на вакансии</h1>
            <div class="flex space-x-2">
              <button 
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="refreshApplications"
              >
                <span class="inline-block mr-1">↻</span>
                Обновить
              </button>
              <button 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                @click="exportToExcel"
              >
                <span class="inline-block mr-1">↓</span>
                Экспорт
              </button>
            </div>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <!-- Фильтры -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
              <div class="flex flex-wrap gap-4">
                <div class="w-full md:w-auto">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                  <select 
                    v-model="filters.status" 
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
                <div class="w-full md:w-auto">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Вакансия</label>
                  <select 
                    v-model="filters.jobId" 
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                  >
                    <option value="">Все вакансии</option>
                    <option v-for="job in jobs" :key="job.id" :value="job.id">{{ job.title }}</option>
                  </select>
                </div>
                <div class="w-full md:w-auto">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Период</label>
                  <select 
                    v-model="filters.period" 
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
                    v-model="filters.search" 
                    placeholder="Поиск по имени кандидата или названию вакансии" 
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  />
                </div>
              </div>
            </div>

            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
              <div 
                v-for="(stat, index) in statistics" 
                :key="index"
                class="bg-white p-4 rounded-lg shadow-sm"
              >
                <div class="text-sm text-gray-500">{{ stat.label }}</div>
                <div class="text-2xl font-bold mt-1">{{ stat.value }}</div>
              </div>
            </div>

            <!-- Таблица откликов с использованием компонента DataTable -->
            <DataTable 
              :items="filteredApplications" 
              :columns="tableColumns"
              :current-page="currentPage"
              :total-items="totalApplications"
              :per-page="pageSize"
              :show-pagination="true"
              @page-change="currentPage = $event"
              emptyText="Нет откликов"
              emptyDescription="Нет откликов, соответствующих выбранным фильтрам"
            >
              <!-- Слот для колонки с кандидатом -->
              <template #candidate="{ item }">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                    <span v-if="!item.user.avatar" class="text-gray-500">👤</span>
                    <img v-else :src="item.user.avatar" class="h-10 w-10 rounded-full object-cover" />
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ item.user.fullName }}</div>
                    <div class="text-sm text-gray-500">{{ item.user.email }}</div>
                  </div>
                </div>
              </template>
              
              <!-- Слот для колонки с вакансией -->
              <template #job="{ item }">
                <div class="text-sm text-gray-900">{{ item.job.title }}</div>
                <div class="text-sm text-gray-500">{{ item.job.company }}</div>
              </template>
              
              <!-- Слот для колонки со статусом -->
              <template #status="{ item }">
                <span 
                  :class="[
                    item.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                    item.status === 'interview' ? 'bg-blue-100 text-blue-800' : 
                    item.status === 'reserve' ? 'bg-purple-100 text-purple-800' : 
                    item.status === 'rejected' ? 'bg-red-100 text-red-800' : 
                    'bg-green-100 text-green-800',
                    'px-2 py-1 text-xs font-medium rounded-full'
                  ]"
                >
                  {{ 
                    item.status === 'pending' ? 'На рассмотрении' : 
                    item.status === 'interview' ? 'Назначено собеседование' : 
                    item.status === 'reserve' ? 'В резерве' : 
                    item.status === 'rejected' ? 'Отклонено' : 
                    'Трудоустроен'
                  }}
                </span>
              </template>
              
              <!-- Слот для колонки с действиями -->
              <template #actions="{ item }">
                <div class="relative action-menu-container">
                  <!-- Иконка троеточия -->
                  <button 
                    @click="toggleActionMenu(item.id, $event)" 
                    class="p-1 rounded-full hover:bg-gray-200 transition-colors"
                    title="Действия"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                  </button>
                  
                  <!-- Выпадающее меню -->
                  <div 
                    v-if="activeActionMenu === item.id" 
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border"
                  >
                    <div class="py-1">
                      <button
                        @click="viewApplicationDetails(item)"
                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                      >
                        Просмотреть
                      </button>
                      <button
                        @click="openStatusChange(item)"
                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                      >
                        Изменить статус
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </DataTable>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import AdminHeader from '~/components/AdminHeader.vue'
import DataTable from '~/components/DataTable.vue'

const router = useRouter()
const notification = useNotification()

// Определение колонок для таблицы
const tableColumns = [
  { key: 'candidate', label: 'Кандидат', align: 'left', sortable: true },
  { key: 'job', label: 'Вакансия', align: 'left', sortable: true },
  { 
    key: 'created_at', 
    label: 'Дата отклика', 
    align: 'left', 
    sortable: true,
    format: (value) => formatDate(value)
  },
  { key: 'status', label: 'Статус', align: 'left', sortable: true },
  { key: 'actions', label: 'Действия', align: 'right', sortable: false }
]

// Фильтры
const filters = ref({
  status: '',
  jobId: '',
  period: '',
  search: ''
})

// Пагинация
const currentPage = ref(1)
const pageSize = 10
const totalApplications = ref(0)
const totalPages = computed(() => Math.ceil(totalApplications.value / pageSize))
const paginationPages = computed(() => {
  const pages = []
  const maxVisiblePages = 5
  
  if (totalPages.value <= maxVisiblePages) {
    for (let i = 1; i <= totalPages.value; i++) {
      pages.push(i)
    }
  } else {
    let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2))
    let endPage = startPage + maxVisiblePages - 1
    
    if (endPage > totalPages.value) {
      endPage = totalPages.value
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
  }
  
  return pages
})

// Модальные окна
const showDetailsModal = ref(false)
const showStatusModal = ref(false)
const showInterviewForm = ref(false)
const showFeedbackForm = ref(false)
const selectedApplication = ref(null)
const newStatus = ref('')
const feedbackText = ref('')

// Для меню действий
const activeActionMenu = ref(null)

// Данные для формы собеседования
const interviewForm = ref({
  datetime: '',
  format: 'online',
  location: '',
  link: '',
  contact: '',
  notes: ''
})

// Данные
const applications = ref([])
const jobs = ref([])
const loading = ref(false)

// Статистика
const statistics = computed(() => [
  { 
    label: 'Всего откликов', 
    value: applications.value.length 
  },
  { 
    label: 'На рассмотрении', 
    value: applications.value.filter(app => app.status === 'pending').length 
  },
  { 
    label: 'Собеседования', 
    value: applications.value.filter(app => app.status === 'interview').length 
  },
  { 
    label: 'В резерве', 
    value: applications.value.filter(app => app.status === 'reserve').length 
  },
  { 
    label: 'Трудоустроено', 
    value: applications.value.filter(app => app.status === 'hired').length 
  }
])

// Фильтрация откликов
const filteredApplications = computed(() => {
  return applications.value.filter(app => {
    // Фильтр по статусу
    if (filters.value.status && app.status !== filters.value.status) {
      return false
    }
    
    // Фильтр по вакансии
    if (filters.value.jobId && app.job.id !== parseInt(filters.value.jobId)) {
      return false
    }
    
    // Фильтр по периоду
    if (filters.value.period) {
      const appDate = new Date(app.created_at)
      const now = new Date()
      
      if (filters.value.period === 'week' && (now - appDate) > 7 * 24 * 60 * 60 * 1000) {
        return false
      } else if (filters.value.period === 'month' && (now - appDate) > 30 * 24 * 60 * 60 * 1000) {
        return false
      } else if (filters.value.period === 'quarter' && (now - appDate) > 90 * 24 * 60 * 60 * 1000) {
        return false
      } else if (filters.value.period === 'year' && (now - appDate) > 365 * 24 * 60 * 60 * 1000) {
        return false
      }
    }
    
    // Поиск по имени кандидата или названию вакансии
    if (filters.value.search) {
      const searchLower = filters.value.search.toLowerCase()
      const nameMatch = app.user.fullName.toLowerCase().includes(searchLower)
      const jobMatch = app.job.title.toLowerCase().includes(searchLower)
      
      if (!nameMatch && !jobMatch) {
        return false
      }
    }
    
    return true
  }).slice((currentPage.value - 1) * pageSize, currentPage.value * pageSize)
})

// Проверка и обновление статусов откликов из localStorage
function checkAndUpdateApplicationStatuses() {
  if (!applications.value || applications.value.length === 0) return
  
  let hasChanges = false
  
  applications.value.forEach(app => {
    const savedStatus = localStorage.getItem(`application_status_${app.id}`)
    if (savedStatus && savedStatus !== app.status) {
      app.status = savedStatus
      hasChanges = true
    }
  })
  
  if (hasChanges) {
    notification.show('Статусы откликов обновлены', 'info')
  }
}

// Загрузка данных при монтировании компонента
onMounted(() => {
  loadApplications()
  loadJobs()
  
  // Проверяем, нужно ли обновить список откликов
  if (localStorage.getItem('applications_need_refresh') === 'true') {
    // Удаляем флаг
    localStorage.removeItem('applications_need_refresh')
    
    // Проверяем и обновляем статусы откликов
    setTimeout(() => {
      checkAndUpdateApplicationStatuses()
    }, 500)
  }
})

// Наблюдатель за изменением фильтров
watch(filters, () => {
  currentPage.value = 1
}, { deep: true })

// Форматирование даты
function formatDate(dateString) {
  const options = { day: 'numeric', month: 'long', year: 'numeric' }
  return new Date(dateString).toLocaleDateString('ru-RU', options)
}

// Форматирование даты и времени
function formatDateTime(dateString) {
  const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }
  return new Date(dateString).toLocaleDateString('ru-RU', options)
}

// Загрузка списка откликов
async function loadApplications() {
  loading.value = true
  try {
    // В реальном приложении здесь будет API запрос
    // const response = await fetch('http://localhost:8000/api/admin/applications', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // applications.value = await response.json()
    
    // Демо-данные
    setTimeout(() => {
      applications.value = [
        {
          id: 1,
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
            slug: 'therapist-moscow'
          },
          status: 'pending',
          created_at: '2025-05-10T14:30:00',
          feedback: null
        },
        {
          id: 2,
          user: {
            id: 102,
            fullName: 'Петрова Анна Сергеевна',
            email: 'petrova@example.com',
            phone: '+7 (900) 234-56-78',
            city: 'Санкт-Петербург',
            avatar: null
          },
          job: {
            id: 202,
            title: 'Медсестра в отделение кардиологии',
            company: 'Медицинский центр "Здоровье"',
            slug: 'nurse-cardiology'
          },
          status: 'interview',
          created_at: '2025-05-05T10:15:00',
          feedback: null,
          interview: {
            datetime: '2025-05-20T15:00:00',
            format: 'online',
            link: 'https://zoom.us/j/123456789',
            contact: 'Иванова Мария, HR-менеджер',
            notes: 'Подготовьте, пожалуйста, документы о вашем образовании и опыте работы.'
          }
        },
        {
          id: 3,
          user: {
            id: 103,
            fullName: 'Сидоров Алексей Петрович',
            email: 'sidorov@example.com',
            phone: '+7 (900) 345-67-89',
            city: 'Екатеринбург',
            avatar: null
          },
          job: {
            id: 203,
            title: 'Фармацевт',
            company: 'Аптечная сеть "Здоровье+"',
            slug: 'pharmacist'
          },
          status: 'rejected',
          created_at: '2025-04-28T09:45:00',
          feedback: 'К сожалению, мы выбрали кандидата с большим опытом работы. Благодарим за интерес к нашей компании!'
        },
        {
          id: 4,
          user: {
            id: 104,
            fullName: 'Козлова Елена Александровна',
            email: 'kozlova@example.com',
            phone: '+7 (900) 456-78-90',
            city: 'Новосибирск',
            avatar: null
          },
          job: {
            id: 204,
            title: 'Врач-педиатр',
            company: 'Детская поликлиника №3',
            slug: 'pediatrician'
          },
          status: 'reserve',
          created_at: '2025-05-01T11:20:00',
          feedback: 'Ваша кандидатура включена в резерв. Мы свяжемся с вами, если появится подходящая вакансия.'
        },
        {
          id: 5,
          user: {
            id: 105,
            fullName: 'Новиков Дмитрий Игоревич',
            email: 'novikov@example.com',
            phone: '+7 (900) 567-89-01',
            city: 'Казань',
            avatar: null
          },
          job: {
            id: 205,
            title: 'Главный врач',
            company: 'Частная клиника "МедЭксперт"',
            slug: 'chief-physician'
          },
          status: 'hired',
          created_at: '2025-04-15T13:10:00',
          feedback: 'Поздравляем! Вы приняты на должность. Наш HR-менеджер свяжется с вами для обсуждения деталей.'
        }
      ]
      
      totalApplications.value = applications.value.length
      loading.value = false
      
      // Проверяем и обновляем статусы откликов из localStorage
      checkAndUpdateApplicationStatuses()
    }, 500)
  } catch (error) {
    console.error('Ошибка при загрузке откликов:', error)
    notification.show('Не удалось загрузить отклики', 'error')
    loading.value = false
  }
}

// Загрузка списка вакансий
async function loadJobs() {
  try {
    // В реальном приложении здесь будет API запрос
    // const response = await fetch('http://localhost:8000/api/admin/jobs', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // jobs.value = await response.json()
    
    // Демо-данные
    jobs.value = [
      { id: 201, title: 'Врач-терапевт' },
      { id: 202, title: 'Медсестра в отделение кардиологии' },
      { id: 203, title: 'Фармацевт' },
      { id: 204, title: 'Врач-педиатр' },
      { id: 205, title: 'Главный врач' }
    ]
  } catch (error) {
    console.error('Ошибка при загрузке вакансий:', error)
    notification.show('Не удалось загрузить список вакансий', 'error')
  }
}

// Обновление данных
function refreshApplications() {
  loadApplications()
  notification.show('Данные обновлены', 'success')
}

// Экспорт данных в Excel
function exportToExcel() {
  notification.show('Экспорт данных в Excel...', 'info')
  // В реальном приложении здесь будет логика экспорта
  setTimeout(() => {
    notification.show('Данные успешно экспортированы', 'success')
  }, 1500)
}

// Открытие деталей отклика
function openApplicationDetails(application) {
  selectedApplication.value = application
  newStatus.value = application.status
  feedbackText.value = application.feedback || ''
  
  if (application.interview) {
    interviewForm.value = { ...application.interview }
  } else {
    interviewForm.value = {
      datetime: '',
      format: 'online',
      location: '',
      link: '',
      contact: '',
      notes: ''
    }
  }
  
  showDetailsModal.value = true
  showInterviewForm.value = false
  showFeedbackForm.value = false
}

// Переход на страницу детального просмотра отклика
function viewApplicationDetails(application) {
  router.push({
    path: '/admin/view-application',
    query: { id: application.id }
  })
}

// Открытие модального окна изменения статуса
function openStatusChange(application) {
  selectedApplication.value = application
  newStatus.value = application.status
  showStatusModal.value = true
}

// Обновление статуса отклика
async function updateApplicationStatus() {
  try {
    // В реальном приложении здесь будет API запрос
    // await fetch(`http://localhost:8000/api/admin/applications/${selectedApplication.value.id}/status`, {
    //   method: 'PUT',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify({ status: newStatus.value })
    // })
    
    // Для демонстрации просто обновляем локальные данные
    selectedApplication.value.status = newStatus.value
    
    // Если статус изменен на "Назначено собеседование", но детали собеседования не заданы
    if (newStatus.value === 'interview' && !selectedApplication.value.interview) {
      showInterviewForm.value = true
    } else {
      showStatusModal.value = false
      showDetailsModal.value = false
    }
    
    notification.show('Статус успешно обновлен', 'success')
  } catch (error) {
    console.error('Ошибка при обновлении статуса:', error)
    notification.show('Не удалось обновить статус', 'error')
  }
}

// Сохранение деталей собеседования
async function saveInterviewDetails() {
  try {
    // В реальном приложении здесь будет API запрос
    // await fetch(`http://localhost:8000/api/admin/applications/${selectedApplication.value.id}/interview`, {
    //   method: 'PUT',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify(interviewForm.value)
    // })
    
    // Для демонстрации просто обновляем локальные данные
    selectedApplication.value.interview = { ...interviewForm.value }
    
    showInterviewForm.value = false
    notification.show('Детали собеседования сохранены', 'success')
  } catch (error) {
    console.error('Ошибка при сохранении деталей собеседования:', error)
    notification.show('Не удалось сохранить детали собеседования', 'error')
  }
}

// Сохранение отзыва
async function saveFeedback() {
  try {
    // В реальном приложении здесь будет API запрос
    // await fetch(`http://localhost:8000/api/admin/applications/${selectedApplication.value.id}/feedback`, {
    //   method: 'PUT',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify({ feedback: feedbackText.value })
    // })
    
    // Для демонстрации просто обновляем локальные данные
    selectedApplication.value.feedback = feedbackText.value
    
    showFeedbackForm.value = false
    notification.show('Отзыв сохранен', 'success')
  } catch (error) {
    console.error('Ошибка при сохранении отзыва:', error)
    notification.show('Не удалось сохранить отзыв', 'error')
  }
}

// Просмотр профиля пользователя
function viewUserProfile(userId) {
  router.push(`/admin/users/${userId}`)
}

// Просмотр вакансии
function viewJobDetails(jobId) {
  router.push(`/admin/jobs/${jobId}`)
}

// Функция для переключения меню действий
function toggleActionMenu(id, event) {
  if (event) {
    event.stopPropagation()
  }
  if (activeActionMenu.value === id) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = id
  }
}

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
</script>
