<template>
  <section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold">Актуальные вакансии</h2>
        <NuxtLink 
          to="/jobs"
          class="text-blue-600 hover:text-blue-800 font-medium"
        >
          Все вакансии →
        </NuxtLink>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <JobCard
          v-for="job in featuredJobs"
          :key="job.id"
          :job="job"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
// Временные данные для примера
const featuredJobs = ref([
  {
    id: 1,
    title: 'Врач-терапевт',
    location: {
      city: 'Москва',
      district: 'Ховрино'
    },
    salary: '120 000',
    requirements: [
      'Высшее медицинское образование',
      'Опыт работы от 3 лет',
      'Наличие действующего сертификата'
    ],
    employmentType: 'Полная занятость',
    experience: 'от 3 лет',
    slug: 'therapist-moscow'
  },
  {
    id: 2,
    title: 'Медицинская сестра',
    location: {
      city: 'Санкт-Петербург',
      district: 'Петроградский'
    },
    salary: '80 000',
    requirements: [
      'Среднее медицинское образование',
      'Опыт работы от 1 года',
      'Наличие действующего сертификата'
    ],
    employmentType: 'Полная занятость',
    experience: 'от 1 года',
    slug: 'nurse-spb'
  },
  {
    id: 3,
    title: 'Врач-кардиолог',
    location: {
      city: 'Новосибирск',
      district: 'Центральный'
    },
    salary: '150 000',
    requirements: [
      'Высшее медицинское образование',
      'Опыт работы от 5 лет',
      'Наличие действующего сертификата',
      'Специализация по кардиологии'
    ],
    employmentType: 'Полная занятость',
    experience: 'от 5 лет',
    slug: 'cardiologist-nsk'
  }
])
</script> 