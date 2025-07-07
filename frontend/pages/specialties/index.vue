<template>
  <div class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Медицинские специальности</h1>
    
    <!-- Фильтры -->
    <div class="mb-8">
      <div class="flex gap-4">
        <button 
          v-for="category in categories" 
          :key="category.id"
          @click="activeCategory = category.id"
          :class="[
            'px-4 py-2 rounded-lg transition-colors',
            activeCategory === category.id 
              ? 'bg-blue-600 text-white' 
              : 'bg-gray-100 hover:bg-gray-200'
          ]"
        >
          {{ category.name }}
        </button>
      </div>
    </div>

    <!-- Сетка специальностей -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <SpecialtyCard
        v-for="specialty in filteredSpecialties"
        :key="specialty.id"
        :specialty="specialty"
      />
    </div>
  </div>
</template>

<script setup>
const categories = [
  { id: 'all', name: 'Все' },
  { id: 'higher', name: 'Высшее образование' },
  { id: 'secondary', name: 'Среднее образование' },
  { id: 'other', name: 'Смежные специальности' }
]

const activeCategory = ref('all')

// Временные данные для примера
const specialties = ref([
  {
    id: 1,
    title: 'Врач-терапевт',
    description: 'Врач общей практики, занимающийся диагностикой и лечением внутренних болезней.',
    image: '/images/specialties/therapist.jpg',
    category: 'Высшее образование',
    salary: 80000,
    slug: 'therapist'
  },
  // Здесь будут добавляться другие специальности
])

const filteredSpecialties = computed(() => {
  if (activeCategory.value === 'all') return specialties.value
  return specialties.value.filter(s => s.category === categories.find(c => c.id === activeCategory.value)?.name)
})
</script> 