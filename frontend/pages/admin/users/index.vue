<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление пользователями</h1>
            <NuxtLink
              to="/admin/users/create"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Добавить пользователя
            </NuxtLink>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <!-- Фильтры -->
            <div class="mb-6 flex space-x-4">
              <div class="max-w-lg">
                <label for="search" class="sr-only">Поиск пользователей</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <input
                    id="search"
                    v-model="searchQuery"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Поиск по имени или email"
                    type="search"
                  />
                </div>
              </div>
              
              <!-- Фильтр по ролям -->
              <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Роль:</label>
                <select
                  v-model="roleFilter"
                  class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                  <option value="">Все</option>
                  <option value="admin">Администраторы</option>
                  <option value="user">Пользователи</option>
                </select>
              </div>

              <!-- Фильтр по статусу поиска работы -->
              <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Статус:</label>
                <select
                  v-model="jobSearchStatusFilter"
                  class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                  <option value="">Все</option>
                  <option value="active">Активно ищет</option>
                  <option value="passive">Рассматривает предложения</option>
                  <option value="not_looking">Не ищет работу</option>
                </select>
              </div>

              <!-- Фильтр по дате регистрации -->
              <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Дата регистрации:</label>
                <div class="flex items-center space-x-2">
                  <input
                    type="date"
                    v-model="registrationDateFrom"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                  />
                  <span class="text-gray-500">—</span>
                  <input
                    type="date"
                    v-model="registrationDateTo"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                  />
                </div>
              </div>
            </div>

            <!-- Таблица пользователей -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <!-- Колонка с чекбоксами удалена -->
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Пользователь
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Роль
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Дата регистрации
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Последний вход
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Статус поиска работы
                      </th>
                      <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in filteredUsers" :key="user.id" :class="{'hover:bg-gray-50': !user.is_blocked, 'bg-red-50 hover:bg-red-100': user.is_blocked}">
                      <!-- Ячейка с чекбоксом удалена -->
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10">
                            <img
                              class="h-10 w-10 rounded-full"
                              :src="user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name)"
                              :alt="user.name"
                            />
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                              {{ user.name }}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ user.email }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          :class="[
                            user.is_admin
                              ? 'bg-green-100 text-green-800'
                              : 'bg-gray-100 text-gray-800',
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                          ]"
                        >
                          {{ user.is_admin ? 'Администратор' : 'Пользователь' }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDate(user.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ user.last_login_at ? formatDate(user.last_login_at) : 'Никогда' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          :class="[
                            user.job_search_status === 'active' ? 'bg-green-100 text-green-800' :
                            user.job_search_status === 'passive' ? 'bg-yellow-100 text-yellow-800' :
                            'bg-gray-100 text-gray-800',
                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                          ]"
                        >
                          {{ 
                            user.job_search_status === 'active' ? 'Активно ищет' :
                            user.job_search_status === 'passive' ? 'Рассматривает предложения' :
                            'Не ищет работу'
                          }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="relative action-menu-container">
                          <!-- Иконка троеточия -->
                          <button 
                            @click="toggleActionMenu(user.id)" 
                            class="p-1 rounded-full hover:bg-gray-200 transition-colors"
                            title="Действия"
                            :disabled="isProcessing"
                          >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                          </button>
                          
                          <!-- Выпадающее меню -->
                          <div 
                            v-if="activeActionMenu === user.id" 
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border"
                            :class="{
                              'bottom-full mb-2': isLastRows(user.id)
                            }"
                            style="z-index: 100;"
                          >
                            <div class="py-1">
                              <NuxtLink
                                :to="`/admin/users/${user.id}/profile`"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                              >
                                Просмотр профиля
                              </NuxtLink>
                              <button
                                @click="user.is_blocked ? showUnblockConfirmation(user) : showBlockConfirmation(user)"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                :disabled="isProcessing"
                              >
                                {{ user.is_blocked ? 'Разблокировать' : 'Заблокировать' }}
                              </button>
                              <button
                                @click="showAdminConfirmation(user)"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                :disabled="isProcessing"
                              >
                                {{ user.is_admin ? 'Убрать права админа' : 'Сделать админом' }}
                              </button>
                              <button
                                @click="showDeleteConfirmation(user)"
                                class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                :disabled="isProcessing"
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
                      @click="currentPage--" 
                      :disabled="currentPage === 1" 
                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                      :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                    >
                      Назад
                    </button>
                    <button 
                      @click="currentPage++" 
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
                        Показано <span class="font-medium">{{ (currentPage - 1) * pageSize + 1 }}</span> - <span class="font-medium">{{ Math.min(currentPage * pageSize, totalUsers) }}</span> из <span class="font-medium">{{ totalUsers }}</span> пользователей
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
                          v-for="page in paginationPages" 
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
        </div>
      </main>
    </div>
  </div>

  <!-- Модальное окно подтверждения удаления пользователя -->
  <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
      <h3 class="text-xl font-bold mb-4">Подтверждение удаления</h3>
      <p class="mb-6">Вы уверены, что хотите удалить пользователя <span class="font-semibold">{{ userToDelete?.name }}</span>? Это действие нельзя отменить.</p>
      <div class="flex justify-end space-x-3">
        <button 
          @click="cancelDelete" 
          class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
          :disabled="isProcessing"
        >
          Отмена
        </button>
        <button 
          @click="deleteUser" 
          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
          :disabled="isProcessing"
        >
          Удалить
        </button>
      </div>
    </div>
  </div>

  <!-- Модальные окна для массовых действий удалены -->
  
  <!-- Модальное окно подтверждения блокировки пользователя -->
  <div v-if="showBlockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
      <h3 class="text-xl font-bold mb-4">Подтверждение блокировки</h3>
      <p class="mb-6">Вы уверены, что хотите заблокировать пользователя <span class="font-semibold">{{ userToBlock?.name }}</span>?</p>
      <div class="flex justify-end space-x-3">
        <button 
          @click="cancelBlock" 
          class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
          :disabled="isProcessing"
        >
          Отмена
        </button>
        <button 
          @click="blockUser" 
          class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors"
          :disabled="isProcessing"
        >
          Заблокировать
        </button>
      </div>
    </div>
  </div>
  
  <!-- Модальное окно подтверждения разблокировки пользователя -->
  <div v-if="showUnblockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
      <h3 class="text-xl font-bold mb-4">Подтверждение разблокировки</h3>
      <p class="mb-6">Вы уверены, что хотите разблокировать пользователя <span class="font-semibold">{{ userToUnblock?.name }}</span>?</p>
      <div class="flex justify-end space-x-3">
        <button 
          @click="cancelUnblock" 
          class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
          :disabled="isProcessing"
        >
          Отмена
        </button>
        <button 
          @click="unblockUser" 
          class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
          :disabled="isProcessing"
        >
          Разблокировать
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useNotification } from '~/stores/notification'

