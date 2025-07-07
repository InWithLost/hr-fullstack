<template>
  <div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Заголовок и метаданные -->
    <div class="mb-8">
      <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <span>{{ post.date }}</span>
        <span>•</span>
        <span>{{ post.readTime }} мин</span>
        <span>•</span>
        <span>{{ post.category }}</span>
      </div>
      <h1 class="text-4xl font-bold mb-4">{{ post.title }}</h1>
    </div>
    
    <!-- Двухколоночный макет с блоком вверху -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Левая колонка с изображением и текстом статьи -->
      <div class="lg:col-span-2">
        <!-- Изображение -->
        <div class="relative h-96 mb-8">
          <img 
            :src="post.image" 
            :alt="post.title"
            class="w-full h-full object-cover rounded-lg"
          />
        </div>
        
        <!-- Содержание статьи -->
        <div class="prose prose-lg max-w-none">
          <div v-html="post.content"></div>
        </div>
        
        <!-- Компонент рейтинга -->
        <div class="mt-8">
          <StarRating 
            :slug="route.params.slug" 
            :initial-rating="post.rating || 0"
            @rating-updated="updateRating"
          />
        </div>
      </div>
      
      <!-- Правая колонка с прилипающими блоками -->
      <div class="lg:col-span-1">
        <div class="sticky top-0 pt-0 space-y-4">
          <!-- Блок для работодателей -->
          <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 shadow-sm">
            <h3 class="text-xl font-semibold mb-4 text-blue-800">Нужны сотрудники?</h3>
            <p class="text-gray-700 mb-6">Опубликуйте вакансию и найдите лучших специалистов в медицинской сфере.</p>
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-colors font-medium">
              Опубликовать вакансию
            </button>
          </div>
          
          <!-- Блок для специалистов -->
          <div class="bg-green-50 p-6 rounded-lg border border-green-100 shadow-sm">
            <h3 class="text-xl font-semibold mb-4 text-green-800">Вы специалист?</h3>
            <p class="text-gray-700 mb-6">Заполните анкету и получайте предложения о работе, без спама, коротко и по делу.</p>
            <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-colors font-medium">
              Заполнить
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Похожие статьи -->
    <div class="mt-12">
      <h2 class="text-2xl font-semibold mb-6">Похожие статьи</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <BlogCard
          v-for="relatedPost in relatedPosts"
          :key="relatedPost.id"
          :post="relatedPost"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import StarRating from '~/components/StarRating.vue'
import { useNotification } from '~/stores/notification'
const route = useRoute()
const notification = useNotification()

const post = ref({
  title: 'Как стать успешным врачом-терапевтом',
  date: '15 марта 2024',
  readTime: 5,
  category: 'Карьера в медицине',
  image: '/images/blog/therapist-career.jpg',
  content: `
    <p>В современном мире профессия врача-терапевта остается одной из самых востребованных и уважаемых. Однако, чтобы стать действительно успешным специалистом, недостаточно просто получить медицинское образование.</p>
    
    <h2>1. Непрерывное обучение</h2>
    <p>Медицина постоянно развивается, появляются новые методы диагностики и лечения. Успешный врач-терапевт должен постоянно следить за новинками в своей области, посещать конференции и семинары, читать специализированную литературу.</p>
    
    <h2>2. Развитие коммуникативных навыков</h2>
    <p>Умение общаться с пациентами - один из ключевых навыков терапевта. Важно научиться слушать, сопереживать и доступно объяснять сложные медицинские термины.</p>
    
    <h2>3. Организационные навыки</h2>
    <p>Терапевт должен уметь эффективно планировать свое время, вести документацию и координировать работу с другими специалистами.</p>
  `
})

// Временные данные для примера
const relatedPosts = ref([
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

// Функция обновления рейтинга
const updateRating = (value) => {
  // Обновляем рейтинг в объекте статьи
  post.value.rating = value
  
  // В реальном приложении здесь был бы API-запрос для сохранения рейтинга
  /* Пример API-запроса
  try {
    await fetch(`http://localhost:8000/api/blog/${route.params.slug}/rate`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ rating: value })
    })
  } catch (error) {
    console.error('Ошибка при сохранении рейтинга:', error)
    notification.error('Не удалось сохранить вашу оценку')
  }
  */
  
  notification.success('Ваша оценка сохранена')
}
</script> 