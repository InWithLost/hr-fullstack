<template>
  <header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <!-- Левая часть: логотип + меню -->
      <div class="flex items-center gap-10">
        <!-- Логотип -->
        <NuxtLink to="/" class="flex items-center gap-2">
          <img
            src="/logo.png"
            alt="Medici Quaestio Logo"
            class="h-[30px] w-21 mr-9"
          />
        </NuxtLink>

        <!-- Навигация -->
        <nav class="hidden md:flex gap-6 text-gray-800 font-medium text-sm">
          <NuxtLink to="/jobs" class="hover:text-green-600 transition" active-class="text-green-600">Вакансии</NuxtLink>
          <NuxtLink to="/specialties" class="hover:text-green-600 transition" active-class="text-green-600">Специальности</NuxtLink>
          <NuxtLink to="/blog" class="hover:text-green-600 transition" active-class="text-green-600">Блог</NuxtLink>
          <NuxtLink to="/contacts" class="hover:text-green-600 transition" active-class="text-green-600">Контакты</NuxtLink>
        </nav>
      </div>

      <!-- Правая часть -->
      <div class="flex items-center gap-6">
        <!-- Контакты -->
        <div class="hidden md:flex items-center gap-6 text-sm text-gray-600">
          <a href="tel:+78001234567" class="hover:text-green-600 transition">+7 800 123-45-67</a>
          <a href="mailto:info@medici-quaestio.ru" class="hover:text-green-600 transition">info@medici-quaestio.ru</a>
        </div>

        <!-- Авторизация -->
        <div class="flex items-center gap-4">
          <div v-if="isAuthenticated" class="relative flex items-center">
            <!-- Индикатор профиля компании -->
            <div v-if="isCompanyProfile && company" class="mr-3 hidden md:block">
              <div class="text-xs text-gray-500">Профиль компании</div>
              <div class="text-sm font-medium text-gray-900">{{ company.name }}</div>
            </div>
            
            <img
              v-if="isCompanyProfile && company"
              :src="company.logo || 'https://ui-avatars.com/api/?name=C&background=0D8ABC&color=fff'"
              class="h-10 w-10 rounded-full object-cover cursor-pointer border"
              @click="toggleMenu"
              alt="Логотип компании"
            />
            <img
              v-else
              :src="user.avatar ? `/storage/${user.avatar}` : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name || 'U')"
              class="h-10 w-10 rounded-full object-cover cursor-pointer border"
              @click="toggleMenu"
              alt="Аватар"
            />
            <!-- Выпадающее меню -->
            <div v-if="showMenu" class="absolute right-0 top-full mt-2 w-64 bg-white border rounded-md shadow-lg z-50 overflow-hidden">
              
              <!-- Активный профиль -->
              <div class="border-b bg-gray-50 px-4 py-3">
                <div v-if="!isCompanyProfile" class="flex items-center">
                  <span class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </span>
                  <div>
                    <div class="text-sm font-medium text-gray-800">{{ formatUserName(user.name) }}</div>
                    <div class="text-xs text-gray-500">Активный профиль</div>
                  </div>
                </div>
                
                <div v-else-if="company" class="flex items-center">
                  <img 
                    :src="company.logo || 'https://ui-avatars.com/api/?name=C&background=0D8ABC&color=fff'"
                    class="w-8 h-8 rounded-full object-cover border mr-2"
                    :alt="company.name"
                  />
                  <div>
                    <div class="text-sm font-medium text-gray-800">{{ company.name }}</div>
                    <div class="text-xs text-gray-500">Активный профиль</div>
                  </div>
                </div>
              </div>
              
              <!-- Навигация -->
              <div class="border-b">
                <div class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">НАВИГАЦИЯ</div>
                
                <!-- Ссылки на разделы профиля пользователя (показываются только когда активен профиль пользователя) -->
                <template v-if="!isCompanyProfile">
                  <NuxtLink to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Профиль
                  </NuxtLink>
                  
                  <NuxtLink to="/profile/anketa" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Анкета
                  </NuxtLink>
                  
                  <NuxtLink to="/profile/education" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path d="M12 14l9-5-9-5-9 5 9 5z" />
                      <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                    Образование
                  </NuxtLink>
                  
                  <NuxtLink to="/profile/applications" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Мои отклики
                  </NuxtLink>
                </template>
                
                <!-- Ссылка на регистрацию компании -->
                <NuxtLink v-if="!hasCompany" to="/company/register" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Зарегистрировать компанию
                </NuxtLink>
                
                <!-- Ссылки для текущей компании (показываются только когда активен профиль компании) -->
                <template v-if="isCompanyProfile && company">
                  <NuxtLink to="/company/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Профиль компании
                  </NuxtLink>
                  <NuxtLink to="/company/profile/jobs" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Вакансии
                  </NuxtLink>
                  <NuxtLink to="/company/profile/balance" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Баланс
                  </NuxtLink>
                </template>
              </div>
              
              <!-- Переключить профиль -->
              <div class="border-b">
                <div class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">ПЕРЕКЛЮЧИТЬ ПРОФИЛЬ</div>
                
                <!-- Профиль пользователя -->
                <NuxtLink 
                  to="/profile"
                  @click="switchToUserProfile"
                  class="flex items-center text-sm w-full px-4 py-3 hover:bg-gray-50"
                  :class="{'bg-gray-50 text-green-600 font-medium': !isCompanyProfile, 'text-gray-700': isCompanyProfile}"
                >
                  <span class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </span>
                  <div class="flex-1 text-left">{{ formatUserName(user.name) }}</div>
                  <span v-if="!isCompanyProfile" class="ml-1 w-2 h-2 rounded-full bg-green-500"></span>
                </NuxtLink>
                
                <!-- Компании пользователя -->
                <div v-if="userCompanies.length > 0" class="max-h-48 overflow-y-auto">
                  <NuxtLink 
                    v-for="userCompany in userCompanies" 
                    :key="userCompany.id"
                    :to="'/company/profile'"
                    @click="switchToCompanyProfile(userCompany.id)"
                    class="flex items-center text-sm w-full px-4 py-3 hover:bg-gray-50"
                    :class="{'bg-gray-50 text-green-600 font-medium': isCompanyProfile && currentCompanyId === userCompany.id, 'text-gray-700': !isCompanyProfile || currentCompanyId !== userCompany.id}"
                  >
                    <img 
                      :src="userCompany.logo || 'https://ui-avatars.com/api/?name=C&background=0D8ABC&color=fff'"
                      class="w-8 h-8 rounded-full object-cover border mr-2"
                      :alt="userCompany.name"
                    />
                    <div class="flex-1 text-left truncate">{{ userCompany.name }}</div>
                    <span v-if="isCompanyProfile && currentCompanyId === userCompany.id" class="ml-1 w-2 h-2 rounded-full bg-green-500"></span>
                  </NuxtLink>
                </div>
                
                <!-- Ссылка на админ-панель для администраторов -->
                <div v-if="user.is_admin" class="border-t">
                  <NuxtLink to="/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Админ панель
                  </NuxtLink>
                </div>
              </div>
              
              <div class="border-t">
                <button @click="handleLogout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Выйти
                </button>
              </div>
            </div>
          </div>
          <div v-else>
            <NuxtLink
              to="/login"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition"
            >
              Войти
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import { useRuntimeConfig } from '#app'

