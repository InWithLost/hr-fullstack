<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
        Вход в систему
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Войдите в свой аккаунт для доступа к личному кабинету
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-6" @submit.prevent="handleLogin">
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

          <!-- Запомнить меня -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember_me"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
              />
              <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                Запомнить меня
              </label>
            </div>
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
                Вход...
              </span>
              <span v-else>Войти</span>
            </button>
          </div>
        </form>

        <!-- Ссылка на регистрацию -->
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Нет аккаунта?
            <NuxtLink to="/register" class="font-medium text-green-600 hover:text-green-500">
              Зарегистрируйтесь
            </NuxtLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useNotification } from '~/stores/notification'

const router = useRouter()
const notification = useNotification()

const loading = ref(false)
const errors = ref({})

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const handleLogin = async () => {
  loading.value = true
  errors.value = {}

  try {
    // Для демо-режима просто сохраняем тестовый токен
    // В реальном приложении здесь будет запрос к API
    
    // Проверка данных для демо-режима
    if (form.email === 'demo@example.com' && form.password === 'password') {
      // Сохраняем тестовый токен
      localStorage.setItem('token', 'demo-token-12345')
      localStorage.setItem('user', JSON.stringify({
        id: 1,
        name: 'Демо Пользователь',
        email: 'demo@example.com',
        is_admin: false
      }))
      
      // Добавляем задержку для имитации запроса
      await new Promise(resolve => setTimeout(resolve, 500))
    } else {
      // Если данные неверны
      errors.value.email = 'Неверный email или пароль'
      notification.show('Неверный email или пароль', 'error')
      loading.value = false
      return
    }
    
    /* Закомментированный код для реального API
    // Получаем CSRF-cookie
    await fetch('http://localhost:8000/sanctum/csrf-cookie', {
      credentials: 'include'
    })

    const response = await fetch('http://localhost:8000/api/login', {
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
        notification.show('Пожалуйста, исправьте ошибки в форме', 'error')
        return
      }
      throw new Error(data.message || 'Ошибка входа')
    }

    // Сохраняем токен
    if (data.token) {
      localStorage.setItem('token', data.token)
    } else {
      throw new Error('Токен не получен от сервера')
    }
    */
    
    // Показываем уведомление об успехе
    notification.show('Вы успешно вошли в систему', 'success')
    
    // Проверяем наличие параметра redirect в URL
    const route = useRoute()
    const redirectPath = route.query.redirect
    
    if (redirectPath) {
      // Если есть параметр redirect, перенаправляем на эту страницу
      router.push(redirectPath)
    } else {
      // Иначе перенаправляем на главную страницу
      router.push('/')
    }
  } catch (error) {
    notification.show(error.message || 'Ошибка входа в систему', 'error')
  } finally {
    loading.value = false
  }
}
</script> 