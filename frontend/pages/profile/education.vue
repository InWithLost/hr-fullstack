<template>
  <div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Личный кабинет</h1>

    <!-- Навигационное меню -->
    <ProfileNavigation currentPage="education" />

    <form @submit.prevent="saveEducation" class="space-y-6" novalidate>


      <!-- Образование -->
      <div>
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Образование</h3>
          <button 
            type="button" 
            @click="addEducation" 
            class="text-sm text-primary-600 hover:text-primary-800"
          >
            + Добавить образование
          </button>
        </div>
        <div 
          v-for="(edu, index) in form.education" 
          :key="index" 
          class="mb-6 border border-gray-200 rounded-lg p-4"
        >
          <div class="flex justify-between items-start mb-3">
            <h4 class="font-medium">Образование #{{ index + 1 }}</h4>
            <button 
              v-if="form.education.length > 1" 
              type="button" 
              @click="removeEducation(index)" 
              class="text-red-600 hover:text-red-800"
            >
              Удалить
            </button>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Учебное заведение *</label>
              <input 
                type="text" 
                v-model="edu.institution" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                placeholder="Название учебного заведения" 
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Уровень образования *</label>
              <select 
                v-model="edu.level" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              >
                <option value="">Выберите уровень</option>
                <option value="secondary">Среднее</option>
                <option value="vocational">Среднее профессиональное</option>
                <option value="bachelor">Бакалавриат</option>
                <option value="specialist">Специалитет</option>
                <option value="master">Магистратура</option>
                <option value="postgraduate">Аспирантура</option>
                <option value="doctorate">Докторантура</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Специальность *</label>
              <input 
                type="text" 
                v-model="edu.specialization" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                placeholder="Специальность" 
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Год окончания *</label>
              <input 
                type="number" 
                v-model="edu.endYear" 
                required 
                min="1950"
                :max="new Date().getFullYear() + 10"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
              />
            </div>
          </div>

          <!-- Дополнительные квалификации -->
          <div class="mt-6 border-t pt-4">
            <h5 class="font-medium mb-3">Дополнительные квалификации</h5>
            
            <!-- Повышение квалификации -->
            <div class="mb-4">
              <div class="flex justify-between items-center mb-2">
                <h6 class="text-sm font-medium text-gray-700">Повышение квалификации</h6>
                <button 
                  type="button" 
                  @click="addQualification(index)" 
                  class="text-xs text-primary-600 hover:text-primary-800"
                >
                  + Добавить повышение квалификации
                </button>
              </div>
            
              <div 
                v-for="(qual, qIndex) in edu.qualifications" 
                :key="qIndex" 
                class="mb-3 border border-gray-100 rounded p-3 bg-gray-50"
              >
                <div class="flex justify-end mb-2">
                  <button 
                    type="button" 
                    @click="removeQualification(index, qIndex)" 
                    class="text-xs text-red-600 hover:text-red-800"
                  >
                    Удалить
                  </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Название программы *</label>
                    <input 
                      type="text" 
                      v-model="qual.name" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                      placeholder="Название программы" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Организация *</label>
                    <input 
                      type="text" 
                      v-model="qual.institution" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                      placeholder="Название организации" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Дата начала *</label>
                    <input 
                      type="date" 
                      v-model="qual.startDate" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Дата окончания *</label>
                    <input 
                      type="date" 
                      v-model="qual.endDate" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Количество часов</label>
                    <input 
                      type="number" 
                      v-model="qual.hours" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Номер документа</label>
                    <input 
                      type="text" 
                      v-model="qual.documentNumber" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                </div>
              </div>
              
              <div v-if="edu.qualifications.length === 0" class="text-sm text-gray-500 italic">
                Нет добавленных повышений квалификации
              </div>
            </div>
            
            <!-- Профессиональная переподготовка -->
            <div class="mb-4">
              <div class="flex justify-between items-center mb-2">
                <h6 class="text-sm font-medium text-gray-700">Профессиональная переподготовка</h6>
                <button 
                  type="button" 
                  @click="addRetraining(index)" 
                  class="text-xs text-primary-600 hover:text-primary-800"
                >
                  + Добавить переподготовку
                </button>
              </div>
              
              <div 
                v-for="(retraining, rIndex) in edu.retrainings" 
                :key="rIndex" 
                class="mb-3 border border-gray-100 rounded p-3 bg-gray-50"
              >
                <div class="flex justify-end mb-2">
                  <button 
                    type="button" 
                    @click="removeRetraining(index, rIndex)" 
                    class="text-xs text-red-600 hover:text-red-800"
                  >
                    Удалить
                  </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Название программы *</label>
                    <input 
                      type="text" 
                      v-model="retraining.name" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                      placeholder="Название программы" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Организация *</label>
                    <input 
                      type="text" 
                      v-model="retraining.institution" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                      placeholder="Название организации" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Дата начала *</label>
                    <input 
                      type="date" 
                      v-model="retraining.startDate" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Дата окончания *</label>
                    <input 
                      type="date" 
                      v-model="retraining.endDate" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Новая квалификация *</label>
                    <input 
                      type="text" 
                      v-model="retraining.newQualification" 
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Номер диплома</label>
                    <input 
                      type="text" 
                      v-model="retraining.diplomaNumber" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                </div>
              </div>
              
              <div v-if="edu.retrainings.length === 0" class="text-sm text-gray-500 italic">
                Нет добавленных профессиональных переподготовок
              </div>
            </div>
            
            <!-- Аккредитация/Сертификация -->
            <div class="mb-4">
              <div class="flex justify-between items-center mb-2">
                <h6 class="text-sm font-medium text-gray-700">Аккредитация/Сертификация</h6>
                <button 
                  type="button" 
                  @click="addAccreditation(index)" 
                  class="text-xs text-primary-600 hover:text-primary-800"
                >
                  + Добавить аккредитацию
                </button>
              </div>
              
              <div 
                v-for="(accreditation, aIndex) in edu.accreditations" 
                :key="aIndex" 
                class="mb-3 border border-gray-100 rounded p-3 bg-gray-50"
              >
                <div class="flex justify-end mb-2">
                  <button 
                    type="button" 
                    @click="removeAccreditation(index, aIndex)" 
                    class="text-xs text-red-600 hover:text-red-800"
                  >
                    Удалить
                  </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Кто выдал Аккредитацию/Сертификацию *</label>
                    <input 
                      type="text" 
                      v-model="accreditation.institution" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                      placeholder="Название организации" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Номер Аккредитации/Сертификации</label>
                    <input 
                      type="text" 
                      v-model="accreditation.certificateNumber" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Дата получения *</label>
                    <input 
                      type="date" 
                      v-model="accreditation.issueDate" 
                      required 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" 
                    />
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Срок действия до (автоматически +5 лет)</label>
                    <input 
                      type="date" 
                      v-model="accreditation.expiryDate" 
                      readonly
                      class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm" 
                    />
                  </div>
                </div>
              </div>
              
              <div v-if="edu.accreditations.length === 0" class="text-sm text-gray-500 italic">
                Нет добавленных аккредитаций/сертификаций
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Кнопки действий -->
      <div class="flex justify-end space-x-4 pt-6">
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
          {{ loading ? 'Сохранение...' : 'Сохранить образование' }}
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

