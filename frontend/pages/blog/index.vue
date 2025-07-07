<template>
  <div class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-8">Блог</h1>
    
    <!-- Фильтры -->
    <div class="mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Поиск -->
        <div class="relative">
          <input 
            type="text" 
            v-model="searchQuery"
            placeholder="Поиск статей..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        
        <!-- Фильтр по категории -->
        <select 
          v-model="selectedCategory"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Все категории</option>
          <option v-for="category in categories" :key="category" :value="category">
            {{ category }}
          </option>
        </select>
      </div>
    </div>

    <!-- Сетка статей -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <BlogCard
        v-for="post in filteredPosts"
        :key="post.id"
        :post="post"
      />
    </div>
  </div>
</template>

<script setup>
const searchQuery = ref('')
const selectedCategory = ref('')

const categories = [
  'Карьера в медицине',
  'Образование',
  'Советы соискателям',
  'Новости медицины'
]

// Временные данные для примера
const posts = ref([
  {
    id: 1,
    title: 'Как стать успешным врачом-терапевтом',
    excerpt: 'В этой статье мы расскажем о том, какие навыки и знания необходимы для успешной карьеры врача-терапевта, и как их развивать.',
    image: '/images/blog/therapist-career.jpg',
    category: 'Карьера в медицине',
    date: '15 марта 2024',
    readTime: 5,
    slug: 'how-to-become-successful-therapist'
  },
  {
    id: 2,
    title: 'Топ-10 медицинских специальностей 2024 года',
    excerpt: 'Обзор самых востребованных медицинских специальностей в 2024 году, их требования и перспективы развития.',
    image: '/images/blog/top-medical-specialties.jpg',
    category: 'Образование',
    date: '10 марта 2024',
    readTime: 7,
    slug: 'top-10-medical-specialties-2024'
  },
  {
    id: 3,
    title: 'Как подготовиться к собеседованию в медицинскую клинику',
    excerpt: 'Практические советы по подготовке к собеседованию в медицинскую клинику, включая типичные вопросы и ответы.',
    image: '/images/blog/medical-interview.jpg',
    category: 'Советы соискателям',
    date: '5 марта 2024',
    readTime: 4,
    slug: 'how-to-prepare-for-medical-interview'
  }
])

const filteredPosts = computed(() => {
  return posts.value.filter(post => {
    const matchesSearch = post.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         post.excerpt.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !selectedCategory.value || post.category === selectedCategory.value
    
    return matchesSearch && matchesCategory
  })
})
</script> 