const router = useRouter()
const notification = useNotification()
const config = useRuntimeConfig()
const isAuthenticated = ref(false)
const showMenu = ref(false)
const user = ref({})

// Переключение между профилями пользователя и компании
const isCompanyProfile = ref(false)
const hasCompany = ref(false)
const company = ref(null)
const userCompanies = ref([])
const currentCompanyId = ref(null)

// Проверка авторизации и загрузка пользователя
const checkAuth = async () => {
  const token = localStorage.getItem('token')
  isAuthenticated.value = !!token
  
  // Проверяем текущий профиль из localStorage
  const currentProfile = localStorage.getItem('currentProfile')
  isCompanyProfile.value = currentProfile === 'company'
  
  // Если есть токен, получаем данные пользователя
  if (token) {
    try {
      // В реальном приложении здесь будет API запрос
      // const response = await fetch(`${config.public.apiBase}/api/user`, {
      //   headers: {
      //     'Authorization': `Bearer ${token}`
      //   }
      // })
      
      // if (response.ok) {
      //   const data = await response.json()
      //   user.value = data.user || {}
      // } else {
      //   throw new Error('Не удалось загрузить данные пользователя')
      // }
      
      // Для демо-режима используем тестовые данные
      user.value = {
        name: 'Иван Петров',
        email: 'demo@example.com',
        is_admin: true, // Устанавливаем флаг администратора
        avatar: null
      }
      
      // Загружаем список компаний пользователя
      // В реальном приложении здесь будет запрос к API
      // Для демо-режима используем тестовые данные
      userCompanies.value = [
        {
          id: 1,
          name: 'ООО "МедТехника"',
          logo: 'https://ui-avatars.com/api/?name=MT&background=0D8ABC&color=fff'
        },
        {
          id: 2,
          name: 'ООО "Клиника Здоровье"',
          logo: 'https://ui-avatars.com/api/?name=KZ&background=4CAF50&color=fff'
        },
        {
          id: 3,
          name: 'ИП Сидоров А.А.',
          logo: 'https://ui-avatars.com/api/?name=SA&background=FF5722&color=fff'
        }
      ]
      
      hasCompany.value = userCompanies.value.length > 0
      
      // Если в localStorage есть ID текущей компании, используем его
      const savedCompanyId = localStorage.getItem('currentCompanyId')
      currentCompanyId.value = savedCompanyId ? parseInt(savedCompanyId) : (userCompanies.value.length > 0 ? userCompanies.value[0].id : null)
      
      // Если выбран профиль компании, загружаем данные компании
      if (isCompanyProfile.value) {
        loadCompanyData()
      }
    } catch (error) {
      console.error('Ошибка при получении данных пользователя:', error)
    }
  }
}

