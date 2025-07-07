<!--
  Компонент заголовка административной панели.
  Отображает навигацию по разделам админки: Дашборд, Вакансии, Компании, Пользователи, Блог, Специальности.
  Также содержит ссылку на главную страницу и выпадающее меню профиля администратора.
  Используется на всех страницах административной панели для обеспечения единого интерфейса навигации.
-->
<template>
  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Логотип -->
          <div class="flex-shrink-0 flex items-center">
            <NuxtLink to="/" class="text-xl font-bold text-gray-800">
              <!-- Надпись MedStaff Admin удалена -->
            </NuxtLink>
          </div>

          <!-- Навигация -->
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <NuxtLink
              to="/admin"
              :class="[
                $route.path === '/admin' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Дашборд
            </NuxtLink>
            <NuxtLink
              to="/admin/users"
              :class="[
                $route.path.startsWith('/admin/users') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Пользователи
            </NuxtLink>
            <NuxtLink
              to="/admin/jobs"
              :class="[
                $route.path.startsWith('/admin/jobs') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Вакансии
            </NuxtLink>
            <NuxtLink
              to="/admin/applications"
              :class="[
                $route.path.startsWith('/admin/applications') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Отклики
            </NuxtLink>
            <NuxtLink
              to="/admin/blog"
              :class="[
                $route.path.startsWith('/admin/blog') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Блог
            </NuxtLink>
            <NuxtLink
              to="/admin/companies"
              :class="[
                $route.path.startsWith('/admin/companies') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Компании
            </NuxtLink>
            <NuxtLink
              to="/admin/specialties"
              :class="[
                $route.path.startsWith('/admin/specialties') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Специальности
            </NuxtLink>
          </div>
        </div>

        <!-- Правая часть -->
        <!-- Кнопка выйти удалена -->
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()

const logout = async () => {
  // Очищаем токен
  localStorage.removeItem('token')
  // Перенаправляем на страницу входа
  await router.push('/login')
}
</script> 