<!--
  Компонент заголовка профиля компании.
  Отображает навигацию по разделам профиля компании: Сведения, Вакансии, Отклики, Баланс.
  Также содержит ссылку на главную страницу и выпадающее меню с действиями профиля.
  Используется на всех страницах профиля компании для обеспечения единого интерфейса навигации.
-->
<template>
  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Логотип -->
          <div class="flex-shrink-0 flex items-center">
            <NuxtLink to="/" class="text-xl font-bold text-gray-800">
              <!-- Логотип компании -->
            </NuxtLink>
          </div>

          <!-- Навигация -->
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <NuxtLink
              to="/company/profile"
              :class="[
                $route.path === '/company/profile' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Сведения
            </NuxtLink>
            <NuxtLink
              to="/company/profile/jobs"
              :class="[
                $route.path === '/company/profile/jobs' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Вакансии
            </NuxtLink>
            <NuxtLink
              to="/company/profile/applications"
              :class="[
                $route.path === '/company/profile/applications' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Отклики
            </NuxtLink>
            <NuxtLink
              to="/company/profile/balance"
              :class="[
                $route.path === '/company/profile/balance' ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
              ]"
            >
              Баланс
            </NuxtLink>
          </div>
        </div>

        <!-- Правая часть - можно добавить дополнительные элементы при необходимости -->
      </div>
    </div>
  </nav>
</template>

<script setup>
const router = useRouter()
</script>
