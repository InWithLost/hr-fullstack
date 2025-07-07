<template>
  <div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Личный кабинет</h1>

    <!-- Навигационное меню -->
    <ProfileNavigation currentPage="anketa" />

    <form @submit.prevent="saveAnketa" class="space-y-6">

      <!-- Опыт работы -->
      <div>
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Опыт работы</h3>
          <button 
            type="button" 
            @click="addWorkExperience" 
            class="text-sm text-primary-600 hover:text-primary-800"
          >
            + Добавить место работы
          </button>
        </div>
        <div 
          v-for="(exp, index) in form.workExperience" 
          :key="index" 
          class="mb-4 border border-gray-200 rounded-lg p-4"
        >
          <div class="flex justify-between items-start mb-3">
            <h4 class="font-medium">Место работы #{{ index + 1 }}</h4>
            <button 
              v-if="form.workExperience.length > 1" 
              type="button" 
              @click="removeWorkExperience(index)" 
              class="text-red-600 hover:text-red-800"
            >
              Удалить
            </button>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Организация *</label>
              <input 
                type="text" 
                v-model="exp.company" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                placeholder="Название организации" 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Должность *</label>
              <input 
                type="text" 
                v-model="exp.position" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                placeholder="Должность" 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Дата начала *</label>
              <input 
                type="month" 
                v-model="exp.startDate" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Дата окончания</label>
              <input 
                type="month" 
                v-model="exp.endDate" 
                :max="getCurrentMonth()" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                placeholder="По настоящее время, если не указано" 
              />
            </div>
          </div>
          <div class="mt-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Обязанности</label>
            <textarea 
              v-model="exp.responsibilities" 
              rows="2" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
              placeholder="Опишите ваши основные обязанности"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Кнопки действий -->
      <div class="flex justify-end space-x-4 pt-6">
        <button 
          type="button" 
          class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50" 
          @click="$router.push('/profile')"
        >
          Отменить
        </button>
        <button 
          type="submit" 
          class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700"
          :disabled="loading"
        >
          {{ loading ? 'Сохранение...' : 'Сохранить анкету' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '@/stores/notification'
import ProfileNavigation from '~/components/ProfileNavigation.vue'

const router = useRouter()
const notification = useNotification()
const loading = ref(false)

// Форма анкеты
const form = reactive({
  workExperience: [
    {
      company: '',
      position: '',
      startDate: '',
      endDate: '',
      responsibilities: ''
    }
  ]
})

// Функции для работы с опытом работы
function addWorkExperience() {
  form.workExperience.push({
    company: '',
    position: '',
    startDate: '',
    endDate: '',
    responsibilities: ''
  })
}

function removeWorkExperience(index) {
  if (form.workExperience.length > 1) {
    form.workExperience.splice(index, 1)
  }
}

function getCurrentMonth() {
  const now = new Date()
  return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`
}

// Сохранение анкеты
async function saveAnketa() {
  try {
    loading.value = true
    
    // Имитация запроса к API
    await new Promise(resolve => setTimeout(resolve, 800))
    
    // Здесь будет реальный запрос к API
    // const response = await fetch('/api/profile/anketa', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //   },
    //   body: JSON.stringify(form)
    // })
    
    // if (!response.ok) throw new Error('Ошибка сохранения анкеты')
    
    notification.show('Анкета успешно сохранена', 'success')
  } catch (error) {
    console.error('Ошибка при сохранении анкеты:', error)
    notification.show('Не удалось сохранить анкету', 'error')
  } finally {
    loading.value = false
  }
}
</script>
