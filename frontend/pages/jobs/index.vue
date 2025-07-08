<template>
  <div class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Вакансии</h1>
    
    <!-- Фильтры -->
    <div class="mb-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Поиск -->
        <div class="relative">
          <input 
            type="text" 
            v-model="searchQuery"
            placeholder="Поиск вакансий..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        
        <!-- Фильтр по городу -->
        <select 
          v-model="selectedCity"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Все города</option>
          <option v-for="city in cities" :key="city" :value="city">
            {{ city }}
          </option>
        </select>
        
        <!-- Фильтр по типу занятости -->
        <select 
          v-model="selectedEmploymentType"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Все типы занятости</option>
          <option v-for="type in employmentTypes" :key="type" :value="type">
            {{ type }}
          </option>
        </select>
      </div>
    </div>

    <!-- Список вакансий -->
    <div class="grid grid-cols-1 gap-6">
      <JobCard
        v-for="job in filteredJobs"
        :key="job.id"
        :job="job"
      />
    </div>
  </div>
</template>

<script setup>
const searchQuery = ref('')
const selectedCity = ref('')
const selectedEmploymentType = ref('')

const cities = ['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург', 'Казань']
const employmentTypes = ['Полная занятость', 'Частичная занятость', 'Удаленная работа']

const config = useRuntimeConfig()

const { data: fetchedJobs } = await useFetch(`${config.public.apiBase}/api/v1/jobs`, {
  transform: (d) => d.data ?? d,
})

const jobs = ref((fetchedJobs.value || []).map(j => ({
  ...j,
  salary: j.salary_from && j.salary_to ? `${j.salary_from}–${j.salary_to}` : j.salary_from ?? '',
  location: {
    city: j.city || '-',
    district: j.district || ''
  }
})))

const filteredJobs = computed(() => {
  return jobs.value.filter(job => {
    const matchesSearch = job.title.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCity = !selectedCity.value || job.location.city === selectedCity.value
    const matchesEmploymentType = !selectedEmploymentType.value || job.employmentType === selectedEmploymentType.value
    
    return matchesSearch && matchesCity && matchesEmploymentType
  })
})
</script> 