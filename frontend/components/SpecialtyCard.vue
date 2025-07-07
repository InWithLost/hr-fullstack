<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="relative h-48">
      <img 
        :src="specialty.image" 
        :alt="specialty.title"
        class="w-full h-full object-cover"
      />
      <div class="absolute top-0 right-0 bg-blue-600 text-white px-3 py-1 rounded-bl-lg">
        {{ specialty.category }}
      </div>
    </div>
    <div class="p-4">
      <h3 class="text-xl font-semibold mb-2">{{ specialty.title }}</h3>
      <p class="text-gray-600 mb-4 line-clamp-3">{{ specialty.description }}</p>
      <div class="flex justify-between items-center">
        <span class="text-blue-600 font-medium">от {{ specialty.salary }} ₽</span>
        <NuxtLink 
          :to="`/specialties/${specialty.slug}`"
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
  specialty: {
    type: Object,
    required: true,
    default: () => ({
      title: '',
      description: '',
      image: '',
      category: '',
      salary: 0,
      slug: ''
    })
  }
})
</script> 