const notificationStore = useNotification()

// Примеры пользователей для демонстрации
const exampleUsers = [
  {
    id: 1,
    name: 'Иванов Иван',
    email: 'ivanov@example.com',
    is_admin: true,
    created_at: '2025-01-15T10:30:00.000Z',
    last_login_at: '2025-05-10T08:45:00.000Z',
    is_blocked: false
  },
  {
    id: 2,
    name: 'Петрова Елена',
    email: 'petrova@example.com',
    is_admin: false,
    created_at: '2025-02-20T14:45:00.000Z',
    last_login_at: '2025-05-12T16:30:00.000Z',
    is_blocked: false
  },
  {
    id: 3,
    name: 'Сидоров Алексей',
    email: 'sidorov@example.com',
    is_admin: false,
    created_at: '2025-03-10T09:15:00.000Z',
    last_login_at: '2025-04-25T11:20:00.000Z',
    is_blocked: true
  },
  {
    id: 4,
    name: 'Кузнецова Мария',
    email: 'kuznetsova@example.com',
    is_admin: false,
    created_at: '2025-04-05T16:20:00.000Z',
    last_login_at: '2025-05-14T09:10:00.000Z',
    is_blocked: false
  },
  {
    id: 5,
    name: 'Смирнов Дмитрий',
    email: 'smirnov@example.com',
    is_admin: true,
    created_at: '2025-05-01T11:10:00.000Z',
    last_login_at: '2025-05-13T14:25:00.000Z',
    is_blocked: false
  },
  {
    id: 6,
    name: 'Козлова Анна',
    email: 'kozlova@example.com',
    is_admin: false,
    created_at: '2025-03-15T13:40:00.000Z',
    last_login_at: '2025-05-08T10:15:00.000Z',
    is_blocked: false
  },
  {
    id: 7,
    name: 'Николаев Сергей',
    email: 'nikolaev@example.com',
    is_admin: false,
    created_at: '2025-02-10T08:30:00.000Z',
    last_login_at: null,
    is_blocked: false
  },
  {
    id: 8,
    name: 'Морозова Ольга',
    email: 'morozova@example.com',
    is_admin: false,
    created_at: '2025-04-20T15:50:00.000Z',
    last_login_at: '2025-05-11T17:40:00.000Z',
    is_blocked: false
  }
]

