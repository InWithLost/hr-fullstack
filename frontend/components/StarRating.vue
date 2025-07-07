<!--
  Компонент рейтинга в виде звезд.
  Позволяет пользователям оценивать контент по шкале от 1 до 5 звезд.
  Отображает текущий рейтинг и позволяет пользователю выбрать свою оценку.
  Поддерживает интерактивное изменение рейтинга при наведении и клике.
  Может использоваться на страницах блога, вакансий и других местах, где требуется оценка.
-->
<template>
  <div class="star-rating">
    <h3 class="text-lg font-medium mb-2">{{ title }}</h3>
    <div class="flex items-center mb-2">
      <div class="flex">
        <button
          v-for="n in 5"
          :key="n"
          @click="setRating(n)"
          @mouseenter="hoverRating = n"
          @mouseleave="hoverRating = 0"
          class="focus:outline-none"
          type="button"
        >
          <svg
            :class="[
              'w-8 h-8 transition-colors',
              {
                'text-yellow-400': hoverRating >= n || (hoverRating === 0 && rating >= n),
                'text-gray-300': hoverRating < n && (hoverRating === 0 && rating < n)
              }
            ]"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
            />
          </svg>
        </button>
      </div>
      <span class="ml-2 text-gray-600">{{ ratingText }}</span>
    </div>
    <div v-if="showThankYou" class="text-green-600 text-sm mt-1">
      Спасибо за вашу оценку!
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: 'Оцените эту статью'
  },
  initialRating: {
    type: Number,
    default: 0
  },
  slug: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['rating-updated'])

const rating = ref(props.initialRating)
const hoverRating = ref(0)
const showThankYou = ref(false)

// Проверяем, голосовал ли уже пользователь
const checkUserRating = () => {
  if (process.client) {
    const savedRating = localStorage.getItem(`blog_rating_${props.slug}`)
    if (savedRating) {
      rating.value = parseInt(savedRating)
      return true
    }
  }
  return false
}

// Инициализация при монтировании
onMounted(() => {
  checkUserRating()
})

const ratingText = computed(() => {
  if (rating.value === 0) return 'Еще нет оценок'
  
  const texts = [
    'Очень плохо',
    'Плохо',
    'Нормально',
    'Хорошо',
    'Отлично'
  ]
  
  return texts[rating.value - 1]
})

const setRating = (value) => {
  // Проверяем, не голосовал ли пользователь уже
  if (process.client && !localStorage.getItem(`blog_rating_${props.slug}`)) {
    rating.value = value
    
    // Сохраняем рейтинг в localStorage
    localStorage.setItem(`blog_rating_${props.slug}`, value.toString())
    
    // Показываем сообщение с благодарностью
    showThankYou.value = true
    setTimeout(() => {
      showThankYou.value = false
    }, 3000)
    
    // Отправляем событие родительскому компоненту
    emit('rating-updated', value)
    
    // В реальном приложении здесь был бы API-запрос для сохранения рейтинга
    console.log(`Сохранен рейтинг ${value} для статьи ${props.slug}`)
  }
}
</script>

<style scoped>
.star-rating {
  @apply bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm;
}
</style>
