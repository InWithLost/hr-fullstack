<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="relative h-48">
      <img 
        :src="post.image" 
        :alt="post.title"
        class="w-full h-full object-cover"
      />
      <div class="absolute top-0 right-0 bg-blue-600 text-white px-3 py-1 rounded-bl-lg">
        {{ post.category }}
      </div>
    </div>
    <div class="p-6">
      <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <span>{{ post.date }}</span>
        <span>•</span>
        <span>{{ post.readTime }} мин</span>
      </div>
      <h3 class="text-xl font-semibold mb-2">{{ post.title }}</h3>
      <p class="text-gray-600 mb-4 line-clamp-3">{{ post.excerpt }}</p>
      <NuxtLink 
        :to="`/blog/${post.slug}`"
        class="text-blue-600 hover:text-blue-800 font-medium"
      >
        Читать далее →
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
defineProps({
  post: {
    type: Object,
    required: true,
    default: () => ({
      title: '',
      excerpt: '',
      image: '',
      category: '',
      date: '',
      readTime: 0,
      slug: ''
    })
  }
})
</script> 