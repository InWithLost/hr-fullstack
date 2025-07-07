<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Создание специальности</h1>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <form @submit.prevent="saveSpecialty">
                  <div class="space-y-6">
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700">Название</label>
                      <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        :class="{ 'border-red-300': errors.name }"
                        required
                      />
                      <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                    </div>
                    
                    <div>
                      <label for="category" class="block text-sm font-medium text-gray-700">Категория</label>
                      <select
                        id="category"
                        v-model="form.category_id"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                        :class="{ 'border-red-300': errors.category_id }"
                        required
                      >
                        <option value="" disabled>Выберите категорию</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                          {{ category.name }}
                        </option>
                      </select>
                      <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</p>
                    </div>
                    
                    <div>
                      <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                      <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        :class="{ 'border-red-300': errors.description }"
                      ></textarea>
                      <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                    </div>
                    
                    <div>
                      <label for="education_level" class="block text-sm font-medium text-gray-700">Уровень образования</label>
                      <select
                        id="education_level"
                        v-model="form.education_level"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                        :class="{ 'border-red-300': errors.education_level }"
                      >
                        <option value="" disabled>Выберите уровень образования</option>
                        <option v-for="level in educationLevels" :key="level.id" :value="level.id">
                          {{ level.name }}
                        </option>
                      </select>
                      <p v-if="errors.education_level" class="mt-1 text-sm text-red-600">{{ errors.education_level }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Изображение специальности</label>
                      <div class="mt-1 flex items-center">
                        <div v-if="imagePreview" class="mr-3">
                          <img :src="imagePreview" alt="Preview" class="h-20 w-20 object-cover rounded-md" />
                        </div>
                        <input
                          type="file"
                          accept="image/*"
                          @change="handleImageUpload"
                          class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100"
                        />
                      </div>
                      <p class="mt-1 text-sm text-gray-500">PNG, JPG или GIF до 2MB</p>
                      <p v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Средняя заработная плата по регионам</label>
                      <div v-for="(salary, index) in form.regional_salaries" :key="index" class="flex items-center space-x-2 mb-2">
                        <select
                          v-model="salary.city_id"
                          class="mt-1 block w-1/2 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                        >
                          <option value="" disabled>Выберите город</option>
                          <option v-for="city in cities" :key="city.id" :value="city.id">
                            {{ city.name }}
                          </option>
                        </select>
                        <div class="relative rounded-md shadow-sm w-1/3">
                          <input
                            type="number"
                            v-model="salary.amount"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="0"
                            min="0"
                          />
                          <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">₽</span>
                          </div>
                        </div>
                        <button 
                          type="button" 
                          @click="removeRegionalSalary(index)" 
                          class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                      <button 
                        type="button" 
                        @click="addRegionalSalary" 
                        class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      >
                        Добавить город
                      </button>
                    </div>
                    
                    <div class="flex items-center">
                      <input
                        type="checkbox"
                        id="is_active"
                        v-model="form.is_active"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                      />
                      <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Активна
                      </label>
                    </div>
                  </div>
                  
                  <div class="mt-6 flex justify-end space-x-3">
                    <button
                      type="button"
                      @click="goBack"
                      class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      Отмена
                    </button>
                    <button
                      type="submit"
                      class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      :disabled="isSubmitting"
                    >
                      {{ isSubmitting ? 'Сохранение...' : 'Сохранить' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import AdminHeader from '~/components/AdminHeader.vue'

definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const router = useRouter()
const notification = useNotification()

// Состояние формы
const isSubmitting = ref(false)
const form = ref({
  name: '',
  category_id: '',
  description: '',
  education_level: '',
  image: null,
  regional_salaries: [],
  is_active: true
})
const errors = ref({})
const imagePreview = ref(null)

// Данные для выпадающих списков
const categories = ref([])
const educationLevels = ref([])
const cities = ref([])

// Загрузка всех необходимых данных
async function loadData() {
  try {
    // В реальном приложении здесь будут параллельные API-запросы
    // Имитация загрузки данных
    setTimeout(() => {
      // Моковые данные для категорий
      categories.value = [
        { id: 1, name: 'Медицина' },
        { id: 2, name: 'IT' },
        { id: 3, name: 'Образование' },
        { id: 4, name: 'Финансы' },
        { id: 5, name: 'Маркетинг' }
      ]
      
      // Моковые данные для уровней образования
      educationLevels.value = [
        { id: 1, name: 'Высшее образование' },
        { id: 2, name: 'Среднее специальное образование' },
        { id: 3, name: 'Среднее образование' },
        { id: 4, name: 'Неоконченное высшее образование' }
      ]
      
      // Моковые данные для городов
      cities.value = [
        { id: 1, name: 'Москва' },
        { id: 2, name: 'Санкт-Петербург' },
        { id: 3, name: 'Новосибирск' },
        { id: 4, name: 'Екатеринбург' },
        { id: 5, name: 'Казань' }
      ]
    }, 500)
  } catch (error) {
    console.error('Ошибка при загрузке данных:', error)
    notification.show('Ошибка при загрузке данных', 'error')
  }
}

// Обработка загрузки изображения
function handleImageUpload(event) {
  const file = event.target.files[0]
  if (!file) return
  
  // Проверка размера файла (2MB)
  if (file.size > 2 * 1024 * 1024) {
    errors.value.image = 'Размер файла не должен превышать 2MB'
    return
  }
  
  // Проверка типа файла
  if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
    errors.value.image = 'Допустимые форматы: JPG, PNG, GIF'
    return
  }
  
  // Сохранение файла в форме
  form.value.image = file
  
  // Создание предварительного просмотра
  const reader = new FileReader()
  reader.onload = e => {
    imagePreview.value = e.target.result
  }
  reader.readAsDataURL(file)
  
  // Сброс ошибки, если она была
  errors.value.image = null
}

// Добавление новой строки для зарплаты по региону
function addRegionalSalary() {
  form.value.regional_salaries.push({
    city_id: '',
    amount: null
  })
}

// Удаление строки с зарплатой по региону
function removeRegionalSalary(index) {
  form.value.regional_salaries.splice(index, 1)
}

// Сохранение специальности
async function saveSpecialty() {
  isSubmitting.value = true
  errors.value = {}
  
  // Валидация обязательных полей
  if (!form.value.name) {
    errors.value.name = 'Название обязательно для заполнения'
  }
  
  if (!form.value.category_id) {
    errors.value.category_id = 'Выберите категорию'
  }
  
  if (!form.value.education_level) {
    errors.value.education_level = 'Выберите уровень образования'
  }
  
  // Валидация зарплат по регионам
  const invalidSalaries = form.value.regional_salaries.some(salary => {
    return !salary.city_id || salary.amount === null || salary.amount === ''
  })
  
  if (form.value.regional_salaries.length > 0 && invalidSalaries) {
    errors.value.regional_salaries = 'Заполните все поля зарплат или удалите незаполненные строки'
  }
  
  // Если есть ошибки, прерываем сохранение
  if (Object.keys(errors.value).length > 0) {
    isSubmitting.value = false
    return
  }
  
  try {
    // В реальном приложении здесь будет API-запрос с загрузкой файла
    // Для загрузки файла используется FormData
    
    // Имитация сохранения данных
    setTimeout(() => {
      notification.show('Специальность успешно создана', 'success')
      router.push('/admin/specialties')
    }, 1000)
  } catch (error) {
    console.error('Ошибка при сохранении специальности:', error)
    notification.show('Ошибка при сохранении специальности', 'error')
    isSubmitting.value = false
  }
}

// Возврат к списку специальностей
function goBack() {
  router.push('/admin/specialties')
}

// Загрузка данных при монтировании компонента
onMounted(() => {
  loadData()
  // Добавляем пустую строку для зарплаты по региону
  addRegionalSalary()
})
</script>
