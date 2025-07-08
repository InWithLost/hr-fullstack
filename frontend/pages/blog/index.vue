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

const config = useRuntimeConfig()
const { data: fetchedPosts } = await useFetch(`${config.public.apiBase}/api/v1/blog`, {
  transform: (data) => data.data ?? data
})

const posts = ref((fetchedPosts.value || []).map(p => ({
  ...p,
  date: new Date(p.published_at || p.created_at).toLocaleDateString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric' })
})))

const filteredPosts = computed(() => {
  return posts.value.filter(post => {
    const matchesSearch = post.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         post.excerpt.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !selectedCategory.value || post.category === selectedCategory.value
    
    return matchesSearch && matchesCategory
  })
})
</script> 