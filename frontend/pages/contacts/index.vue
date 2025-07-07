<template>
  <div class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Контакты</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Контактная информация -->
      <div>
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
          <h2 class="text-2xl font-semibold mb-4">Наши контакты</h2>
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <div class="text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div>
                <h3 class="font-medium">Адрес</h3>
                <p class="text-gray-600">г. Москва, ул. Медицинская, д. 123</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div>
                <h3 class="font-medium">Телефон</h3>
                <p class="text-gray-600">+7 (999) 123-45-67</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <h3 class="font-medium">Email</h3>
                <p class="text-gray-600">info@medical-hr.ru</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Время работы -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="text-2xl font-semibold mb-4">Время работы</h2>
          <div class="space-y-2">
            <div class="flex justify-between">
              <span class="text-gray-600">Понедельник - Пятница</span>
              <span class="font-medium">9:00 - 18:00</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Суббота</span>
              <span class="font-medium">10:00 - 16:00</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Воскресенье</span>
              <span class="font-medium">Выходной</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Форма обратной связи -->
      <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-2xl font-semibold mb-4">Напишите нам</h2>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Ваше имя</label>
            <input 
              type="text" 
              id="name"
              v-model="form.name"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input 
              type="email" 
              id="email"
              v-model="form.email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Сообщение</label>
            <textarea 
              id="message"
              v-model="form.message"
              rows="4"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            ></textarea>
          </div>

          <button 
            type="submit"
            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors"
          >
            Отправить сообщение
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
const form = ref({
  name: '',
  email: '',
  message: ''
})

const handleSubmit = () => {
  // Здесь будет логика отправки формы
  console.log('Form submitted:', form.value)
  // Очистка формы после отправки
  form.value = {
    name: '',
    email: '',
    message: ''
  }
}
</script> 