<!--
  Страница профиля компании (Сведения).
  Отображает основную информацию о компании: название, описание, контакты, логотип и др.
  Позволяет перейти к редактированию профиля компании.
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
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Профиль компании</h1>
            <button
              @click="editCompany"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              Редактировать
            </button>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Навигация перемещена в компонент CompanyProfileHeader -->

          <!-- Информация о компании -->
          <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex items-center">
              <div class="flex-shrink-0">
                <img
                  :src="company.logo ? company.logo : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(company.name || 'C')"
                  class="h-16 w-16 rounded-full object-cover border"
                  alt="Логотип компании"
                />
              </div>
              <div class="ml-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ company.name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">ИНН: {{ company.inn }}</p>
              </div>
            </div>
            <div class="border-t border-gray-200">
              <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Отрасль</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ getIndustryName(company.industry) }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Контактное лицо</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ company.contact_name }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Должность</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ company.position }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Email</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ company.email }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Телефон</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ company.phone }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Описание</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ company.description }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-gray-500">Дата регистрации</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ formatDate(company.created_at) }}</dd>
                </div>
              </dl>
            </div>
          </div>

          <!-- Управляющие компанией -->
          <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Управляющие компанией</h3>
              <button
                @click="openAddManagerModal"
                class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                Добавить управляющего
              </button>
            </div>
            <div class="border-t border-gray-200">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Роль</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата добавления</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="manager in managers" :key="manager.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ manager.name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-500">{{ manager.email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-500">{{ manager.role }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-500">{{ formatDate(manager.added_at) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="removeManager(manager)" class="text-red-600 hover:text-red-900 ml-2">Удалить</button>
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
  
  <!-- Модальное окно добавления управляющего -->
  <div v-if="showAddManagerModal" class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <form @submit.prevent="addManager">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Добавление управляющего</h3>
            
            <div class="mb-4">
              <label for="manager_email" class="block text-sm font-medium text-gray-700">Электронная почта</label>
              <input
                type="email"
                id="manager_email"
                v-model="managerForm.email"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                required
                placeholder="Введите email пользователя"
              />
              <p class="mt-1 text-sm text-gray-500">Укажите email пользователя, который будет иметь доступ к управлению компанией</p>
            </div>

            <div class="mb-4">
              <label for="manager_role" class="block text-sm font-medium text-gray-700">Роль</label>
              <input
                type="text"
                id="manager_role"
                v-model="managerForm.role"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                required
                placeholder="Например: Менеджер по персоналу"
              />
            </div>
          </div>

          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="submit"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Добавить
            </button>
            <button
              type="button"
              @click="closeAddManagerModal"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Отмена
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'

// Переменные для модального окна добавления управляющего
const showAddManagerModal = ref(false)
const managerForm = ref({
  email: '',
  role: ''
})

// Middleware для проверки авторизации
definePageMeta({
  middleware: ['auth-check']
})

const router = useRouter()
const notification = useNotification()
const user = ref({})

// Проверка режима администратора
const isAdminMode = ref(false)
const adminCompanyData = ref(null)

// Данные компании
const company = ref({
  name: 'ООО "МедТехника"',
  inn: '7701234567',
  industry: 'clinic',
  contact_name: 'Иванов Иван Иванович',
  position: 'Директор по персоналу',
  email: 'info@medtechnika.ru',
  phone: '+7 (495) 123-45-67',
  description: 'Компания специализируется на поставке медицинского оборудования и расходных материалов для клиник и больниц.',
  created_at: '2025-03-15T10:30:00Z',
  logo: 'https://ui-avatars.com/api/?name=MT&background=0D8ABC&color=fff'
})

const stats = ref({
  totalJobs: 5,
  activeJobs: 2,
  pendingJobs: 3
})

// Список управляющих компанией
const managers = ref([
  {
    id: 1,
    name: 'Иванов Иван Иванович',
    email: 'ivanov@example.com',
    role: 'Администратор компании',
    added_at: '2025-03-15T10:30:00Z'
  },
  {
    id: 2,
    name: 'Петрова Елена Сергеевна',
    email: 'petrova@example.com',
    role: 'Менеджер по персоналу',
    added_at: '2025-04-10T14:20:00Z'
  }
])

// Функция для форматирования даты
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(date)
}

// Получение названия отрасли по коду
const getIndustryName = (code) => {
  const industries = {
    'hospital': 'Больница',
    'clinic': 'Клиника',
    'pharmacy': 'Аптека',
    'laboratory': 'Лаборатория',
    'research': 'Исследовательский центр',
    'education': 'Образовательное учреждение',
    'other': 'Другое'
  }
  return industries[code] || 'Не указано'
}

// Переход на страницу редактирования компании
const editCompany = () => {
  router.push('/company/profile/edit')
}

// Функция возврата в админ-панель
const returnToAdmin = () => {
  // Удаляем информацию о режиме администратора
  localStorage.removeItem('admin_managing_company')
  
  // Возвращаемся на страницу компаний в админ-панели
  router.push('/admin/companies')
}

// Функция открытия модального окна добавления управляющего
const openAddManagerModal = () => {
  // Очищаем форму
  managerForm.value = {
    email: '',
    role: ''
  }
  
  // Открываем модальное окно
  showAddManagerModal.value = true
}

// Функция закрытия модального окна
const closeAddManagerModal = () => {
  showAddManagerModal.value = false
}

// Функция добавления нового управляющего
const addManager = async () => {
  try {
    // В реальном приложении здесь был бы API-запрос для проверки существования пользователя
    // const token = localStorage.getItem('token')
    // const checkUserResponse = await fetch(`http://localhost:8000/api/users/check?email=${encodeURIComponent(managerForm.value.email)}`, {
    //   headers: {
    //     'Authorization': `Bearer ${token}`
    //   }
    // })
    // 
    // if (!checkUserResponse.ok) {
    //   const errorData = await checkUserResponse.json()
    //   throw new Error(errorData.message || 'Ошибка при проверке пользователя')
    // }
    // 
    // const userData = await checkUserResponse.json()
    // 
    // if (!userData.exists) {
    //   throw new Error('Пользователь с таким email не зарегистрирован в системе')
    // }
    // 
    // // Если пользователь существует, добавляем его как управляющего
    // const response = await fetch('http://localhost:8000/api/company/managers', {
    //   method: 'POST',
    //   headers: {
    //     'Authorization': `Bearer ${token}`,
    //     'Content-Type': 'application/json'
    //   },
    //   body: JSON.stringify(managerForm.value)
    // })
    // 
    // if (!response.ok) {
    //   throw new Error('Не удалось добавить управляющего')
    // }
    // 
    // const data = await response.json()
    
    // Для демо-режима имитируем проверку существования пользователя
    // Список допустимых email для демо
    const allowedEmails = [
      'ivanov@example.com',
      'petrova@example.com',
      'sidorov@example.com',
      'admin@example.com',
      'hr@example.com',
      'manager@example.com'
    ]
    
    // Проверяем, есть ли уже управляющий с таким email
    const existingManager = managers.value.find(m => m.email.toLowerCase() === managerForm.value.email.toLowerCase())
    if (existingManager) {
      throw new Error('Управляющий с таким email уже добавлен')
    }
    
    // Проверяем, существует ли пользователь с таким email
    if (!allowedEmails.includes(managerForm.value.email.toLowerCase())) {
      throw new Error('Пользователь с таким email не зарегистрирован в системе')
    }
    
    // Если пользователь существует, добавляем его как управляющего
    const newManager = {
      id: Date.now(),
      name: managerForm.value.email.split('@')[0], // Для демо используем часть email как имя
      email: managerForm.value.email,
      role: managerForm.value.role,
      added_at: new Date().toISOString()
    }
    
    managers.value.unshift(newManager)
    notification.show('Управляющий успешно добавлен', 'success')
    closeAddManagerModal()
  } catch (error) {
    notification.show('Ошибка при добавлении управляющего: ' + error.message, 'error')
    console.error('Ошибка при добавлении управляющего:', error)
  }
}

// Функция удаления управляющего
const removeManager = async (manager) => {
  if (!confirm(`Вы действительно хотите удалить управляющего ${manager.name}?`)) {
    return
  }
  
  try {
    // В реальном приложении здесь был бы API-запрос
    // const token = localStorage.getItem('token')
    // const response = await fetch(`http://localhost:8000/api/company/managers/${manager.id}`, {
    //   method: 'DELETE',
    //   headers: {
    //     'Authorization': `Bearer ${token}`
    //   }
    // })
    // 
    // if (!response.ok) {
    //   throw new Error('Не удалось удалить управляющего')
    // }
    
    // Для демо-режима просто удаляем из списка
    managers.value = managers.value.filter(m => m.id !== manager.id)
    notification.show('Управляющий успешно удален', 'success')
  } catch (error) {
    notification.show('Ошибка при удалении управляющего: ' + error.message, 'error')
    console.error('Ошибка при удалении управляющего:', error)
  }
}

// Загрузка данных пользователя и компании
const loadData = async () => {
  try {
    // Проверяем, есть ли информация о режиме администратора
    const adminCompanyDataStr = localStorage.getItem('admin_managing_company')
    if (adminCompanyDataStr) {
      try {
        // Парсим данные о компании, которой управляет администратор
        adminCompanyData.value = JSON.parse(adminCompanyDataStr)
        isAdminMode.value = adminCompanyData.value.admin_mode === true
        
        // Если мы в режиме администратора, загружаем данные компании по ID
        if (isAdminMode.value) {
          console.log(`Загрузка данных компании в режиме администратора, ID: ${adminCompanyData.value.id}`)
          
          // В реальном приложении здесь был бы API-запрос для получения данных компании по ID
          // const token = localStorage.getItem('token')
          // const response = await fetch(`http://localhost:8000/api/admin/companies/${adminCompanyData.value.id}`, {
          //   headers: {
          //     'Authorization': `Bearer ${token}`
          //   }
          // })
          // const data = await response.json()
          // company.value = data.company
          
          // Для демо-режима просто используем имя из данных администратора
          company.value.name = adminCompanyData.value.name
        }
      } catch (error) {
        console.error('Ошибка при парсинге данных компании администратора:', error)
      }
    }
    
    // Проверяем, есть ли данные о зарегистрированной компании
    const demoCompanyStr = localStorage.getItem('demo_company')
    if (demoCompanyStr && !isAdminMode.value) {
      try {
        // Загружаем данные компании из localStorage
        const demoCompany = JSON.parse(demoCompanyStr)
        company.value = demoCompany
        
        // Загружаем данные об управляющих
        const demoManagersStr = localStorage.getItem('demo_company_managers')
        if (demoManagersStr) {
          managers.value = JSON.parse(demoManagersStr)
        }
        
        // Загружаем данные пользователя
        const userStr = localStorage.getItem('user')
        if (userStr) {
          user.value = JSON.parse(userStr)
        } else {
          // Если данных пользователя нет, используем демо-данные
          user.value = {
            name: 'Иванов Иван Иванович',
            email: 'ivanov@example.com'
          }
        }
        
        return // Если загрузили данные из localStorage, выходим
      } catch (error) {
        console.error('Ошибка при загрузке данных компании из localStorage:', error)
      }
    }
    
    // Если мы не в режиме администратора и нет данных в localStorage
    // В реальном приложении здесь будет API запрос
    // const token = localStorage.getItem('token')
    // const response = await fetch('http://localhost:8000/api/company/profile', {
    //   headers: {
    //     'Authorization': `Bearer ${token}`
    //   }
    // })
    // 
    // if (!response.ok) {
    //   throw new Error('Не удалось загрузить данные компании')
    // }
    // 
    // const data = await response.json()
    // company.value = data.company
    // user.value = data.user
    // stats.value = data.stats

    // Для демо-режима используем тестовые данные
    user.value = {
      name: 'Иванов Иван Иванович',
      email: 'ivanov@example.com'
    }
    
    // В реальном приложении здесь был бы API-запрос для получения списка управляющих
    // const managersResponse = await fetch(`http://localhost:8000/api/company/managers`, {
    //   headers: {
    //     'Authorization': `Bearer ${token}`
    //   }
    // })
    // const managersData = await managersResponse.json()
    // managers.value = managersData.managers
    
    // Для демо-режима используем тестовые данные, которые уже заданы
  } catch (error) {
    notification.show('Ошибка при загрузке данных: ' + error.message, 'error')
    console.error('Ошибка при загрузке данных компании:', error)
  }
}

onMounted(() => {
  loadData()
})
</script>
