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
const config = useRuntimeConfig()

const { data: fetchedFeatured } = await useFetch(`${config.public.apiBase}/api/v1/jobs`, {
  transform: (d) => (d.data ?? d).slice(0,3)
})

const featuredJobs = ref((fetchedFeatured.value || []).map(j => ({
  ...j,
  salary: j.salary_from && j.salary_to ? `${j.salary_from}–${j.salary_to}` : j.salary_from ?? '',
  location: {
    city: j.city || '-',
    district: j.district || ''
  }
})))
</script> 