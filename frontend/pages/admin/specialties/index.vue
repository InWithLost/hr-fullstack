<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление специальностями</h1>
            <NuxtLink
              to="/admin/specialties/create"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Добавить специальность
            </NuxtLink>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Поиск и фильтры -->
          <div class="mt-4 space-y-4">
            <div class="flex space-x-4">
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Поиск специальностей..."
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
              />
              <select
                v-model="categoryFilter"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-1/4 sm:text-sm border-gray-300 rounded-md"
              >
                <option value="">Все категории</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
          </div>

          <!-- Список специальностей -->
          <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Название
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Категория
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Количество вакансий
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
                  <tr v-if="loading">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      Загрузка...
                    </td>
                  </tr>
                  <tr v-else-if="filteredSpecialties.length === 0">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      Специальности не найдены
                    </td>
                  </tr>
                  <tr v-for="specialty in paginatedSpecialties" :key="specialty.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ specialty.name }}</div>
                      <div v-if="specialty.education_level" class="text-xs text-gray-500">
                        {{ getEducationLevelName(specialty.education_level) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ getCategoryName(specialty.category_id) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <div class="text-sm text-gray-900">{{ specialty.jobs_count }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="specialty.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      >
                        {{ specialty.is_active ? 'Активна' : 'Неактивна' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="relative action-menu-container">
                        <!-- Иконка троеточия -->
                        <button 
                          @click="toggleActionMenu(specialty.id)" 
                          class="p-1 rounded-full hover:bg-gray-200 transition-colors"
                          title="Действия"
                        >
                          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                          </svg>
                        </button>
                        
                        <!-- Выпадающее меню -->
                        <div 
                          v-if="activeActionMenu === specialty.id" 
                          class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border"
                          :class="{
                            'bottom-full mb-2': isLastRows(specialty.id)
                          }"
                          style="z-index: 100;"
                        >
                          <div class="py-1">
                            <NuxtLink
                              :to="`/admin/specialties/${specialty.id}`"
                              class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                              @click="activeActionMenu = null"
                            >
                              Редактировать
                            </NuxtLink>
                            <button
                              @click="toggleStatus(specialty); activeActionMenu = null"
                              class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                              {{ specialty.is_active ? 'Деактивировать' : 'Активировать' }}
                            </button>
                            <button
                              @click="viewJobs(specialty); activeActionMenu = null"
                              class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                              Просмотр вакансий
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
                      Показаны <span class="font-medium">{{ paginationStart }}</span> - <span class="font-medium">{{ paginationEnd }}</span> из <span class="font-medium">{{ filteredSpecialties.length }}</span> специальностей
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
                          'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': page === currentPage,
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
            </div>
          </div>
        </div>
      </main>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useNotification } from '~/stores/notification'
import AdminHeader from '~/components/AdminHeader.vue'

definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const notification = useNotification()

// Состояние загрузки
const loading = ref(true)

// Данные для таблицы
const specialties = ref([])
const categories = ref([])
const educationLevels = ref([])

// Фильтры
const searchQuery = ref('')
const categoryFilter = ref('')

// Пагинация
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Активное меню действий
const activeActionMenu = ref(null)

// Отфильтрованные специальности
const filteredSpecialties = computed(() => {
  return specialties.value.filter(specialty => {
    // Фильтр по поисковому запросу
    const matchesSearch = searchQuery.value === '' || 
      specialty.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    // Фильтр по категории
    const matchesCategory = categoryFilter.value === '' || 
      specialty.category_id.toString() === categoryFilter.value.toString()
    
    return matchesSearch && matchesCategory
  })
})

// Общее количество страниц
const totalPages = computed(() => {
  return Math.ceil(filteredSpecialties.value.length / itemsPerPage.value)
})

// Специальности для текущей страницы
const paginatedSpecialties = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredSpecialties.value.slice(start, end)
})

// Информация о пагинации
const paginationStart = computed(() => {
  if (filteredSpecialties.value.length === 0) return 0
  return (currentPage.value - 1) * itemsPerPage.value + 1
})

const paginationEnd = computed(() => {
  if (filteredSpecialties.value.length === 0) return 0
  const end = currentPage.value * itemsPerPage.value
  return end > filteredSpecialties.value.length ? filteredSpecialties.value.length : end
})

// Получение названия категории по ID
function getCategoryName(categoryId) {
  const category = categories.value.find(c => c.id === categoryId)
  return category ? category.name : 'Не указана'
}

// Загрузка данных
async function loadData() {
  loading.value = true
  
  try {
    // В реальном приложении здесь будет API-запрос
    // Имитация загрузки данных
    setTimeout(() => {
      // Моковые данные для категорий
      categories.value = [
        { id: 1, name: 'Медицина' },
        { id: 2, name: 'IT' },
        { id: 3, name: 'Образование' },
        { id: 4, name: 'Финансы' },
        { id: 5, name: 'Маркетинг' }
      ]
      
      // Моковые данные для уровней образования
      educationLevels.value = [
        { id: 1, name: 'Высшее образование' },
        { id: 2, name: 'Среднее специальное образование' },
        { id: 3, name: 'Среднее образование' },
        { id: 4, name: 'Неоконченное высшее образование' }
      ]
      
      // Моковые данные для специальностей
      specialties.value = [
        { id: 1, name: 'Хирург', category_id: 1, education_level: 1, description: 'Врач, специализирующийся на хирургических операциях', is_active: true, jobs_count: 5 },
        { id: 2, name: 'Frontend-разработчик', category_id: 2, education_level: 1, description: 'Специалист по разработке пользовательских интерфейсов', is_active: true, jobs_count: 12 },
        { id: 3, name: 'Backend-разработчик', category_id: 2, education_level: 1, description: 'Разработка серверной части приложений', is_active: true, jobs_count: 8 },
        { id: 4, name: 'Учитель математики', category_id: 3, education_level: 2, description: 'Преподавание математики в школе', is_active: false, jobs_count: 3 },
        { id: 5, name: 'Бухгалтер', category_id: 4, education_level: 1, description: 'Ведение бухгалтерского учета', is_active: true, jobs_count: 7 },
        { id: 6, name: 'SMM-специалист', category_id: 5, education_level: 4, description: 'Продвижение в социальных сетях', is_active: true, jobs_count: 4 },
        { id: 7, name: 'Терапевт', category_id: 1, education_level: 1, description: 'Врач общей практики', is_active: true, jobs_count: 9 },
        { id: 8, name: 'Дизайнер интерфейсов', category_id: 2, education_level: 2, description: 'Разработка пользовательских интерфейсов', is_active: true, jobs_count: 6 },
        { id: 9, name: 'Преподаватель английского', category_id: 3, education_level: 1, description: 'Обучение английскому языку', is_active: true, jobs_count: 4 },
        { id: 10, name: 'Финансовый аналитик', category_id: 4, education_level: 1, description: 'Анализ финансовых показателей', is_active: true, jobs_count: 5 },
        { id: 11, name: 'Копирайтер', category_id: 5, education_level: 3, description: 'Написание рекламных текстов', is_active: false, jobs_count: 2 },
        { id: 12, name: 'Педиатр', category_id: 1, education_level: 1, description: 'Врач, специализирующийся на лечении детей', is_active: true, jobs_count: 7 }
      ]
      
      loading.value = false
    }, 1000)
  } catch (error) {
    console.error('Ошибка при загрузке данных:', error)
    notification.show('Ошибка при загрузке данных', 'error')
    loading.value = false
  }
}

// Функция для получения названия уровня образования по ID
function getEducationLevelName(levelId) {
  const level = educationLevels.value.find(l => l.id === levelId)
  return level ? level.name : 'Не указан'
}

// Функция для изменения страницы
function changePage(page) {
  if (page < 1 || page > totalPages.value) return
  currentPage.value = page
}

// Функция для генерации номеров страниц в пагинации
function getPageNumbers() {
  const pages = []
  const maxVisiblePages = 5
  
  if (!totalPages.value) return [1]
  
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
}

// Изменение статуса специальности
function toggleStatus(specialty) {
  // В реальном приложении здесь будет API-запрос
  specialty.is_active = !specialty.is_active
  
  notification.show(
    `Специальность "${specialty.name}" ${specialty.is_active ? 'активирована' : 'деактивирована'}`,
    'success'
  )
}

// Просмотр вакансий по специальности
function viewJobs(specialty) {
  // В реальном приложении здесь будет переход на страницу с вакансиями по специальности
  alert(`Просмотр вакансий по специальности "${specialty.name}". В реальном приложении здесь будет переход на страницу с вакансиями по специальности.`)
}

// Функция для переключения меню действий
function toggleActionMenu(specialtyId) {
  if (activeActionMenu.value === specialtyId) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = specialtyId
  }
}

// Функция для определения, является ли специальность одной из последних в списке
function isLastRows(specialtyId) {
  const index = paginatedSpecialties.value.findIndex(s => s.id === specialtyId)
  return index >= paginatedSpecialties.value.length - 2
}

// Отслеживание изменений в поиске и фильтрах
watch([searchQuery, categoryFilter], () => {
  currentPage.value = 1 // Сброс на первую страницу при изменении фильтров
})

// Загрузка данных при монтировании компонента
onMounted(() => {
  loadData()
  
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
