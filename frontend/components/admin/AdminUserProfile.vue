<template>
  <div>
    <div v-if="loading" class="flex justify-center items-center py-10">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>
    
    <form v-else @submit.prevent="saveProfile" class="space-y-6">
      <!-- Аватар и статус поиска работы -->
      <div class="flex items-start space-x-4">
        <div class="relative">
          <img 
            v-if="avatarPreview || user.avatar" 
            :src="avatarPreview || `/storage/${user.avatar}`" 
            class="w-32 h-32 rounded-full object-cover"
            alt="Аватар пользователя"
          />
          <div 
            v-else
            class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          
          <label 
            class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-md cursor-pointer"
            for="avatarInput"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </label>
          <input
            id="avatarInput"
            type="file"
            class="hidden"
            accept="image/*"
            @change="handleAvatarChange"
          />
        </div>

        <div class="flex-1">
          <div class="flex justify-between items-start">
            <div>
              <h2 class="text-xl font-semibold">{{ user.name }}</h2>
              <p class="text-gray-600">{{ user.email }}</p>
            </div>
            
            <!-- Статус поиска работы -->
            <div class="w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Статус поиска работы</label>
              <select 
                v-model="form.jobSearchStatus" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              >
                <option value="none">Нет статуса</option>
                <option value="active">В активном поиске работы</option>
                <option value="open">Рассмотрю предложения</option>
                <option value="closed">Работу не ищу</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Основная информация -->
      <div class="grid grid-cols-1 gap-6 mt-4">
        <h3 class="text-lg font-medium mb-2">Основная информация</h3>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">ФИО</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            placeholder="Иванов Иван Иванович"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input
            v-model="form.email"
            type="email"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            placeholder="example@mail.com"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
          <input
            v-model="form.phone"
            type="tel"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            placeholder="+7 (___) ___-__-__"
          />
          <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
        </div>
      </div>

      <!-- Дополнительная информация -->
      <div class="border-t pt-6 mt-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Дата рождения</label>
            <input 
              type="date" 
              v-model="form.birthDate" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
            />
            <p v-if="errors.birthDate" class="mt-1 text-sm text-red-600">{{ errors.birthDate }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Пол</label>
            <select 
              v-model="form.gender" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="">Не указано</option>
              <option value="male">Мужской</option>
              <option value="female">Женский</option>
            </select>
            <p v-if="errors.gender" class="mt-1 text-sm text-red-600">{{ errors.gender }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Гражданство</label>
            <select 
              v-model="form.citizenship" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="">Не указано</option>
              <option value="russia">Россия</option>
              <option value="belarus">Беларусь</option>
              <option value="kazakhstan">Казахстан</option>
              <option value="other">Другое</option>
            </select>
            <p v-if="errors.citizenship" class="mt-1 text-sm text-red-600">{{ errors.citizenship }}</p>
          </div>
        </div>
      </div>

      <!-- Место проживания -->
      <div class="border-t pt-6 mt-6">
        <h3 class="text-lg font-medium mb-4">Место проживания</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Город проживания</label>
            <input 
              type="text" 
              v-model="form.city" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              placeholder="Город"
            />
            <p v-if="errors.city" class="mt-1 text-sm text-red-600">{{ errors.city }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Район</label>
            <input 
              type="text" 
              v-model="form.district" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              placeholder="Район"
            />
            <p v-if="errors.district" class="mt-1 text-sm text-red-600">{{ errors.district }}</p>
          </div>
        </div>
      </div>

      <!-- Роль пользователя -->
      <div class="border-t pt-6 mt-6">
        <h3 class="text-lg font-medium mb-4">Роль пользователя</h3>
        <div class="flex items-center">
          <input 
            id="is_admin" 
            type="checkbox" 
            v-model="form.is_admin" 
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
          />
          <label for="is_admin" class="ml-2 block text-sm text-gray-900">
            Администратор
          </label>
        </div>
      </div>

      <!-- Кнопки действий -->
      <div class="flex justify-end space-x-4 pt-6 mt-4">
        <button
          type="button"
          @click="$router.push('/admin/users')"
          class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
          Отмена
        </button>
        <button
          type="submit"
          class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700"
          :disabled="loading"
        >
          {{ loading ? 'Сохранение...' : 'Сохранить изменения' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '@/stores/notification'
import { useRuntimeConfig } from '#app'

const props = defineProps({
  userId: {
    type: String,
    required: true
  }
})

const router = useRouter()
const notification = useNotification()
const config = useRuntimeConfig()

// Состояние
const user = ref({})
const loading = ref(false)
const avatarPreview = ref(null)
const errors = ref({})

// Форма
const form = reactive({
  name: '',
  email: '',
  phone: '',
  specialization: '',
  avatar: null,
  birthDate: '',
  gender: '',
  citizenship: '',
  city: '',
  district: '',
  jobSearchStatus: 'none',
  is_admin: false
})

// Загрузка данных пользователя
const loadUserData = async () => {
  try {
    loading.value = true
    
    // В реальном приложении здесь будет запрос к API
    const response = await fetch(`${config.public.apiBase}/api/admin/users/${props.userId}/profile`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      }
    })
    
    if (!response.ok) {
      throw new Error('Ошибка загрузки данных пользователя')
    }
    
    const data = await response.json()
    user.value = data
    
    // Заполняем форму
    form.name = data.name
    form.email = data.email
    form.phone = data.phone || ''
    form.specialization = data.specialization || ''
    form.birthDate = data.birthDate || ''
    form.gender = data.gender || ''
    form.citizenship = data.citizenship || ''
    form.city = data.city || ''
    form.district = data.district || ''
    form.jobSearchStatus = data.jobSearchStatus || 'none'
    form.is_admin = data.is_admin || false
  } catch (error) {
    console.error('Ошибка при загрузке данных пользователя:', error)
    notification.show('Ошибка загрузки данных пользователя', 'error')
  } finally {
    loading.value = false
  }
}

// Обработка изменения аватара
const handleAvatarChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.avatar = file
    avatarPreview.value = URL.createObjectURL(file)
  }
}

// Сохранение профиля
const saveProfile = async () => {
  try {
    loading.value = true
    errors.value = {}

    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('email', form.email)
    formData.append('phone', form.phone)
    formData.append('specialization', form.specialization)
    formData.append('birthDate', form.birthDate || '')
    formData.append('gender', form.gender || '')
    formData.append('citizenship', form.citizenship || '')
    formData.append('city', form.city || '')
    formData.append('district', form.district || '')
    formData.append('jobSearchStatus', form.jobSearchStatus || 'none')
    formData.append('is_admin', form.is_admin ? '1' : '0')
    
    if (form.avatar instanceof File) {
      formData.append('avatar', form.avatar)
    }

    // В реальном приложении здесь будет запрос к API
    const response = await fetch(`${config.public.apiBase}/api/admin/users/${props.userId}/profile`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: formData
    })

    if (!response.ok) {
      const data = await response.json()
      if (data.errors) {
        errors.value = data.errors
        notification.show('Пожалуйста, исправьте ошибки в форме', 'error')
        return
      }
      throw new Error('Ошибка сохранения профиля')
    }

    notification.show('Профиль успешно обновлен', 'success')
    await loadUserData()
  } catch (error) {
    console.error('Ошибка при сохранении профиля:', error)
    notification.show('Ошибка сохранения профиля', 'error')
  } finally {
    loading.value = false
  }
}

// Загрузка данных при монтировании
onMounted(() => {
  loadUserData()
})
</script>
