<template>
  <div class="min-h-screen bg-gray-100">
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Регистрация компании</h1>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Форма только для авторизованных пользователей -->
          <div v-if="isAuthenticated" class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <form @submit.prevent="registerCompany">
                  <div class="space-y-6">
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700">Название компании</label>
                      <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.name ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                    </div>

                    <div>
                      <label for="inn" class="block text-sm font-medium text-gray-700">ИНН</label>
                      <input
                        type="text"
                        id="inn"
                        v-model="form.inn"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.inn ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.inn" class="mt-2 text-sm text-red-600">{{ errors.inn }}</p>
                    </div>

                    <div>
                      <label for="industry" class="block text-sm font-medium text-gray-700">Отрасль</label>
                      <select
                        id="industry"
                        v-model="form.industry"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.industry ? 'border-red-300' : ''
                        ]"
                      >
                        <option value="">Выберите отрасль</option>
                        <option value="hospital">Больница</option>
                        <option value="clinic">Клиника</option>
                        <option value="pharmacy">Аптека</option>
                        <option value="laboratory">Лаборатория</option>
                        <option value="research">Исследовательский центр</option>
                        <option value="education">Образовательное учреждение</option>
                        <option value="other">Другое</option>
                      </select>
                      <p v-if="errors.industry" class="mt-2 text-sm text-red-600">{{ errors.industry }}</p>
                    </div>

                    <div>
                      <label for="contact_name" class="block text-sm font-medium text-gray-700">ФИО контактного лица</label>
                      <input
                        type="text"
                        id="contact_name"
                        v-model="form.contact_name"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.contact_name ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.contact_name" class="mt-2 text-sm text-red-600">{{ errors.contact_name }}</p>
                    </div>

                    <div>
                      <label for="position" class="block text-sm font-medium text-gray-700">Ваша должность в компании</label>
                      <input
                        type="text"
                        id="position"
                        v-model="form.position"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.position ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.position" class="mt-2 text-sm text-red-600">{{ errors.position }}</p>
                    </div>

                    <div>
                      <label for="phone" class="block text-sm font-medium text-gray-700">Телефон компании</label>
                      <input
                        type="tel"
                        id="phone"
                        v-model="form.phone"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.phone ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.phone" class="mt-2 text-sm text-red-600">{{ errors.phone }}</p>
                    </div>

                    <div>
                      <label for="email" class="block text-sm font-medium text-gray-700">Email компании</label>
                      <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.email ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email }}</p>
                    </div>

                    <div>
                      <label for="description" class="block text-sm font-medium text-gray-700">Описание компании</label>
                      <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        :class="[
                          'mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.description ? 'border-red-300' : ''
                        ]"
                      ></textarea>
                      <p v-if="errors.description" class="mt-2 text-sm text-red-600">{{ errors.description }}</p>
                    </div>


                    <div>
                      <label class="block text-sm font-medium text-gray-700">Логотип компании</label>
                      <div class="mt-1 flex items-center">
                        <span v-if="!logoPreview" class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                          <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                          </svg>
                        </span>
                        <img v-else :src="logoPreview" class="h-12 w-12 rounded-full object-cover" />
                        <button
                          type="button"
                          @click="$refs.logoInput.click()"
                          class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                          Загрузить
                        </button>
                        <input
                          ref="logoInput"
                          type="file"
                          accept="image/*"
                          class="hidden"
                          @change="handleLogoUpload"
                        />
                      </div>
                      <p v-if="errors.logo" class="mt-2 text-sm text-red-600">{{ errors.logo }}</p>
                    </div>

                    <div class="pt-5">
                      <div class="flex justify-end">
                        <button
                          type="button"
                          @click="$router.push('/')"
                          class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                          Отмена
                        </button>
                        <button
                          type="submit"
                          :disabled="isSubmitting"
                          class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                          :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }"
                        >
                          {{ isSubmitting ? 'Регистрация...' : 'Зарегистрировать компанию' }}
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Сообщение для неавторизованных пользователей -->
          <div v-else class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Для регистрации компании необходимо авторизоваться
                </h3>
                <div class="mt-5">
                  <p class="text-sm text-gray-500">
                    Чтобы зарегистрировать компанию, вам необходимо сначала войти в систему или зарегистрироваться.
                  </p>
                </div>
                <div class="mt-5">
                  <NuxtLink
                    to="/login"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                  >
                    Войти
                  </NuxtLink>
                  <NuxtLink
                    to="/register"
                    class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                  >
                    Зарегистрироваться
                  </NuxtLink>
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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'

// Middleware для проверки авторизации
definePageMeta({
  middleware: ['auth-check']
})

const router = useRouter()
const notification = useNotification()
const isAuthenticated = ref(false)
const isSubmitting = ref(false)
const logoPreview = ref(null)