const router = useRouter()
const notification = useNotification()
const loading = ref(false)

// Загрузка данных при монтировании компонента
onMounted(() => {
  loadEducationData()
})

// Форма образования
const form = reactive({
  education: [
    {
      institution: '',
      specialization: '',
      level: '',
      endYear: '',
      qualifications: [], // Повышение квалификации
      retrainings: [],    // Профессиональная переподготовка
      accreditations: []  // Аккредитация/Сертификация
    }
  ]
})

// Функции для работы с образованием
function addEducation() {
  form.education.push({
    institution: '',
    specialization: '',
    level: '',
    endYear: '',
    qualifications: [],
    retrainings: [],
    accreditations: []
  })
}

function removeEducation(index) {
  if (form.education.length > 1) {
    form.education.splice(index, 1)
  }
}

// Функции для работы с повышением квалификации
function addQualification(eduIndex) {
  form.education[eduIndex].qualifications.push({
    name: '',
    institution: '',
    startDate: '',
    endDate: '',
    hours: '',
    documentNumber: ''
  })
}

function removeQualification(eduIndex, qualIndex) {
  form.education[eduIndex].qualifications.splice(qualIndex, 1)
}

// Функции для работы с профессиональной переподготовкой
function addRetraining(eduIndex) {
  form.education[eduIndex].retrainings.push({
    name: '',
    institution: '',
    startDate: '',
    endDate: '',
    newQualification: '',
    diplomaNumber: ''
  })
}