const users = ref([...exampleUsers])
const searchQuery = ref('')
const roleFilter = ref('')
const jobSearchStatusFilter = ref('')
const registrationDateFrom = ref('')
const registrationDateTo = ref('')
// Переменная selectedUsers удалена

// Пагинация
const currentPage = ref(1)
const pageSize = 10
const totalUsers = ref(exampleUsers.length)
const totalPages = computed(() => Math.ceil(totalUsers.value / pageSize))
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

// Переменные для модальных окон
const activeActionMenu = ref(null)
const showDeleteModal = ref(false)
const userToDelete = ref(null)
const userToToggleAdmin = ref(null)
const showBlockModal = ref(false)
const userToBlock = ref(null)
const showUnblockModal = ref(false)
const userToUnblock = ref(null)

// Фильтрация пользователей
const filteredUsers = computed(() => {
  let filtered = users.value
  
  // Фильтр по поиску
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(user => 
      user.name.toLowerCase().includes(query) || 
      user.email.toLowerCase().includes(query)
    )
  }
  
  // Фильтр по роли
  if (roleFilter.value) {
    filtered = filtered.filter(user => 
      (roleFilter.value === 'admin' && user.is_admin) || 
      (roleFilter.value === 'user' && !user.is_admin)
    )
  }
  
  // Обновляем общее количество пользователей для пагинации
  totalUsers.value = filtered.length
  
  // Применяем пагинацию
  const startIndex = (currentPage.value - 1) * pageSize
  const endIndex = startIndex + pageSize
  
  return filtered.slice(startIndex, endIndex)
})