// Форма для регистрации компании
const form = ref({
  name: '',
  inn: '',
  industry: '',
  contact_name: '',
  position: '',
  phone: '',
  email: '',
  description: '',
  logo: null,
  status: 'moderation' // Статус по умолчанию - на модерации
})

// Ошибки валидации
const errors = ref({})

// Проверка авторизации
const checkAuth = () => {
  const token = localStorage.getItem('token')
  isAuthenticated.value = !!token
}

// Обработка загрузки логотипа
const handleLogoUpload = (event) => {
  const file = event.target.files[0]
  if (!file) return

  // Проверка типа файла
  if (!file.type.match('image.*')) {
    errors.value.logo = 'Файл должен быть изображением'
    return
  }

  // Проверка размера файла (максимум 2MB)
  if (file.size > 2 * 1024 * 1024) {
    errors.value.logo = 'Размер файла не должен превышать 2MB'
    return
  }

  // Сохраняем файл и создаем превью
  form.value.logo = file
  const reader = new FileReader()
  reader.onload = (e) => {
    logoPreview.value = e.target.result
  }
  reader.readAsDataURL(file)
  errors.value.logo = null
}

// Валидация формы
const validateForm = () => {
  errors.value = {}
  let isValid = true

  if (!form.value.name.trim()) {
    errors.value.name = 'Название компании обязательно для заполнения'
    isValid = false
  }

  if (!form.value.inn.trim()) {
    errors.value.inn = 'ИНН обязателен для заполнения'
    isValid = false
  } else if (!/^\d{10}$|^\d{12}$/.test(form.value.inn.trim())) {
    errors.value.inn = 'ИНН должен содержать 10 цифр для организаций или 12 цифр для ИП'
    isValid = false
  }

  if (!form.value.industry) {
    errors.value.industry = 'Отрасль обязательна для выбора'
    isValid = false
  }

  if (!form.value.contact_name.trim()) {
    errors.value.contact_name = 'ФИО контактного лица обязательно для заполнения'
    isValid = false
  }

  if (!form.value.position.trim()) {
    errors.value.position = 'Должность обязательна для заполнения'
    isValid = false
  }

  if (!form.value.phone.trim()) {
    errors.value.phone = 'Телефон обязателен для заполнения'
    isValid = false
  }

  if (!form.value.email.trim()) {
    errors.value.email = 'Email обязателен для заполнения'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email.trim())) {
    errors.value.email = 'Введите корректный email'
    isValid = false
  }

  return isValid
}

// Регистрация компании
const registerCompany = async () => {
  if (!validateForm()) return

  isSubmitting.value = true

  try {
    // В реальном приложении здесь будет API запрос
    // const formData = new FormData()
    // Object.keys(form.value).forEach(key => {
    //   formData.append(key, form.value[key])
    // })
    // 
    // // Добавляем статус "на модерации"
    // formData.append('status', 'moderation')
    // 
    // const response = await fetch('http://localhost:8000/api/companies', {
    //   method: 'POST',
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: formData
    // })
    // 
    // if (!response.ok) {
    //   const errorData = await response.json()
    //   throw new Error(errorData.message || 'Ошибка при регистрации компании')
    // }
    // 
    // const companyData = await response.json()
    // 
    // // Автоматически добавляем текущего пользователя как управляющего компанией
    // const managerResponse = await fetch(`http://localhost:8000/api/companies/${companyData.id}/managers`, {
    //   method: 'POST',
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`,
    //     'Content-Type': 'application/json'
    //   },
    //   body: JSON.stringify({
    //     role: 'Администратор компании'
    //   })
    // })
    // 
    // if (!managerResponse.ok) {
    //   console.error('Ошибка при добавлении управляющего')
    // }
    
    // Для демо-режима просто имитируем успешную регистрацию
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Сохраняем данные компании в localStorage для демо-режима
    const companyWithId = {
      ...form.value,
      id: Date.now(),
      created_at: new Date().toISOString()
    }
    localStorage.setItem('demo_company', JSON.stringify(companyWithId))
    
    // Добавляем текущего пользователя как управляющего компанией
    const currentUser = JSON.parse(localStorage.getItem('user') || '{}')
    const managers = [{
      id: Date.now(),
      name: currentUser.name || 'Текущий пользователь',
      email: currentUser.email || 'user@example.com',
      role: 'Администратор компании',
      added_at: new Date().toISOString()
    }]
    localStorage.setItem('demo_company_managers', JSON.stringify(managers))
    
    notification.show('Компания успешно зарегистрирована и отправлена на модерацию!', 'success')
    router.push('/company/profile')
  } catch (error) {
    notification.show(error.message || 'Ошибка при регистрации компании', 'error')
    console.error('Ошибка при регистрации компании:', error)
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  checkAuth()
})
</script>
