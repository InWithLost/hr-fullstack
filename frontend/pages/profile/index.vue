<template>
  <div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Личный кабинет</h1>

    <!-- Навигационное меню -->
    <ProfileNavigation currentPage="profile" />

    <!-- Форма редактирования профиля -->
    <form @submit.prevent="saveProfile" class="space-y-6">
      <!-- Аватар -->
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
            <UserIcon class="w-16 h-16 text-gray-400" />
          </div>
          
          <label 
            class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-md cursor-pointer"
            :for="avatarInput"
          >
            <CameraIcon class="w-5 h-5 text-gray-600" />
          </label>
          <input
            ref="avatarInput"
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
              <option value="">Выберите пол</option>
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
              <option value="">Выберите гражданство</option>
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
            <div class="relative">
              <input 
                type="text" 
                id="city"
                v-model="citySearch" 
                @input="searchCities"
                @focus="showCityDropdown = true"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                placeholder="Начните вводить название города" 
              />
              <div 
                v-if="showCityDropdown && filteredCities.length > 0" 
                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm"
              >
                <div 
                  v-for="city in filteredCities" 
                  :key="city.id"
                  @click="selectCity(city)"
                  class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100"
                >
                  {{ city.name }}
                </div>
              </div>
            </div>
            <p v-if="errors.city" class="mt-1 text-sm text-red-600">{{ errors.city }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Район</label>
            <div class="relative">
              <input 
                type="text" 
                id="district"
                v-model="districtSearch" 
                @input="searchDistricts"
                @focus="showDistrictDropdown = true"
                :disabled="!form.city"
                :class="[
                  'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
                  !form.city ? 'bg-gray-100 cursor-not-allowed' : ''
                ]"
                :placeholder="form.city ? 'Начните вводить название района' : 'Сначала выберите город'" 
              />
              <div 
                v-if="showDistrictDropdown && filteredDistricts.length > 0" 
                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm"
              >
                <div 
                  v-for="district in filteredDistricts" 
                  :key="district.id"
                  @click="selectDistrict(district)"
                  class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100"
                >
                  {{ district.name }}
                </div>
              </div>
            </div>
            <p v-if="errors.district" class="mt-1 text-sm text-red-600">{{ errors.district }}</p>
          </div>
        </div>
      </div>

      <!-- Смена пароля -->
      <div class="border-t pt-6 mt-6">
        <h3 class="text-lg font-medium mb-4">Сменить пароль</h3>
        <div class="grid grid-cols-1 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Текущий пароль</label>
            <input
              v-model="form.current_password"
              type="password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
            <p v-if="errors.current_password" class="mt-1 text-sm text-red-600">{{ errors.current_password }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Новый пароль</label>
            <input
              v-model="form.password"
              type="password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Подтверждение пароля</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
          </div>
        </div>
      </div>

      <!-- Кнопки действий -->
      <div class="flex justify-end space-x-4 pt-6 mt-4">
        <button
          type="button"
          class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300"
          @click="resetForm"
        >
          Отменить
        </button>
        <button
          type="submit"
          class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
          :disabled="loading"
        >
          {{ loading ? 'Сохранение...' : 'Сохранить изменения' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '@/stores/notification'
import ProfileNavigation from '~/components/ProfileNavigation.vue'
import { UserIcon, CameraIcon } from '@heroicons/vue/24/outline'
import { useRuntimeConfig } from '#app'

const router = useRouter()
const notification = useNotification()
const config = useRuntimeConfig()

// Состояние
const user = ref({})
const loading = ref(false)
const avatarInput = ref(null)
const avatarPreview = ref(null)
const errors = ref({})

// Форма
const form = reactive({
  name: '',
  email: '',
  phone: '',
  specialization: '',
  current_password: '',
  password: '',
  password_confirmation: '',
  avatar: null,
  birthDate: '',
  gender: '',
  citizenship: '',
  city: '',
  district: '',
  jobSearchStatus: 'none' // Статус поиска работы: none, active, open, closed
})

// Переменные для работы с городами и районами
const citySearch = ref('')
const districtSearch = ref('')
const showCityDropdown = ref(false)
const showDistrictDropdown = ref(false)
const filteredCities = ref([])
const filteredDistricts = ref([])
const allCities = ref([
  { id: 1, name: 'Москва' },
  { id: 2, name: 'Санкт-Петербург' },
  { id: 3, name: 'Казань' },
  { id: 4, name: 'Новосибирск' },
  { id: 5, name: 'Екатеринбург' },
  { id: 6, name: 'Нижний Новгород' },
  { id: 7, name: 'Краснодар' },
  { id: 8, name: 'Сочи' }
])

// Районы по городам
const districtsMap = {
  'Москва': [
    { id: 1, name: 'Ховрино' },
    { id: 2, name: 'Хорошево-Мневники' },
    { id: 3, name: 'Аэропорт' },
    { id: 4, name: 'Беговой' },
    { id: 5, name: 'Бибирево' },
    { id: 6, name: 'Тверской' }
  ],
  'Санкт-Петербург': [
    { id: 7, name: 'Адмиралтейский' },
    { id: 8, name: 'Василеостровский' },
    { id: 9, name: 'Выборгский' },
    { id: 10, name: 'Калининский' },
    { id: 11, name: 'Приморский' }
  ],
  'Казань': [
    { id: 12, name: 'Авиастроительный' },
    { id: 13, name: 'Вахитовский' },
    { id: 14, name: 'Кировский' },
    { id: 15, name: 'Московский' }
  ],
  'Новосибирск': [
    { id: 16, name: 'Дзержинский' },
    { id: 17, name: 'Железнодорожный' },
    { id: 18, name: 'Заельцовский' },
    { id: 19, name: 'Ленинский' }
  ]
}

// Функции для работы с выбором города
const searchCities = () => {
  if (citySearch.value.trim() === '') {
    filteredCities.value = allCities.value
  } else {
    const search = citySearch.value.toLowerCase()
    filteredCities.value = allCities.value.filter(city => 
      city.name.toLowerCase().includes(search)
    )
  }
}

// Выбор города
const selectCity = (city) => {
  form.city = city.name
  citySearch.value = city.name
  showCityDropdown.value = false
  
  // Сброс района при смене города
  form.district = ''
  districtSearch.value = ''
  
  // Загрузка районов для выбранного города
  if (districtsMap[city.name]) {
    filteredDistricts.value = districtsMap[city.name]
  } else {
    filteredDistricts.value = []
  }
}

// Функции для работы с выбором района
const searchDistricts = () => {
  if (!form.city || districtSearch.value.trim() === '') {
    filteredDistricts.value = districtsMap[form.city] || []
  } else {
    const search = districtSearch.value.toLowerCase()
    filteredDistricts.value = (districtsMap[form.city] || []).filter(district => 
      district.name.toLowerCase().includes(search)
    )
  }
}

// Выбор района
const selectDistrict = (district) => {
  form.district = district.name
  districtSearch.value = district.name
  showDistrictDropdown.value = false
}

// Закрытие выпадающих списков при клике вне их
if (process.client) {
  window.addEventListener('click', (e) => {
    if (!e.target.closest('#city') && !e.target.closest('#cityDropdown')) {
      showCityDropdown.value = false
    }
    if (!e.target.closest('#district') && !e.target.closest('#districtDropdown')) {
      showDistrictDropdown.value = false
    }
  })
}

// Отслеживание изменений в поле города
watch(() => form.city, (newCity) => {
  if (!newCity) {
    form.district = ''
    districtSearch.value = ''
    filteredDistricts.value = []
  }
})

// Загрузка данных пользователя
const loadUserData = async () => {
  try {
    const response = await fetch(`${config.public.apiBase}/api/user`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      }
    })
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
    
    // Устанавливаем значения для поисковых полей
    citySearch.value = data.city || ''
    districtSearch.value = data.district || ''
    
    // Загружаем списки городов и районов
    searchCities()
    if (data.city && districtsMap[data.city]) {
      filteredDistricts.value = districtsMap[data.city]
    }
  } catch (error) {
    notification.show('Ошибка загрузки данных пользователя', 'error')
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
  loading.value = true
  errors.value = {}

  const formData = new FormData()
  formData.append('name', form.name)
  formData.append('email', form.email)
  formData.append('phone', form.phone)
  formData.append('specialization', form.specialization)
  
  // Добавляем новые поля
  formData.append('birthDate', form.birthDate || '')
  formData.append('gender', form.gender || '')
  formData.append('citizenship', form.citizenship || '')
  formData.append('city', form.city || '')
  formData.append('district', form.district || '')
  formData.append('jobSearchStatus', form.jobSearchStatus || 'none')
  
  if (form.current_password) {
    formData.append('current_password', form.current_password)
    formData.append('password', form.password)
    formData.append('password_confirmation', form.password_confirmation)
  }
  
  if (form.avatar) {
    formData.append('avatar', form.avatar)
  }

  try {
    const response = await fetch('/api/profile', {
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
    resetPasswordFields()
  } catch (error) {
    notification.show('Ошибка сохранения профиля', 'error')
  } finally {
    loading.value = false
  }
}

// Сброс формы
const resetForm = () => {
  loadUserData()
  resetPasswordFields()
  avatarPreview.value = null
  form.avatar = null
  errors.value = {}
}

// Сброс полей пароля
const resetPasswordFields = () => {
  form.current_password = ''
  form.password = ''
  form.password_confirmation = ''
}

// Загрузка данных при монтировании
onMounted(() => {
  loadUserData()
})
</script> 