// Загрузка списка пользователей
const loadUsers = async () => {
  try {
    // Для демонстрации используем примеры пользователей
    
    // Демо-данные
    const demoUsers = [
      {
        id: 1,
        name: 'Иван Иванов',
        email: 'ivan@example.com',
        role: 'admin',
        status: 'active',
        job_search_status: 'not_looking',
        created_at: '2025-04-15T10:30:00Z',
        updated_at: '2025-05-01T14:45:00Z'
      },
      {
        id: 2,
        name: 'Елена Петрова',
        email: 'elena@example.com',
        role: 'recruiter',
        status: 'active',
        job_search_status: 'passive',
        created_at: '2025-04-20T09:15:00Z',
        updated_at: '2025-05-02T11:30:00Z'
      },
      {
        id: 3,
        name: 'Алексей Сидоров',
        email: 'alexey@example.com',
        role: 'applicant',
        status: 'blocked',
        job_search_status: 'active',
        created_at: '2025-03-10T13:45:00Z',
        updated_at: '2025-04-15T16:20:00Z'
      },
      {
        id: 4,
        name: 'Мария Смирнова',
        email: 'maria@example.com',
        role: 'applicant',
        status: 'active',
        job_search_status: 'active',
        created_at: '2025-05-05T08:30:00Z',
        updated_at: '2025-05-05T08:30:00Z'
      },
      {
        id: 5,
        name: 'Дмитрий Козлов',
        email: 'dmitry@example.com',
        role: 'employer',
        status: 'active',
        job_search_status: 'passive',
        created_at: '2025-04-01T11:00:00Z',
        updated_at: '2025-04-25T14:15:00Z'
      }
    ]
    
    // Фильтрация по поисковому запросу и фильтрам
    let filteredUsers = demoUsers
    
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      filteredUsers = filteredUsers.filter(user => 
        user.name.toLowerCase().includes(query) || 
        user.email.toLowerCase().includes(query)
      )
    }
    
    if (roleFilter.value && roleFilter.value !== 'all') {
      filteredUsers = filteredUsers.filter(user => user.role === roleFilter.value)
    }
    
    if (jobSearchStatusFilter.value) {
      filteredUsers = filteredUsers.filter(user => user.job_search_status === jobSearchStatusFilter.value)
    }
    
    // Фильтрация по дате регистрации
    if (registrationDateFrom.value) {
      const fromDate = new Date(registrationDateFrom.value)
      filteredUsers = filteredUsers.filter(user => new Date(user.created_at) >= fromDate)
    }
    
    if (registrationDateTo.value) {
      const toDate = new Date(registrationDateTo.value)
      // Устанавливаем время на конец дня
      toDate.setHours(23, 59, 59, 999)
      filteredUsers = filteredUsers.filter(user => new Date(user.created_at) <= toDate)
    }
    
    // Имитируем ответ API с пагинацией
    const perPage = 10
    const total = filteredUsers.length
    const lastPage = Math.ceil(total / perPage)
    const from = (page - 1) * perPage + 1
    const to = Math.min(page * perPage, total)
    
    users.value = {
      data: filteredUsers,
      current_page: page,
      per_page: perPage,
      total: total,
      last_page: lastPage,
      from: from,
      to: to,
      prev_page_url: page > 1 ? `?page=${page-1}` : null,
      next_page_url: page < lastPage ? `?page=${page+1}` : null
    }
    
    totalUsers.value = total
  } catch (error) {
    console.error('Ошибка при загрузке пользователей:', error)
  }
}

// ... остальной код ...
// Функции для подтверждения изменения статуса администратора
const showAdminConfirmation = (user) => {
  userToToggleAdmin.value = user
  // showAdminModal.value = true
  // Закрываем меню действий
  activeActionMenu.value = null
}

const cancelAdminToggle = () => {
  // showAdminModal.value = false
  userToToggleAdmin.value = null
}

// Изменение статуса администратора
const toggleAdminStatus = async () => {
  if (!userToToggleAdmin.value || isProcessing.value) return

  const user = userToToggleAdmin.value
  isProcessing.value = true
  
  try {
    const response = await fetch(`http://localhost:8000/api/admin/users/${user.id}/toggle-admin`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      const updatedUser = await response.json()
      const index = users.value.findIndex(u => u.id === user.id)
      users.value[index] = updatedUser
      notificationStore.success(`Права пользователя ${user.name} успешно обновлены`)
      // showAdminModal.value = false
      userToToggleAdmin.value = null
    } else {
      notificationStore.error('Ошибка при обновлении прав пользователя')
    }
  } catch (error) {
    console.error('Ошибка при обновлении прав пользователя:', error)
    notificationStore.error('Ошибка при обновлении прав пользователя')
  } finally {
    isProcessing.value = false
  }
}

