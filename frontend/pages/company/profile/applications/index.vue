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
          
          <h1 class="text-2xl font-bold mb-6">Управление откликами на вакансии</h1>
        </div>
      </header>
      
      <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Фильтры -->
          <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex flex-wrap gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select v-model="filters.status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">Все статусы</option>
                  <option value="pending">На рассмотрении</option>
                  <option value="interview">Назначено собеседование</option>
                  <option value="reserve">В резерве</option>
                  <option value="rejected">Отклонено</option>
                  <option value="hired">Трудоустроен</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Вакансия</label>
                <select v-model="filters.jobId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">Все вакансии</option>
                  <option v-for="job in jobs" :key="job.id" :value="job.id">{{ job.title }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Период</label>
                <select v-model="filters.period" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">Все время</option>
                  <option value="week">За неделю</option>
                  <option value="month">За месяц</option>
                  <option value="quarter">За 3 месяца</option>
                  <option value="year">За год</option>
                </select>
              </div>
              <div class="flex-grow">
              </div>
            </div>
          </div>
          
          <!-- Статистика -->
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div v-for="(stat, index) in statistics" :key="index" class="bg-white shadow rounded-lg p-4">
              <div class="text-sm font-medium text-gray-500">{{ stat.label }}</div>
              <div class="mt-1 text-3xl font-semibold text-gray-900">{{ stat.value }}</div>
            </div>
          </div>
          
          <!-- Таблица с откликами -->
          <DataTable 
            :items="filteredApplications" 
            :columns="tableColumns"
            emptyText="Нет откликов"
            emptyDescription="На ваши вакансии пока нет откликов от кандидатов."
          >
            <!-- Слот для колонки с кандидатом -->
            <template #candidate="{ item }">
              <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0">
                  <img v-if="item.user && item.user.avatar" class="h-10 w-10 rounded-full" :src="item.user.avatar" alt="" />
                  <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                    {{ getInitials(item.user?.fullName) }}
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ item.user?.fullName || 'Неизвестный кандидат' }}</div>
                  <div class="text-sm text-gray-500">{{ item.user?.email || 'Email не указан' }}</div>
                </div>
              </div>
            </template>
            
            <template #job="{ item }">
              <div class="text-sm text-gray-900">{{ item.job?.title || 'Вакансия не указана' }}</div>
            </template>
            
            <!-- Слот для колонки со статусом -->
            <template #status="{ item }">
              <span :class="[
                item.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                item.status === 'interview' ? 'bg-blue-100 text-blue-800' : 
                item.status === 'reserve' ? 'bg-purple-100 text-purple-800' : 
                item.status === 'rejected' ? 'bg-red-100 text-red-800' : 
                item.status === 'hired' ? 'bg-green-100 text-green-800' : 
                'bg-gray-100 text-gray-800',
                'px-2 py-1 text-xs font-medium rounded-full'
              ]">
                {{ 
                  item.status === 'pending' ? 'На рассмотрении' : 
                  item.status === 'interview' ? 'Собеседование' : 
                  item.status === 'reserve' ? 'В резерве' : 
                  item.status === 'rejected' ? 'Отклонено' : 
                  item.status === 'hired' ? 'Трудоустроен' : ''
                }}
              </span>
            </template>
            
            <!-- Слот для колонки с действиями -->
            <template #actions="{ item }">
              <div class="flex justify-end">
                <button @click="viewApplicationDetails(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                  Подробнее
                </button>
                <div class="relative inline-block text-left action-menu-container">
                  <button @click="toggleActionMenu(item.id, $event)" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                  </button>
                  <div v-if="activeActionMenu === item.id" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                    <div class="py-1">
                      <button @click="changeStatus(item, 'interview')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Назначить собеседование
                      </button>
                      <button @click="changeStatus(item, 'reserve')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Добавить в резерв
                      </button>
                      <button @click="changeStatus(item, 'rejected')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Отклонить
                      </button>
                      <button @click="changeStatus(item, 'hired')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Принять на работу
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </DataTable>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

// Режим администратора (для тестирования)
const isAdminMode = ref(route.query.admin === 'true')

// Возврат в админ-панель
const returnToAdmin = () => {
  router.push('/admin/applications')
}

// Активное меню действий
const activeActionMenu = ref(null)

// Обработчик клика для закрытия меню действий при клике вне его
const handleClickOutside = (event) => {
  if (activeActionMenu.value !== null) {
    // Проверяем, был ли клик вне контейнера меню
    const containers = document.querySelectorAll('.action-menu-container')
    let clickedOutside = true
    
    containers.forEach(container => {
      if (container.contains(event.target)) {
        clickedOutside = false
      }
    })
    
    if (clickedOutside) {
      activeActionMenu.value = null
    }
  }
}

