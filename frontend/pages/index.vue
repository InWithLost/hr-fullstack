<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-blue-50 py-20">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
          <div class="flex flex-col h-full">
            <div class="mb-8">
              <h1 class="text-4xl md:text-5xl font-bold mb-6">
                Подбор медицинского персонала
              </h1>
              <p class="text-xl text-gray-600">
                Помогаем медицинским организациям находить специалистов — быстро, качественно и по всей России.
              </p>
            </div>
            <div class="mt-auto">
              <NuxtLink 
                to="/jobs"
                class="bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 transition-colors text-lg font-semibold inline-block"
              >
                Заявка на подбор специалиста
              </NuxtLink>
            </div>
          </div>
          <div class="relative h-96">
            <img 
              src="/logo.png" 
              alt="Медицинские специалисты"
              class="w-full h-full object-contain rounded-lg shadow-lg"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="bg-blue-50 py-20">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Feature 1 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold text-green-700 mb-2">Проверенные специалисты</h3>
            <p class="text-gray-600">Работаем только с квалифицированными и опытными кадрами.</p>
          </div>
          <!-- Feature 2 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold text-green-700 mb-2">Вся Россия</h3>
            <p class="text-gray-600">Помогаем с подбором персонала в любом регионе страны.</p>
          </div>
          <!-- Feature 3 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold text-green-700 mb-2">Быстрая подача заявки</h3>
            <p class="text-gray-600">Простая форма и моментальная обратная связь с менеджером.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Jobs Section -->
    <FeaturedJobs />

    <!-- How It Works Section -->
    <section class="py-20 bg-gray-900 text-white">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Как мы работаем</h2>
        <div class="max-w-4xl mx-auto space-y-16">
          <!-- Step 1 -->
          <div>
            <div class="flex items-center gap-4 mb-4">
              <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full"></div>
              </div>
              <h3 class="text-2xl font-semibold">Шаг 1. Подробная заявка на сайте</h3>
            </div>
            <p class="text-gray-300 text-lg mb-6 ml-12">
              Вы оставляете подробную заявку с описанием вакансии, пожеланий к кандидату и особенностей работы.
            </p>
            <div class="ml-12 space-y-3">
              <div class="flex items-start gap-3">
                <span class="text-xl">📌</span>
                <p class="text-gray-300">Чем точнее вы укажете требования, тем быстрее мы подберем подходящих специалистов.</p>
              </div>
              <div class="flex items-start gap-3">
                <span class="text-xl">💰</span>
                <p class="text-gray-300">Стоимость подбора будет рассчитана и показана до отправки формы.</p>
              </div>
            </div>
          </div>

          <!-- Step 2 -->
          <div>
            <div class="flex items-center gap-4 mb-4">
              <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full"></div>
              </div>
              <h3 class="text-2xl font-semibold">Шаг 2. Связь с вами</h3>
            </div>
            <p class="text-gray-300 text-lg mb-6 ml-12">
              После получения заявки мы связываемся с вами по указанным контактам.
            </p>
            <div class="ml-12 space-y-3">
              <div class="flex items-start gap-3">
                <span class="text-xl">🤝</span>
                <p class="text-gray-300">Знакомимся и уточняем, кто со стороны заказчика будет принимать участие в финальном собеседовании и принимать решения.</p>
              </div>
            </div>
          </div>

          <!-- Step 3 -->
          <div>
            <div class="flex items-center gap-4 mb-4">
              <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full"></div>
              </div>
              <h3 class="text-2xl font-semibold">Шаг 3. Подписание договора</h3>
            </div>
            <p class="text-gray-300 text-lg mb-6 ml-12">
              Мы оформляем простой и понятный договор.
            </p>
            <div class="ml-12 space-y-3">
              <div class="flex items-start gap-3">
                <span class="text-xl">📄</span>
                <p class="text-gray-300">Без лишней бюрократии: всё онлайн, всё прозрачно, юридически корректно.</p>
              </div>
            </div>
          </div>

          <!-- Step 4 -->
          <div>
            <div class="flex items-center gap-4 mb-4">
              <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full"></div>
              </div>
              <h3 class="text-2xl font-semibold">Шаг 4. Подбор и направление кандидатов</h3>
            </div>
            <p class="text-gray-300 text-lg mb-6 ml-12">
              Мы начинаем активный поиск и направляем кандидатов в согласованные сроки.
            </p>
            <div class="ml-12 space-y-3">
              <div class="flex items-start gap-3">
                <span class="text-xl">⏰</span>
                <p class="text-gray-300">Только те, кто действительно соответствует вашему запросу, приходят на собеседование.</p>
              </div>
            </div>
          </div>

          <!-- Step 5 -->
          <div>
            <div class="flex items-center gap-4 mb-4">
              <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full"></div>
              </div>
              <h3 class="text-2xl font-semibold">Шаг 5. Оплата по факту</h3>
            </div>
            <p class="text-gray-300 text-lg mb-6 ml-12">
              Вы оплачиваете услуги только за тех кандидатов, которые действительно начали работу.
            </p>
            <div class="ml-12 space-y-3">
              <div class="flex items-start gap-3">
                <span class="text-xl">✅</span>
                <p class="text-gray-300">Удобно, справедливо и без лишних рисков.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Find Specialist Section -->
    <section class="bg-gray-50 py-20">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-12">Найдём нужного специалиста в короткие сроки</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Feature 1 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-blue-600 mb-4">
              <img src="/patterns/education.png" alt="Образование" class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Партнерство с образовательными центрами</h3>
            <p class="text-gray-600">Мы сотрудничаем с ведущими учебными заведениями и колледжами медицинского профиля по всей стране.</p>
          </div>

          <!-- Feature 2 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-blue-600 mb-4">
              <img src="/patterns/database.png" alt="База данных" class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">База более 350 000 медработников</h3>
            <p class="text-gray-600">Доступ к крупнейшей базе дипломированных врачей, медсестер и фельдшеров. Это даёт нам скорость и выбор.</p>
          </div>

          <!-- Feature 3 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-blue-600 mb-4">
              <img src="/patterns/hunter.png" alt="Хантер" class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Хантер найдет редкого специалиста</h3>
            <p class="text-gray-600">Если подходящего кандидата нет — мы направим опытного хантера, который свяжется с нужным специалистом напрямую.</p>
          </div>

          <!-- Feature 4 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-blue-600 mb-4">
              <img src="/patterns/price.png" alt="Цена" class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Ниже рыночной стоимости</h3>
            <p class="text-gray-600">Мы предлагаем выгодные условия и прозрачное ценообразование, без скрытых платежей.</p>
          </div>

          <!-- Feature 5 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-blue-600 mb-4">
              <img src="/patterns/contract.png" alt="Договор" class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Простой договор</h3>
            <p class="text-gray-600">Прозрачные условия сотрудничества и юридическая чистота — без лишней бюрократии.</p>
          </div>

          <!-- Feature 6 -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-blue-600 mb-4">
              <img src="/patterns/guarantee.png" alt="Гарантия" class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Гарантия 60 дней</h3>
            <p class="text-gray-600">Если специалист не подошел — бесплатно предоставим замену в течение 2 месяцев.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Recruitment Form Section -->
    <section class="py-20">
      <div class="container mx-auto px-4">
        <RecruitmentForm />
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-gray-50 py-20">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-12 text-center">Часто задаваемые вопросы</h2>
        <div class="max-w-3xl mx-auto space-y-4">
          <div 
            v-for="(item, index) in faqItems" 
            :key="index"
            class="bg-white border border-gray-200 rounded-lg overflow-hidden"
          >
            <button
              class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors"
              @click="openFaqIndex = openFaqIndex === index ? null : index"
            >
              <span class="font-medium">{{ item.question }}</span>
              <svg 
                class="w-5 h-5 transform transition-transform"
                :class="{ 'rotate-180': openFaqIndex === index }"
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 20 20" 
                fill="currentColor"
              >
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div 
              v-show="openFaqIndex === index"
              class="px-6 py-4 bg-gray-50"
            >
              <p class="text-gray-600">{{ item.answer }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest Blog Posts -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-3xl font-bold">Последние статьи</h2>
          <NuxtLink 
            to="/blog"
            class="text-blue-600 hover:text-blue-800 font-medium"
          >
            Все статьи →
          </NuxtLink>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <BlogCard
            v-for="post in latestPosts"
            :key="post.id"
            :post="post"
          />
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import FeaturedJobs from '~/components/FeaturedJobs.vue'
import BlogCard from '~/components/BlogCard.vue'
import RecruitmentForm from '~/components/RecruitmentForm.vue'

// Временные данные для примера
const latestPosts = ref([
  {
    id: 1,
    title: 'Как стать успешным врачом-терапевтом',
    excerpt: 'В этой статье мы расскажем о том, какие навыки и знания необходимы для успешной карьеры врача-терапевта, и как их развивать.',
    image: '/logo.png',
    category: 'Карьера в медицине',
    date: '15 марта 2024',
    readTime: 5,
    slug: 'how-to-become-successful-therapist'
  },
  {
    id: 2,
    title: 'Топ-10 медицинских специальностей 2024 года',
    excerpt: 'Обзор самых востребованных медицинских специальностей в 2024 году, их требования и перспективы развития.',
    image: '/logo.png',
    category: 'Образование',
    date: '10 марта 2024',
    readTime: 7,
    slug: 'top-10-medical-specialties-2024'
  },
  {
    id: 3,
    title: 'Как подготовиться к собеседованию в медицинскую клинику',
    excerpt: 'Практические советы по подготовке к собеседованию в медицинскую клинику, включая типичные вопросы и ответы.',
    image: '/logo.png',
    category: 'Советы соискателям',
    date: '5 марта 2024',
    readTime: 4,
    slug: 'how-to-prepare-for-medical-interview'
  }
])

const openFaqIndex = ref(null)

const faqItems = [
  {
    question: 'Как происходит процесс подбора специалиста?',
    answer: 'После отправки заявки наш специалист свяжется с вами для уточнения деталей. Затем мы начинаем поиск кандидатов, проводим предварительное собеседование и представляем вам наиболее подходящих специалистов. Вы выбираете кандидата, и мы помогаем организовать его выход на работу.'
  },
  {
    question: 'Сколько времени занимает подбор специалиста?',
    answer: 'Сроки подбора зависят от специфики вакансии и требований к кандидату. В среднем, подбор занимает от 2 до 4 недель. При срочном поиске (опция "Немедленно") мы стараемся найти специалиста в течение 1-2 недель.'
  },
  {
    question: 'Какие гарантии вы предоставляете?',
    answer: 'Мы предоставляем гарантийный период 3 месяца. Если в течение этого времени специалист не подошел или уволился, мы бесплатно проведем повторный поиск или вернем часть стоимости услуги.'
  },
  {
    question: 'Как рассчитывается стоимость подбора?',
    answer: 'Базовая стоимость подбора составляет 15,000 ₽. Для врачей применяется коэффициент 1.5. За каждый дополнительный язык добавляется 5,000 ₽. При срочном поиске (опция "Немедленно") стоимость увеличивается на 20%.'
  },
  {
    question: 'Что делать, если у кандидата нет действующего сертификата?',
    answer: 'В этом случае мы можем помочь с организацией обучения и получения сертификата. Стоимость обучения обсуждается отдельно. Вы можете указать свою готовность оплатить обучение в форме заявки.'
  }
]
</script>

<style scoped>
.container {
  max-width: 1280px;
}
</style>
  