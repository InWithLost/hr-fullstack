<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
        Регистрация
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Уже есть аккаунт?
        <NuxtLink to="/login" class="font-medium text-green-600 hover:text-green-500">
          Войти
        </NuxtLink>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-6" @submit.prevent="handleRegister">
          <!-- Имя -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
              Имя
            </label>
            <div class="mt-1">
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                placeholder="Иван Иванов"
              />
            </div>
            <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                placeholder="example@email.com"
              />
            </div>
            <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email }}</p>
          </div>

          <!-- Телефон -->
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">
              Телефон
            </label>
            <div class="mt-1">
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                placeholder="+7 (999) 123-45-67"
              />
            </div>
            <p v-if="errors.phone" class="mt-2 text-sm text-red-600">{{ errors.phone }}</p>
          </div>

          <!-- Специализация -->
          <div>
            <label for="specialization" class="block text-sm font-medium text-gray-700">
              Специализация
            </label>
            <div class="mt-1">
              <input
                id="specialization"
                v-model="form.specialization"
                type="text"
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                placeholder="Например: Терапевт"
              />
            </div>
            <p v-if="errors.specialization" class="mt-2 text-sm text-red-600">{{ errors.specialization }}</p>
          </div>

          <!-- Пароль -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Пароль
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                placeholder="••••••••"
              />
            </div>
            <p v-if="errors.password" class="mt-2 text-sm text-red-600">{{ errors.password }}</p>
          </div>

          <!-- Подтверждение пароля -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Подтверждение пароля
            </label>
            <div class="mt-1">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500"
                placeholder="••••••••"
              />
            </div>
            <p v-if="errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ errors.password_confirmation }}</p>
          </div>

          <div>
            <button
              type="submit"
              :disabled="loading"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition"
            >
              <span v-if="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Регистрация...
              </span>
              <span v-else>Зарегистрироваться</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'

const router = useRouter()
const notification = useNotification()

const loading = ref(false)
const errors = ref({})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  specialization: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = async () => {
  loading.value = true
  errors.value = {}

  try {
    // Получаем CSRF-токен
    await fetch('http://localhost:8000/sanctum/csrf-cookie', {
      credentials: 'include'
    })

    const response = await fetch('http://localhost:8000/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-XSRF-TOKEN': document.cookie.match(/XSRF-TOKEN=([\w-]+)/)?.[1] || ''
      },
      credentials: 'include',
      body: JSON.stringify(form)
    })

    const data = await response.json()

    if (!response.ok) {
      if (data.errors) {
        errors.value = data.errors
      } else if (data.message) {
        notification.error(data.message)
      } else {
        notification.error('Ошибка при регистрации')
      }
      return
    }

    notification.success('Регистрация успешно завершена')

    // --- АВТОМАТИЧЕСКИЙ ВХОД ---
    const loginResponse = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-XSRF-TOKEN': document.cookie.match(/XSRF-TOKEN=([\w-]+)/)?.[1] || ''
      },
      credentials: 'include',
      body: JSON.stringify({
        email: form.email,
        password: form.password
      })
    })

    const loginData = await loginResponse.json()

    if (loginResponse.ok && loginData.token) {
      localStorage.setItem('token', loginData.token)
      notification.success('Вы успешно вошли в систему')
      router.push('/profile')
    } else {
      notification.error(loginData.message || 'Ошибка автоматического входа')
      router.push('/login')
    }
    // --- КОНЕЦ БЛОКА АВТОМАТИЧЕСКОГО ВХОДА ---
  } catch (error) {
    console.error('Registration error:', error)
    notification.error('Произошла ошибка при регистрации')
  } finally {
    loading.value = false
  }
}
</script> 