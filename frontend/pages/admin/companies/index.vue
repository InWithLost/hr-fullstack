<!--
  Страница управления компаниями в административной панели.
  Отображает список всех компаний с возможностью фильтрации и поиска.
  Позволяет создавать новые компании, редактировать и удалять существующие.
  Реализована функция "Управлять компанией", которая позволяет администратору войти в профиль
  компании и управлять ею от имени управляющего.
-->
<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление компаниями</h1>
            <NuxtLink
              to="/admin/companies/create"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Добавить компанию
            </NuxtLink>
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
              placeholder="Поиск компаний..."
              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
            />
          </div>

          <!-- Список компаний -->
          <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Название организации
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Контактное лицо
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Телефон
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Дата создания
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Статус
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Кол-во вакансий
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                      <span class="sr-only">Действия</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="company in companies.data" :key="company.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ company.name }}</div>
                      <div class="text-sm text-gray-500">ИНН: {{ company.inn }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ company.contact_name }}</div>
                      <div class="text-sm text-gray-500">{{ company.position }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ company.phone }}</div>
                      <div class="text-sm text-gray-500">{{ company.email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ formatDate(company.created_at) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="{
                          'bg-yellow-100 text-yellow-800': company.status === 'moderation',
                          'bg-green-100 text-green-800': company.status === 'active',
                          'bg-red-100 text-red-800': company.status === 'inactive',
                          'bg-gray-100 text-gray-800': !company.status
                        }"
                      >
                        {{ getStatusName(company.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="text-sm text-gray-900">{{ company.jobs_count }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="relative action-menu-container">
                        <!-- Иконка троеточия -->
                        <button 
                          @click="toggleActionMenu(company.id)" 
                          class="p-1 rounded-full hover:bg-gray-200 transition-colors"
                          title="Действия"
                        >
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                          </svg>
                        </button>
                        
                        <!-- Выпадающее меню -->
                        <div 
                          v-if="activeActionMenu === company.id" 
                          class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border"
                          :class="{
                            'bottom-full mb-2': isLastRows(company.id)
                          }"
                          style="z-index: 100;"
                        >
                          <div class="py-1">
                            <NuxtLink
                              :to="`/admin/companies/${company.id}/edit`"
                              class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                              @click="activeActionMenu = null"
                            >
                              Редактировать
                            </NuxtLink>
                            <button
                              @click="manageCompany(company)"
                              class="w-full text-left block px-4 py-2 text-sm text-blue-600 hover:bg-gray-100"
                            >
                              Управлять компанией
                            </button>
                            <button
                              @click="viewJobs(company)"
                              class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                              Просмотр вакансий
                            </button>
                            <button
                              @click="deleteCompany(company)"
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
              <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                  <button 
                    @click="loadCompanies(companies.current_page - 1)" 
                    :disabled="!companies.prev_page_url" 
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': !companies.prev_page_url }"
                  >
                    Назад
                  </button>
                  <button 
                    @click="loadCompanies(companies.current_page + 1)" 
                    :disabled="!companies.next_page_url" 
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': !companies.next_page_url }"
                  >
                    Вперед
                  </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Показано <span class="font-medium">{{ companies.from || 0 }}</span> - <span class="font-medium">{{ companies.to || 0 }}</span> из <span class="font-medium">{{ companies.total || 0 }}</span> компаний
                    </p>
                  </div>
                  <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                      <button 
                        @click="loadCompanies(companies.current_page - 1)" 
                        :disabled="!companies.prev_page_url" 
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !companies.prev_page_url }"
                      >
                        <span class="sr-only">Предыдущая</span>
                        &lt;
                      </button>
                      <button 
                        v-for="page in getPageNumbers()" 
                        :key="page" 
                        @click="loadCompanies(page)" 
                        :class="[
                          page === companies.current_page
                            ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]"
                      >
                        {{ page }}
                      </button>
                      <button 
                        @click="loadCompanies(companies.current_page + 1)" 
                        :disabled="!companies.next_page_url" 
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        :class="{ 'opacity-50 cursor-not-allowed': !companies.next_page_url }"
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
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'

definePageMeta({ middleware: 'admin' })

const router = useRouter()

const companies = ref({
  data: [],
  current_page: 1,
  prev_page_url: null,
  next_page_url: null
})

const searchQuery = ref('')
const activeActionMenu = ref(null)

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

// Загрузка компаний
const loadCompanies = async (page = 1) => {
  try {
    // В реальном приложении здесь будет API запрос
    // const response = await fetch(`http://localhost:8000/api/admin/companies?page=${page}&search=${searchQuery.value}`, {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // companies.value = await response.json()
    
    // Демо-данные
    const demoCompanies = [
      {
        id: 1,
        name: 'ООО "МедТехника"',
        inn: '7701234567',
        contact_name: 'Иванов Иван Иванович',
        position: 'Директор по персоналу',
        phone: '+7 (495) 123-45-67',
        email: 'ivanov@medtechnika.ru',
        created_at: '2025-03-15T10:30:00Z',
        status: 'active',
        jobs_count: 3
      },
      {
        id: 2,
        name: 'ЗАО "ФармаПлюс"',
        inn: '7702345678',
        contact_name: 'Петрова Елена Сергеевна',
        position: 'HR-менеджер',
        phone: '+7 (495) 234-56-78',
        email: 'petrova@pharmaplus.ru',
        created_at: '2025-04-10T14:15:00Z',
        status: 'moderation',
        jobs_count: 5
      },
      {
        id: 3,
        name: 'АО "МедСервис"',
        inn: '7703456789',
        contact_name: 'Сидоров Алексей Петрович',
        position: 'Руководитель отдела кадров',
        phone: '+7 (495) 345-67-89',
        email: 'sidorov@medservice.ru',
        created_at: '2025-05-05T09:45:00Z',
        status: 'inactive',
        jobs_count: 2
      },
      {
        id: 4,
        name: 'ООО "Клиника Здоровье"',
        inn: '7704567890',
        contact_name: 'Козлова Мария Александровна',
        position: 'Менеджер по персоналу',
        phone: '+7 (495) 456-78-90',
        email: 'kozlova@zdorovie.ru',
        created_at: '2025-05-12T11:20:00Z',
        status: 'moderation',
        jobs_count: 4
      }
    ]
    
    // Фильтрация по поисковому запросу
    let filteredCompanies = demoCompanies
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      filteredCompanies = demoCompanies.filter(company => 
        company.name.toLowerCase().includes(query) || 
        company.inn.includes(query) ||
        company.contact_name.toLowerCase().includes(query) ||
        company.email.toLowerCase().includes(query)
      )
    }
    
    // Имитируем ответ API с пагинацией
    const perPage = 10
    const total = filteredCompanies.length
    const lastPage = Math.ceil(total / perPage)
    const from = (page - 1) * perPage + 1
    const to = Math.min(page * perPage, total)
    
    companies.value = {
      data: filteredCompanies,
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
    console.error('Ошибка при загрузке компаний:', error)
  }
}

// Управление компанией из админки
const manageCompany = (company) => {
  // Сохраняем данные о компании и режиме администратора в localStorage
  const adminCompanyData = {
    company_id: company.id,
    company_name: company.name,
    admin_mode: true,
    timestamp: new Date().toISOString()
  }
  
  localStorage.setItem('admin_managing_company', JSON.stringify(adminCompanyData))
  
  // Переходим на страницу профиля компании
  router.push('/company/profile')
  
  activeActionMenu.value = null
}

// Просмотр вакансий компании
const viewJobs = (company) => {
  // В реальном приложении здесь будет переход на страницу с вакансиями компании
  router.push(`/admin/companies/${company.id}/jobs`)
  activeActionMenu.value = null
}

// Удаление компании
const deleteCompany = async (company) => {
  if (!confirm(`Вы уверены, что хотите удалить компанию "${company.name}"?`)) return
  activeActionMenu.value = null

  try {
    // В реальном приложении здесь будет API запрос
    // await fetch(`http://localhost:8000/api/admin/companies/${company.id}`, {
    //   method: 'DELETE',
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    
    // Для демо-режима просто показываем успешное сообщение
    alert(`Компания "${company.name}" успешно удалена!`)
    
    loadCompanies(companies.value.current_page)
  } catch (error) {
    console.error('Ошибка при удалении компании:', error)
  }
}

// Получение названия статуса по коду
const getStatusName = (statusCode) => {
  const statuses = {
    'moderation': 'На модерации',
    'active': 'Активна',
    'inactive': 'Неактивна'
  }
  return statuses[statusCode] || 'Не указан'
}

// Функция для генерации номеров страниц в пагинации
const getPageNumbers = () => {
  const pages = []
  const maxVisiblePages = 5
  
  if (!companies.value.last_page) return [1]
  
  if (companies.value.last_page <= maxVisiblePages) {
    for (let i = 1; i <= companies.value.last_page; i++) {
      pages.push(i)
    }
  } else {
    let startPage = Math.max(1, companies.value.current_page - Math.floor(maxVisiblePages / 2))
    let endPage = startPage + maxVisiblePages - 1
    
    if (endPage > companies.value.last_page) {
      endPage = companies.value.last_page
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
  }
  
  return pages
}

// Функция для переключения меню действий
const toggleActionMenu = (companyId) => {
  if (activeActionMenu.value === companyId) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = companyId
  }
}

// Функция для определения, является ли компания одной из последних в списке
const isLastRows = (companyId) => {
  const index = companies.value.data.findIndex(c => c.id === companyId)
  return index >= companies.value.data.length - 2
}

// Отслеживание изменений в поиске
watch(searchQuery, () => {
  loadCompanies()
})

onMounted(() => {
  loadCompanies()
  
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
