<template>
  <footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-6">
      <!-- Основной контент футера -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        <!-- Разделы сайта -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Разделы сайта</h3>
          <ul class="space-y-2">
            <li>
              <NuxtLink to="/" class="text-gray-300 hover:text-white transition-colors">
                Главная
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/specialties" class="text-gray-300 hover:text-white transition-colors">
                Специальности
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/jobs" class="text-gray-300 hover:text-white transition-colors">
                Вакансии
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/blog" class="text-gray-300 hover:text-white transition-colors">
                Блог
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/contacts" class="text-gray-300 hover:text-white transition-colors">
                Контакты
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Последние новости -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Последние новости</h3>
          <ul class="space-y-3">
            <li v-for="(news, index) in latestNews" :key="index">
              <NuxtLink :to="`/blog/${news.slug}`" class="text-gray-300 hover:text-white transition-colors">
                <p class="text-sm">{{ news.title }}</p>
                <p class="text-xs text-gray-400">{{ news.date }}</p>
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Юридическая информация -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Юридическая информация</h3>
          <ul class="space-y-2">
            <li>
              <NuxtLink to="/legal/privacy" class="text-gray-300 hover:text-white transition-colors">
                Политика конфиденциальности
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/legal/personal-data" class="text-gray-300 hover:text-white transition-colors">
                Согласие на обработку персональных данных
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/legal/terms" class="text-gray-300 hover:text-white transition-colors">
                Условия использования
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/legal/offer" class="text-gray-300 hover:text-white transition-colors">
                Договор оферты
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Контактная информация -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Контакты</h3>
          <ul class="space-y-2">
            <li class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <span>+7 (495) 123-45-67</span>
            </li>
            <li class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>info@medicalstaff.ru</span>
            </li>
            <li class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>г. Москва, ул. Медицинская, 123</span>
            </li>
          </ul>
          
          <!-- Социальные сети -->
          <div class="mt-6">
            <h4 class="text-sm font-semibold mb-3">Мы в социальных сетях</h4>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/>
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2a10 10 0 1 0 10 10A10.009 10.009 0 0 0 12 2zm6 5.92v.218a12.1 12.1 0 0 1-12.1 12.1A12.1 12.1 0 0 1 0 19.22a8.766 8.766 0 0 0 1.018.059 8.574 8.574 0 0 0 5.303-1.827 4.276 4.276 0 0 1-3.987-2.964 4.273 4.273 0 0 0 1.928-.073 4.272 4.272 0 0 1-3.427-4.187v-.054a4.239 4.239 0 0 0 1.934.535 4.27 4.27 0 0 1-1.9-3.553 4.24 4.24 0 0 1 .58-2.15 12.129 12.129 0 0 0 8.802 4.464 4.269 4.269 0 0 1 7.269-3.891 8.57 8.57 0 0 0 2.71-1.035 4.288 4.288 0 0 1-1.876 2.359 8.552 8.552 0 0 0 2.45-.671 8.676 8.676 0 0 1-2.127 2.208z"/>
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z"/>
                </svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5l6-3.5-6-3.5v7z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Нижняя часть футера -->
      <div class="pt-8 mt-8 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center">
        <p class="text-gray-400 text-sm">
          © {{ new Date().getFullYear() }} МедСтафф. Все права защищены.
        </p>
        <div class="mt-4 md:mt-0">
          <img
            src="/logo.png"
            alt="МедСтафф"
            class="h-[30px] w-21"
          />
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
// Пример данных для последних новостей
const latestNews = ref([
  {
    title: 'Новые стандарты в медицинском образовании',
    date: '10 июня 2023',
    slug: 'new-standards-medical-education'
  },
  {
    title: 'Как подготовиться к собеседованию на медицинскую должность',
    date: '5 июня 2023',
    slug: 'prepare-medical-job-interview'
  },
  {
    title: 'Тренды в медицинской сфере 2023 года',
    date: '1 июня 2023',
    slug: 'medical-trends-2023'
  }
])
</script> 