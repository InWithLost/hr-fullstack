<template>
  <div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Заголовок и основная информация -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold mb-4">{{ specialty.title }}</h1>
      <div class="flex items-center gap-4 text-gray-600">
        <span>{{ specialty.category }}</span>
        <span>•</span>
        <span>от {{ specialty.salary }} ₽</span>
      </div>
    </div>

    <!-- Изображение и описание -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
      <div class="relative h-96">
        <img 
          :src="specialty.image" 
          :alt="specialty.title"
          class="w-full h-full object-cover rounded-lg"
        />
      </div>
      <div>
        <h2 class="text-2xl font-semibold mb-4">Описание специальности</h2>
        <p class="text-gray-700 leading-relaxed">{{ specialty.description }}</p>
      </div>
    </div>

    <!-- Зарплаты по регионам -->
    <div class="mb-12">
      <h2 class="text-2xl font-semibold mb-6">Средняя заработная плата по регионам</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div 
          v-for="region in specialty.salaries" 
          :key="region.name"
          class="bg-white p-4 rounded-lg shadow-sm"
        >
          <h3 class="font-medium mb-2">{{ region.name }}</h3>
          <p class="text-blue-600 font-semibold">{{ region.salary }} ₽</p>
        </div>
      </div>
    </div>

    <!-- Доступные вакансии -->
    <div>
      <h2 class="text-2xl font-semibold mb-6">Доступные вакансии</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div 
          v-for="job in specialty.jobs" 
          :key="job.id"
          class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow"
        >
          <h3 class="font-semibold mb-2">{{ job.title }}</h3>
          <p class="text-gray-600 mb-2">{{ job.location }}</p>
          <p class="text-blue-600 font-medium">{{ job.salary }} ₽</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const specialty = ref({
  title: 'Врач-терапевт',
  category: 'Высшее образование',
  salary: 80000,
  image: '/images/specialties/therapist.jpg',
  description: 'Врач-терапевт - это специалист широкого профиля, который занимается диагностикой и лечением внутренних болезней. Он проводит первичный осмотр пациентов, назначает необходимые анализы и исследования, ставит диагнозы и разрабатывает планы лечения. Терапевт также может направлять пациентов к узким специалистам при необходимости.',
  salaries: [
    { name: 'Москва', salary: '120 000' },
    { name: 'Санкт-Петербург', salary: '100 000' },
    { name: 'Новосибирск', salary: '90 000' },
    { name: 'Екатеринбург', salary: '85 000' },
    { name: 'Казань', salary: '80 000' }
  ],
  jobs: [
    {
      id: 1,
      title: 'Врач-терапевт',
      location: 'Москва',
      salary: '120 000'
    },
    {
      id: 2,
      title: 'Врач-терапевт участковый',
      location: 'Санкт-Петербург',
      salary: '100 000'
    }
  ]
})
</script> 