function removeRetraining(eduIndex, retrIndex) {
  form.education[eduIndex].retrainings.splice(retrIndex, 1)
}

// Функции для работы с аккредитацией/сертификацией
function addAccreditation(eduIndex) {
  form.education[eduIndex].accreditations.push({
    institution: '',
    issueDate: '',
    expiryDate: '',
    certificateNumber: ''
  })
}

// Функция для расчета срока действия аккредитации (+5 лет)
function calculateExpiryDate(issueDate) {
  if (!issueDate) return ''
  
  const date = new Date(issueDate)
  date.setFullYear(date.getFullYear() + 5)
  
  // Форматирование даты в формате YYYY-MM-DD для input type="date"
  return date.toISOString().split('T')[0]
}

function removeAccreditation(eduIndex, accIndex) {
  form.education[eduIndex].accreditations.splice(accIndex, 1)
}

function resetForm() {
  form.education = [
    {
      institution: '',
      specialization: '',
      level: '',
      endYear: '',
      qualifications: [],
      retrainings: [],
      accreditations: []
    }
  ]
}

// Наблюдатель за изменениями в форме для автоматического расчета срока действия
watch(() => form.education, (newEducation) => {
  // Обрабатываем все записи об образовании
  newEducation.forEach(edu => {
    // Обрабатываем все аккредитации
    if (edu.accreditations && edu.accreditations.length > 0) {
      edu.accreditations.forEach(acc => {
        if (acc.issueDate && !acc.expiryDate) {
          acc.expiryDate = calculateExpiryDate(acc.issueDate)
        }
      })
    }
  })
}, { deep: true })

// Дополнительный наблюдатель для обновления срока действия при изменении даты получения
watch(() => {
  const result = []
  form.education.forEach((edu, eduIndex) => {
    if (edu.accreditations) {
      edu.accreditations.forEach((acc, accIndex) => {
        result.push({ eduIndex, accIndex, issueDate: acc.issueDate })
      })
    }
  })
  return result
}, (newValues) => {
  newValues.forEach(({ eduIndex, accIndex, issueDate }) => {
    if (issueDate) {
      form.education[eduIndex].accreditations[accIndex].expiryDate = calculateExpiryDate(issueDate)
    }
  })
}, { deep: true })

// Загрузка данных образования
async function loadEducationData() {
  try {
    loading.value = true
    
    // Имитация загрузки данных с сервера
    // В реальном приложении здесь будет запрос к API
    await new Promise(resolve => setTimeout(resolve, 500))
    
    // Здесь будет реальный запрос к API
    // const response = await fetch(`${config.public.apiBase}/api/profile/education`, {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // 
    // if (!response.ok) throw new Error('Ошибка загрузки данных образования')
    // 
    // const data = await response.json()
    // if (data && data.education && data.education.length > 0) {
    //   form.education = data.education
    // }
  } catch (error) {
    console.error('Ошибка при загрузке данных образования:', error)
    notification.show('Не удалось загрузить данные образования', 'error')
  } finally {
    loading.value = false
  }
}

// Сохранение образования
async function saveEducation() {
  try {
    loading.value = true
    
    // Валидация данных перед отправкой
    const isValid = form.education.every(edu => {
      return edu.institution && edu.level && edu.endYear
    })
    
    if (!isValid) {
      notification.show('Заполните все обязательные поля', 'error')
      return
    }
    
    // Имитация запроса к API
    await new Promise(resolve => setTimeout(resolve, 800))
    
    // Здесь будет реальный запрос к API
    // const response = await fetch(`${config.public.apiBase}/api/profile/education`, {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify({ education: form.education })
    // })
    // 
    // if (!response.ok) {
    //   const errorData = await response.json()
    //   throw new Error(errorData.message || 'Ошибка сохранения образования')
    // }
    
    notification.show('Образование успешно сохранено', 'success')
    
    // Перенаправление на страницу профиля после успешного сохранения
    // router.push('/profile')
  } catch (error) {
    console.error('Ошибка при сохранении образования:', error)
    notification.show(error.message || 'Не удалось сохранить образование', 'error')
  } finally {
    loading.value = false
  }
}
</script>