// Добавляем и удаляем обработчик при монтировании/размонтировании компонента
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

// Переключение меню действий
const toggleActionMenu = (id, event) => {
  // Предотвращаем всплытие события, чтобы не сработал handleClickOutside
  event.stopPropagation()
  
  // Если меню уже открыто для этого элемента, закрываем его
  if (activeActionMenu.value === id) {
    activeActionMenu.value = null
  } else {
    // Иначе открываем меню для этого элемента
    activeActionMenu.value = id
  }
}

// Фильтры для откликов
const filters = ref({
  status: '',
  jobId: '',
  period: '',
  search: ''
})

// Колонки для таблицы
const tableColumns = [
  { key: 'candidate', label: 'Кандидат', sortable: true },
  { key: 'job', label: 'Вакансия', sortable: true },
  { key: 'created_at', label: 'Дата отклика', sortable: true },
  { key: 'status', label: 'Статус', sortable: true },
  { key: 'actions', label: 'Действия', sortable: false }
]

// Список вакансий компании (для фильтра)
const jobs = ref([
  { id: 1, title: 'Frontend-разработчик' },
  { id: 2, title: 'Backend-разработчик' },
  { id: 3, title: 'UI/UX дизайнер' },
  { id: 4, title: 'Project Manager' }
])

// Список откликов (в реальном приложении будет загружаться с сервера)
const applications = ref([
  {
    id: 1,
    job: {
      id: 1,
      title: 'Frontend-разработчик',
      company: 'ООО "Технологии будущего"',
      salary: '120 000 - 150 000 ₽',
      location: 'Москва, удаленно'
    },
    user: {
      id: 101,
      fullName: 'Иванов Иван',
      email: 'ivanov@example.com',
      phone: '+7 (999) 123-45-67',
      avatar: 'https://randomuser.me/api/portraits/men/1.jpg'
    },
    status: 'pending',
    created_at: '2023-05-15T10:30:00',
    updated_at: '2023-05-15T10:30:00',
    resume_url: 'https://example.com/resumes/ivanov_resume.pdf',
    cover_letter: 'Я опытный frontend-разработчик с 5-летним стажем...',
    feedback: '',
    status_history: [
      {
        status: 'pending',
        date: '2023-05-15T10:30:00',
        comment: 'Отклик получен'
      }
    ]
  },
  {
    id: 2,
    job: {
      id: 2,
      title: 'Backend-разработчик',
      company: 'ООО "Технологии будущего"',
      salary: '150 000 - 180 000 ₽',
      location: 'Москва, офис'
    },
    user: {
      id: 102,
      fullName: 'Петрова Анна',
      email: 'petrova@example.com',
      phone: '+7 (999) 234-56-78',
      avatar: 'https://randomuser.me/api/portraits/women/2.jpg'
    },
    status: 'interview',
    created_at: '2023-05-10T14:20:00',
    updated_at: '2023-05-12T11:15:00',
    resume_url: 'https://example.com/resumes/petrova_resume.pdf',
    cover_letter: 'Имею большой опыт разработки на Python и Node.js...',
    feedback: '',
    status_history: [
      {
        status: 'pending',
        date: '2023-05-10T14:20:00',
        comment: 'Отклик получен'
      },
      {
        status: 'interview',
        date: '2023-05-12T11:15:00',
        comment: 'Назначено собеседование'
      }
    ],
    interview: {
      date: '2023-05-20T15:00:00',
      location: 'Офис компании, Москва, ул. Примерная, д. 123',
      contact_person: 'Соколов Игорь',
      notes: 'Техническое собеседование с тимлидом'
    }
  },
  {
    id: 3,
    job: {
      id: 3,
      title: 'UI/UX дизайнер',
      company: 'ООО "Технологии будущего"',
      salary: '100 000 - 130 000 ₽',
      location: 'Москва, гибрид'
    },
    user: {
      id: 103,
      fullName: 'Сидоров Алексей',
      email: 'sidorov@example.com',
      phone: '+7 (999) 345-67-89',
      avatar: 'https://randomuser.me/api/portraits/men/3.jpg'
    },
    status: 'rejected',
    created_at: '2023-05-05T09:45:00',
    updated_at: '2023-05-08T16:30:00',
    resume_url: 'https://example.com/resumes/sidorov_resume.pdf',
    cover_letter: 'Я креативный дизайнер с опытом создания интерфейсов...',
    feedback: 'Недостаточный опыт работы с мобильными интерфейсами',
    status_history: [
      {
        status: 'pending',
        date: '2023-05-05T09:45:00',
        comment: 'Отклик получен'
      },
      {
        status: 'interview',
        date: '2023-05-06T12:00:00',
        comment: 'Назначено собеседование'
      },
      {
        status: 'rejected',
        date: '2023-05-08T16:30:00',
        comment: 'Отклонено'
      }
    ]
  },
  {
    id: 4,
    job: {
      id: 4,
      title: 'Project Manager',
      company: 'ООО "Технологии будущего"',
      salary: '180 000 - 220 000 ₽',
      location: 'Москва, офис'
    },
    user: {
      id: 104,
      fullName: 'Козлова Мария',
      email: 'kozlova@example.com',
      phone: '+7 (999) 456-78-90',
      avatar: 'https://randomuser.me/api/portraits/women/4.jpg'
    },
    status: 'hired',
    created_at: '2023-04-20T11:10:00',
    updated_at: '2023-05-05T14:45:00',
    resume_url: 'https://example.com/resumes/kozlova_resume.pdf',
    cover_letter: 'Имею 7-летний опыт управления IT-проектами...',
    feedback: 'Отличный кандидат с большим опытом управления проектами',
    status_history: [
      {
        status: 'pending',
        date: '2023-04-20T11:10:00',
        comment: 'Отклик получен'
      },
      {
        status: 'interview',
        date: '2023-04-25T13:00:00',
        comment: 'Назначено собеседование'
      },
      {
        status: 'interview',
        date: '2023-05-02T15:30:00',
        comment: 'Второе собеседование с CEO'
      },
      {
        status: 'hired',
        date: '2023-05-05T14:45:00',
        comment: 'Принято решение о найме'
      }
    ],
    interview: {
      date: '2023-05-02T15:30:00',
      location: 'Офис компании, Москва, ул. Примерная, д. 123',
      contact_person: 'Соколов Игорь',
      notes: 'Финальное собеседование с руководителем отдела'
    }
  }
])