// Загрузка данных выбранной компании
const loadCompanyData = async () => {
  try {
    // В реальном приложении здесь будет API запрос для получения данных конкретной компании
    // const token = localStorage.getItem('token')
    // const response = await fetch(`${config.public.apiBase}/api/companies/${currentCompanyId.value}`, {
    //   headers: {
    //     'Authorization': `Bearer ${token}`
    //   }
    // })
    // 
    // if (response.ok) {
    //   const data = await response.json()
    //   company.value = data.company
    // } else {
    //   throw new Error('Не удалось загрузить данные компании')
    // }
    
    // Для демо-режима используем тестовые данные
    if (currentCompanyId.value) {
      // Находим выбранную компанию в списке
      const selectedCompany = userCompanies.value.find(c => c.id === currentCompanyId.value)
      if (selectedCompany) {
        company.value = selectedCompany
      } else if (userCompanies.value.length > 0) {
        // Если компания не найдена, используем первую из списка
        company.value = userCompanies.value[0]
        currentCompanyId.value = company.value.id
        localStorage.setItem('currentCompanyId', company.value.id)
      } else {
        throw new Error('У пользователя нет компаний')
      }
    } else if (userCompanies.value.length > 0) {
      // Если ID не указан, используем первую компанию
      company.value = userCompanies.value[0]
      currentCompanyId.value = company.value.id
      localStorage.setItem('currentCompanyId', company.value.id)
    } else {
      throw new Error('У пользователя нет компаний')
    }
  } catch (error) {
    console.error('Ошибка при загрузке данных компании:', error)
    notification.show('Ошибка при загрузке данных компании', 'error')
    
    // В случае ошибки переключаемся на профиль пользователя
    switchToUserProfile()
  }
}

// Переключение на профиль пользователя
const switchToUserProfile = () => {
  isCompanyProfile.value = false
  localStorage.setItem('currentProfile', 'user')
  showMenu.value = false
  
  // Если мы находимся на странице компании, перенаправляем на профиль пользователя
  const currentPath = router.currentRoute.value.path
  if (currentPath.startsWith('/company')) {
    router.push('/profile')
  }
}

// Переключение на профиль компании
const switchToCompanyProfile = async (companyId) => {
  if (!hasCompany.value) {
    notification.show('У вас нет зарегистрированной компании', 'error')
    return
  }
  
  // Если передан ID компании, используем его
  if (companyId) {
    currentCompanyId.value = companyId
    localStorage.setItem('currentCompanyId', companyId)
  }
  
  isCompanyProfile.value = true
  localStorage.setItem('currentProfile', 'company')
  showMenu.value = false
  
  // Загружаем данные компании
  await loadCompanyData()
  
  // Если мы находимся на странице профиля пользователя, перенаправляем на профиль компании
  const currentPath = router.currentRoute.value.path
  if (currentPath.startsWith('/profile')) {
    router.push('/company/profile')
  }
}

// Выход из системы
const handleLogout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('currentProfile')
  isAuthenticated.value = false
  isCompanyProfile.value = false
  hasCompany.value = false
  user.value = {}
  company.value = null
  showMenu.value = false
  notification.show('Вы успешно вышли из системы', 'success')
  router.push('/login')
}

// Функция форматирования имени пользователя в формате "Фамилия И.О."
const formatUserName = (fullName) => {
  if (!fullName) return ''
  
  const nameParts = fullName.split(' ')
  if (nameParts.length < 2) return fullName
  
  const lastName = nameParts[0]
  const firstNameInitial = nameParts[1] ? nameParts[1].charAt(0) + '.' : ''
  const middleNameInitial = nameParts[2] ? nameParts[2].charAt(0) + '.' : ''
  
  return `${lastName} ${firstNameInitial}${middleNameInitial}`
}

// Функция для открытия/закрытия меню по клику
const toggleMenu = () => {
  showMenu.value = !showMenu.value
}

// Проверяем авторизацию при загрузке компонента
onMounted(() => {
  checkAuth()
  // (по желанию) Закрытие меню при клике вне меню
  if (process.client) {
    window.addEventListener('click', (e) => {
      const menu = document.querySelector('.relative .absolute')
      if (showMenu.value && menu && !menu.contains(e.target) && !e.target.classList.contains('rounded-full')) {
        showMenu.value = false
      }
    })
  }
})
</script>
