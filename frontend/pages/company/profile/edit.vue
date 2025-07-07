<template>
  <div class="min-h-screen bg-gray-100">
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
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Редактирование профиля компании</h1>
            <button
              @click="cancelEdit"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Отмена
            </button>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <form @submit.prevent="saveCompany" class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Логотип компании -->
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Логотип компании</h3>
              <div class="mt-4 flex items-center">
                <div class="flex-shrink-0">
                  <img
                    :src="previewImage || (company.logo ? company.logo : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(company.name || 'C'))"
                    class="h-24 w-24 rounded-full object-cover border"
                    alt="Логотип компании"
                  />
                </div>
                <div class="ml-5">
                  <div class="mb-2">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Загрузить новый логотип</label>
                    <input
                      type="file"
                      id="logo"
                      @change="handleLogoChange"
                      accept="image/*"
                      class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <p class="text-xs text-gray-500">Рекомендуемый размер: 200x200 пикселей. Максимальный размер файла: 2MB.</p>
                </div>
              </div>
            </div>
            
            <!-- Основная информация -->
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Основная информация</h3>
              
              <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                  <label for="company_name" class="block text-sm font-medium text-gray-700">Название компании</label>
                  <input
                    type="text"
                    id="company_name"
                    v-model="company.name"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required
                  />
                </div>

                <div class="sm:col-span-3">
                  <label for="company_inn" class="block text-sm font-medium text-gray-700">ИНН</label>
                  <input
                    type="text"
                    id="company_inn"
                    v-model="company.inn"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required
                  />
                </div>

                <div class="sm:col-span-3">
                  <label for="company_industry" class="block text-sm font-medium text-gray-700">Отрасль</label>
                  <select
                    id="company_industry"
                    v-model="company.industry"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                    <option value="hospital">Больница</option>
                    <option value="clinic">Клиника</option>
                    <option value="pharmacy">Аптека</option>
                    <option value="laboratory">Лаборатория</option>
                    <option value="research">Исследовательский центр</option>
                    <option value="education">Образовательное учреждение</option>
                    <option value="other">Другое</option>
                  </select>
                </div>

                <div class="sm:col-span-3">
                  <label for="company_contact_name" class="block text-sm font-medium text-gray-700">Контактное лицо</label>
                  <input
                    type="text"
                    id="company_contact_name"
                    v-model="company.contact_name"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="sm:col-span-3">
                  <label for="company_position" class="block text-sm font-medium text-gray-700">Должность</label>
                  <input
                    type="text"
                    id="company_position"
                    v-model="company.position"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="sm:col-span-3">
                  <label for="company_phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                  <input
                    type="tel"
                    id="company_phone"
                    v-model="company.phone"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="sm:col-span-3">
                  <label for="company_email" class="block text-sm font-medium text-gray-700">Email</label>
                  <input
                    type="email"
                    id="company_email"
                    v-model="company.email"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  />
                </div>

                <div class="sm:col-span-6">
                  <label for="company_description" class="block text-sm font-medium text-gray-700">Описание компании</label>
                  <textarea
                    id="company_description"
                    v-model="company.description"
                    rows="4"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                  ></textarea>
                </div>
              </div>
            </div>
            
            <!-- Кнопки действий -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
              <button
                type="button"
                @click="cancelEdit"
                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
              >
                Отмена
              </button>
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                Сохранить
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'

// Middleware для проверки авторизации
definePageMeta({
  middleware: ['auth-check']
})

const router = useRouter()
const notification = useNotification()

// Проверка режима администратора
const isAdminMode = ref(false)
const adminCompanyData = ref(null)

// Данные компании
const company = ref({
  id: null,
  name: '',
  inn: '',
  industry: '',
  contact_name: '',
  position: '',
  email: '',
  phone: '',
  description: '',
  logo: null
})

// Переменные для загрузки логотипа
const newLogoFile = ref(null)
const previewImage = ref(null)

// Функция возврата в админ-панель
const returnToAdmin = () => {
  // Удаляем информацию о режиме администратора
  localStorage.removeItem('admin_managing_company')
  
  // Возвращаемся на страницу компаний в админ-панели
  router.push('/admin/companies')
}

// Функция отмены редактирования
const cancelEdit = () => {
  router.push('/company/profile')
}

// Функция для обработки изменения логотипа
const handleLogoChange = (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  // Проверка размера файла (максимум 2MB)
  if (file.size > 2 * 1024 * 1024) {
    notification.show('Размер файла превышает 2MB', 'error')
    return
  }
  
  // Проверка типа файла
  if (!file.type.startsWith('image/')) {
    notification.show('Выбранный файл не является изображением', 'error')
    return
  }
  
  newLogoFile.value = file
  
  // Создаем предпросмотр изображения
  const reader = new FileReader()
  reader.onload = (e) => {
    previewImage.value = e.target.result
  }
  reader.readAsDataURL(file)
}

// Функция сохранения данных компании
const saveCompany = async () => {
  try {
    // В реальном приложении здесь был бы API-запрос для сохранения данных компании
    // const formData = new FormData()
    // Object.keys(company.value).forEach(key => {
    //   if (key !== 'logo') {
    //     formData.append(key, company.value[key])
    //   }
    // })
    // 
    // if (newLogoFile.value) {
    //   formData.append('logo', newLogoFile.value)
    // }
    // 
    // const token = localStorage.getItem('token')
    // const response = await fetch('http://localhost:8000/api/company/profile', {
    //   method: 'PUT',
    //   headers: {
    //     'Authorization': `Bearer ${token}`
    //   },
    //   body: formData
    // })
    // 
    // if (!response.ok) {
    //   throw new Error('Не удалось сохранить данные компании')
    // }
    // 
    // const data = await response.json()
    // company.value = data.company
    
    // Для демо-режима просто сохраняем в localStorage
    if (newLogoFile.value && previewImage.value) {
      company.value.logo = previewImage.value
    }
    
    localStorage.setItem('demo_company', JSON.stringify(company.value))
    
    notification.show('Данные компании успешно сохранены', 'success')
    router.push('/company/profile')
  } catch (error) {
    notification.show('Ошибка при сохранении данных компании: ' + error.message, 'error')
    console.error('Ошибка при сохранении данных компании:', error)
  }
}

// Загрузка данных компании
const loadCompanyData = async () => {
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
          company.value.id = adminCompanyData.value.id
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
    
    // Для демо-режима, если нет данных, используем тестовые данные
    if (!company.value.id) {
      company.value = {
        id: 1,
        name: 'ООО "Медтехника"',
        inn: '1234567890',
        industry: 'clinic',
        contact_name: 'Иванов Иван Иванович',
        position: 'Директор по персоналу',
        email: 'info@medtechnika.ru',
        phone: '+7 (495) 123-45-67',
        description: 'Компания специализируется на поставке медицинского оборудования и расходных материалов для клиник и больниц.',
        logo: 'https://ui-avatars.com/api/?name=MT&background=0D8ABC&color=fff'
      }
    }
  } catch (error) {
    notification.show('Ошибка при загрузке данных компании: ' + error.message, 'error')
    console.error('Ошибка при загрузке данных компании:', error)
  }
}

// Инициализация
onMounted(() => {
  loadCompanyData()
})
</script>
