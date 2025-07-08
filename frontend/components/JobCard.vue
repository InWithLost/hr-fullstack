<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="p-6">
      <div class="flex justify-between items-start mb-4">
        <div>
          <h3 class="text-xl font-semibold mb-2">{{ job.title }}</h3>
          <p class="text-gray-600">{{ job.location?.city || job.city || '-' }}<span v-if="job.location?.district || job.district">, {{ job.location?.district || job.district }}</span></p>
        </div>
        <span class="text-blue-600 font-semibold">{{ formattedSalary }}</span>
      </div>
      
      <div class="mb-4">
        <h4 class="font-medium mb-2">Требования:</h4>
        <ul class="list-disc list-inside text-gray-600">
          <li v-for="(requirement, index) in job.requirements" :key="index">
            {{ requirement }}
          </li>
        </ul>
      </div>

      <div class="flex justify-between items-center">
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-500">{{ job.employmentType }}</span>
          <span class="text-sm text-gray-500">•</span>
          <span class="text-sm text-gray-500">Опыт работы {{ job.experience }}</span>
        </div>
        <NuxtLink 
          :to="`/jobs/${job.slug}`"
          class="text-blue-600 hover:text-blue-800 font-medium"
        >
          Подробнее →
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  job: {
    type: Object,
    required: true,
    default: () => ({
      title: '',
      location: {
        city: '',
        district: ''
      },
      salary: '',
      requirements: [],
      employmentType: '',
      experience: '',
      slug: ''
    })
  }
})

const props = defineProps(['job'])

const formattedSalary = computed(() => {
  if (props.job.salary) return `${props.job.salary} ₽`
  if (props.job.salary_from && props.job.salary_to) return `${props.job.salary_from}–${props.job.salary_to} ₽`
  if (props.job.salary_from) return `от ${props.job.salary_from} ₽`
  return ''
})
</script> 