// Вычисляемые свойства для фильтрации откликов
const filteredApplications = computed(() => {
  return applications.value.filter(app => {
    // Фильтр по статусу
    if (filters.value.status && app.status !== filters.value.status) {
      return false
    }
    
    // Фильтр по вакансии
    if (filters.value.jobId && app.job && app.job.id !== parseInt(filters.value.jobId)) {
      return false
    }
    
    // Фильтр по периоду
    if (filters.value.period) {
      const appDate = new Date(app.created_at)
      const now = new Date()
      let startDate
      
      switch (filters.value.period) {
        case 'week':
          startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7)
          break
        case 'month':
          startDate = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate())
          break
        case 'quarter':
          startDate = new Date(now.getFullYear(), now.getMonth() - 3, now.getDate())
          break
        case 'year':
          startDate = new Date(now.getFullYear() - 1, now.getMonth(), now.getDate())
          break
        default:
          startDate = new Date(0) // Начало эпохи, т.е. все даты
      }
      
      if (appDate < startDate) {
        return false
      }
    }
    
    // Фильтр по поиску (имя кандидата)
    if (filters.value.search && app.user && app.user.fullName && !app.user.fullName.toLowerCase().includes(filters.value.search.toLowerCase())) {
      return false
    }
    
    return true
  })
})

// Статистика по откликам
const statistics = computed(() => {
  const stats = {
    total: applications.value.length,
    pending: applications.value.filter(app => app.status === 'pending').length,
    interview: applications.value.filter(app => app.status === 'interview').length,
    reserve: applications.value.filter(app => app.status === 'reserve').length,
    rejected: applications.value.filter(app => app.status === 'rejected').length,
    hired: applications.value.filter(app => app.status === 'hired').length
  }
  
  return [
    { label: 'Всего откликов', value: stats.total },
    { label: 'На рассмотрении', value: stats.pending },
    { label: 'Собеседование', value: stats.interview },
    { label: 'В резерве', value: stats.reserve },
    { label: 'Отклонено', value: stats.rejected }
  ]
})

// Функция для получения инициалов из полного имени
const getInitials = (fullName) => {
  if (!fullName) return ''
  return fullName.split(' ')
    .map(name => name.charAt(0).toUpperCase())
    .join('')
}

// Переход на страницу деталей отклика
const viewApplicationDetails = (application) => {
  if (!application) {
    console.error('Ошибка: application не определен')
    return
  }
  
  // Перенаправляем на страницу деталей отклика
  router.push(`/company/profile/applications/${application.id}`)
}

// Изменение статуса отклика из меню действий
const changeStatus = (application, status) => {
  // Закрываем меню действий
  activeActionMenu.value = null
  
  // Перенаправляем на страницу деталей отклика
  // Добавляем параметр status в URL для автоматического открытия формы изменения статуса
  router.push(`/company/profile/applications/${application.id}?status=${status}`)
}
</script>