// Функции для подтверждения удаления пользователя
const showDeleteConfirmation = (user) => {
  userToDelete.value = user
  showDeleteModal.value = true
  // Закрываем меню действий
  activeActionMenu.value = null
}

const cancelDelete = () => {
  showDeleteModal.value = false
  userToDelete.value = null
}

// Удаление пользователя
const deleteUser = async () => {
  if (!userToDelete.value || isProcessing.value) return
  
  const user = userToDelete.value
  isProcessing.value = true
  
  try {
    const response = await fetch(`http://localhost:8000/api/admin/users/${user.id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      users.value = users.value.filter(u => u.id !== user.id)
      notificationStore.success(`Пользователь ${user.name} успешно удален`)
      showDeleteModal.value = false
      userToDelete.value = null
    } else {
      notificationStore.error('Ошибка при удалении пользователя')
    }
  } catch (error) {
    console.error('Ошибка при удалении пользователя:', error)
    notificationStore.error('Ошибка при удалении пользователя')
  } finally {
    isProcessing.value = false
  }
}

// Функции для подтверждения блокировки пользователя
const showBlockConfirmation = (user) => {
  userToBlock.value = user
  showBlockModal.value = true
  // Закрываем меню действий
  activeActionMenu.value = null
}

const cancelBlock = () => {
  showBlockModal.value = false
  userToBlock.value = null
}

// Функции для подтверждения разблокировки пользователя
const showUnblockConfirmation = (user) => {
  userToUnblock.value = user
  showUnblockModal.value = true
  // Закрываем меню действий
  activeActionMenu.value = null
}

const cancelUnblock = () => {
  showUnblockModal.value = false
  userToUnblock.value = null
}

// Методы блокировки/разблокировки пользователя
const blockUser = async () => {
  if (!userToBlock.value || isProcessing.value) return
  
  const user = userToBlock.value
  isProcessing.value = true
  
  try {
    const response = await fetch(`http://localhost:8000/api/admin/users/${user.id}/block`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.ok) {
      const data = await response.json()
      const index = users.value.findIndex(u => u.id === user.id)
      users.value[index] = data.user
      notificationStore.success(`Пользователь ${user.name} заблокирован`)
      showBlockModal.value = false
      userToBlock.value = null
    } else {
      notificationStore.error('Ошибка при блокировке пользователя')
    }
  } catch (error) {
    console.error('Ошибка при блокировке пользователя:', error)
    notificationStore.error('Ошибка при блокировке пользователя')
  } finally {
    isProcessing.value = false
  }
}

const unblockUser = async () => {
  if (!userToUnblock.value || isProcessing.value) return
  
  const user = userToUnblock.value
  isProcessing.value = true
  
  try {
    const response = await fetch(`http://localhost:8000/api/admin/users/${user.id}/unblock`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    if (response.ok) {
      const data = await response.json()
      const index = users.value.findIndex(u => u.id === user.id)
      users.value[index] = data.user
      notificationStore.success(`Пользователь ${user.name} разблокирован`)
      showUnblockModal.value = false
      userToUnblock.value = null
    } else {
      notificationStore.error('Ошибка при разблокировке пользователя')
    }
  } catch (error) {
    console.error('Ошибка при разблокировке пользователя:', error)
    notificationStore.error('Ошибка при разблокировке пользователя')
  } finally {
    isProcessing.value = false
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Функция для переключения меню действий
const toggleActionMenu = (userId) => {
  if (activeActionMenu.value === userId) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = userId
  }
}

// Функция для определения, является ли пользователь одним из последних в списке
const isLastRows = (userId) => {
  const index = filteredUsers.value.findIndex(u => u.id === userId)
  return index >= filteredUsers.value.length - 2
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

// Наблюдатель за изменениями в фильтрах и поиске
watch([searchQuery, roleFilter], () => {
  currentPage.value = 1
})

onMounted(() => {
  loadUsers()
})

definePageMeta({ middleware: 'admin' })
</script> 