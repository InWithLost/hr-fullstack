<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Создание нового пользователя</h1>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <form @submit.prevent="createUser" class="space-y-6">
                <div class="px-4 py-5 sm:p-6">
                  <!-- Аватар -->
                  <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700">Фото профиля</label>
                    <div class="mt-2 flex items-center space-x-6">
                      <div class="flex-shrink-0">
                        <img
                          class="h-24 w-24 rounded-full"
                          :src="previewImage || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(form.name)"
                          :alt="form.name"
                        />
                      </div>
                      <div>
                        <input
                          type="file"
                          ref="fileInput"
                          @change="handleFileUpload"
                          accept="image/*"
                          class="hidden"
                        />
                        <button
                          type="button"
                          @click="$refs.fileInput.click()"
                          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                          Загрузить фото
                        </button>
                        <button
                          v-if="previewImage"
                          type="button"
                          @click="removeImage"
                          class="ml-3 text-sm text-red-600 hover:text-red-500"
                        >
                          Удалить
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Основная информация -->
                  <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                      <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        required
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.name ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                    </div>

                    <div>
                      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                      <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.email ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email }}</p>
                    </div>

                    <div>
                      <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                      <input
                        type="tel"
                        id="phone"
                        v-model="form.phone"
                        required
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.phone ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.phone" class="mt-2 text-sm text-red-600">{{ errors.phone }}</p>
                    </div>

                    <!-- Дополнительная информация -->
                    <div class="sm:col-span-2 mt-6 mb-2">
                      <h3 class="text-lg font-medium">Дополнительная информация</h3>
                    </div>

                    <div>
                      <label for="birthDate" class="block text-sm font-medium text-gray-700">Дата рождения</label>
                      <input
                        type="date"
                        id="birthDate"
                        v-model="form.birthDate"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.birthDate ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.birthDate" class="mt-2 text-sm text-red-600">{{ errors.birthDate }}</p>
                    </div>

                    <div>
                      <label for="gender" class="block text-sm font-medium text-gray-700">Пол</label>
                      <select
                        id="gender"
                        v-model="form.gender"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.gender ? 'border-red-300' : ''
                        ]"
                      >
                        <option value="">Выберите пол</option>
                        <option value="male">Мужской</option>
                        <option value="female">Женский</option>
                      </select>
                      <p v-if="errors.gender" class="mt-2 text-sm text-red-600">{{ errors.gender }}</p>
                    </div>

                    <div>
                      <label for="citizenship" class="block text-sm font-medium text-gray-700">Гражданство</label>
                      <select
                        id="citizenship"
                        v-model="form.citizenship"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.citizenship ? 'border-red-300' : ''
                        ]"
                      >
                        <option value="">Выберите гражданство</option>
                        <option value="russia">Россия</option>
                        <option value="belarus">Беларусь</option>
                        <option value="kazakhstan">Казахстан</option>
                        <option value="other">Другое</option>
                      </select>
                      <p v-if="errors.citizenship" class="mt-2 text-sm text-red-600">{{ errors.citizenship }}</p>
                    </div>

                    <div class="sm:col-span-2 mt-6 mb-2">
                      <h3 class="text-lg font-medium">Место проживания</h3>
                    </div>

                    <div>
                      <label for="city" class="block text-sm font-medium text-gray-700">Город проживания</label>
                      <div class="relative">
                        <input
                          type="text"
                          id="city"
                          v-model="citySearch"
                          @input="searchCities"
                          @focus="showCityDropdown = true"
                          :class="[
                            'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                            errors.city ? 'border-red-300' : ''
                          ]"
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
                      <p v-if="errors.city" class="mt-2 text-sm text-red-600">{{ errors.city }}</p>
                    </div>

                    <div>
                      <label for="district" class="block text-sm font-medium text-gray-700">Район</label>
                      <div class="relative">
                        <input
                          type="text"
                          id="district"
                          v-model="districtSearch"
                          @input="searchDistricts"
                          @focus="showDistrictDropdown = true"
                          :disabled="!form.city"
                          :class="[
                            'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                            errors.district ? 'border-red-300' : '',
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
                      <p v-if="errors.district" class="mt-2 text-sm text-red-600">{{ errors.district }}</p>
                    </div>

                    <div class="sm:col-span-2 mt-6 mb-2">
                      <h3 class="text-lg font-medium">Пароль</h3>
                    </div>

                    <div class="sm:col-span-2">
                      <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                      <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.password ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.password" class="mt-2 text-sm text-red-600">{{ errors.password }}</p>
                    </div>

                    <div class="sm:col-span-2">
                      <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля</label>
                      <input
                        type="password"
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        required
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.password_confirmation ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ errors.password_confirmation }}</p>
                    </div>

                    <div class="sm:col-span-2">
                      <label class="flex items-center">
                        <input
                          type="checkbox"
                          v-model="form.is_admin"
                          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                        <span class="ml-2 text-sm text-gray-900">Администратор</span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Кнопки действий -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                  <button
                    type="button"
                    @click="router.push('/admin/users')"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    Отмена
                  </button>
                  <button
                    type="submit"
                    :disabled="isSubmitting"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ isSubmitting ? 'Создание...' : 'Создать' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'

const router = useRouter()
const notificationStore = useNotification()

const errors = ref({})
const isSubmitting = ref(false)
const previewImage = ref(null)
const fileInput = ref(null)

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

const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  is_admin: false,
  avatar: null,
  birthDate: '',
  gender: '',
  citizenship: '',
  city: '',
  district: ''
})

definePageMeta({ middleware: 'admin' })

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
  form.value.city = city.name
  citySearch.value = city.name
  showCityDropdown.value = false
  
  // Сброс района при смене города
  form.value.district = ''
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
  if (!form.value.city || districtSearch.value.trim() === '') {
    filteredDistricts.value = districtsMap[form.value.city] || []
  } else {
    const search = districtSearch.value.toLowerCase()
    filteredDistricts.value = (districtsMap[form.value.city] || []).filter(district => 
      district.name.toLowerCase().includes(search)
    )
  }
}

// Выбор района
const selectDistrict = (district) => {
  form.value.district = district.name
  districtSearch.value = district.name
  showDistrictDropdown.value = false
}

// Инициализация списка городов при загрузке
searchCities()

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
watch(() => form.value.city, (newCity) => {
  if (!newCity) {
    form.value.district = ''
    districtSearch.value = ''
    filteredDistricts.value = []
  }
})

// Обработка загрузки файла
const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.avatar = file
    const reader = new FileReader()
    reader.onload = (e) => {
      previewImage.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

// Удаление загруженного изображения
const removeImage = () => {
  form.value.avatar = null
  previewImage.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

// Создание пользователя
const createUser = async () => {
  errors.value = {}
  isSubmitting.value = true

  try {
    const formData = new FormData()
    Object.keys(form.value).forEach(key => {
      if (form.value[key] !== null && form.value[key] !== '') {
        formData.append(key, form.value[key])
      }
    })

    const response = await fetch('http://localhost:8000/api/admin/users', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: formData
    })

    if (response.ok) {
      const newUser = await response.json()
      notificationStore.success('Пользователь успешно создан')
      router.push(`/admin/users/${newUser.id}`)
    } else {
      const error = await response.json()
      if (error.errors) {
        errors.value = error.errors
      } else {
        notificationStore.error(error.message || 'Ошибка при создании пользователя')
      }
    }
  } catch (error) {
    console.error('Ошибка при создании пользователя:', error)
    notificationStore.error('Ошибка при создании пользователя')
  } finally {
    isSubmitting.value = false
  }
}
</script> 