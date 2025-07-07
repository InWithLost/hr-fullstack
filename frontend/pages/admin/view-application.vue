<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Управление откликом</h1>
            <button
              @click="returnToList"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Вернуться к списку
            </button>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div v-if="loading" class="flex justify-center items-center py-10">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
          </div>

          <div v-else-if="error" class="mt-8 bg-white shadow sm:rounded-lg p-6">
            <div class="text-center text-red-600">
              <p>{{ error }}</p>
              <button 
                @click="loadApplication" 
                class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
              >
                Попробовать снова
              </button>
            </div>
          </div>

          <div v-else class="mt-8 space-y-6">
            <!-- Информация о вакансии и кандидате -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Информация о вакансии -->
                  <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Информация о вакансии</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                      <div class="mb-4">
                        <h3 class="text-xl font-bold">{{ application.job.title }}</h3>
                        <p class="text-gray-600">{{ application.job.company }}</p>
                      </div>
                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <p class="text-sm text-gray-500">Зарплата</p>
                          <p>{{ application.job.salary || 'Не указана' }}</p>
                        </div>
                        <div>
                          <p class="text-sm text-gray-500">Локация</p>
                          <p>{{ application.job.location || 'Не указана' }}</p>
                        </div>
                      </div>
                      <div class="mt-4">
                        <button 
                          @click="viewJob(application.job.id)"
                          class="text-indigo-600 hover:text-indigo-900 text-left"
                        >
                          Просмотреть вакансию
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Информация о кандидате -->
                  <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Информация о кандидате</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                      <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                          <img 
                            v-if="application.user.avatar" 
                            :src="application.user.avatar" 
                            :alt="application.user.name"
                            class="h-full w-full object-cover"
                          />
                          <span v-else class="text-lg font-medium text-gray-500">
                            {{ application.user.fullName ? application.user.fullName.charAt(0) : 'U' }}
                          </span>
                        </div>
                        <div class="ml-4">
                          <h3 class="text-lg font-medium">{{ application.user.fullName }}</h3>
                          <p class="text-gray-600">{{ application.user.email }}</p>
                        </div>
                      </div>
                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <p class="text-sm text-gray-500">Телефон</p>
                          <p>{{ application.user.phone || 'Не указан' }}</p>
                        </div>
                        <div>
                          <p class="text-sm text-gray-500">Город</p>
                          <p>{{ application.user.city || 'Не указан' }}</p>
                        </div>
                      </div>
                      <div class="mt-4">
                        <NuxtLink 
                          :to="{
                            path: `/admin/users/${application.user.id}/profile/`,
                            query: { 
                              from_application: 'true',
                              application_id: route.query.id
                            }
                          }"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          Просмотреть профиль
                        </NuxtLink>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Сопроводительное письмо кандидата -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Сопроводительное письмо кандидата</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <p v-if="application.cover_letter">{{ application.cover_letter }}</p>
                  <p v-else class="text-gray-500 italic">Кандидат не оставил сопроводительное письмо</p>
                </div>
              </div>
            </div>

            <!-- Управление статусом отклика -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Статус отклика</h2>
                
                <!-- Текущий статус -->
                <div class="mb-6">
                  <span 
                    class="px-3 py-1 rounded-full text-sm font-medium"
                    :class="[
                      application.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                      application.status === 'interview' ? 'bg-blue-100 text-blue-800' : 
                      application.status === 'reserve' ? 'bg-purple-100 text-purple-800' : 
                      application.status === 'rejected' ? 'bg-red-100 text-red-800' : 
                      'bg-green-100 text-green-800'
                    ]"
                  >
                    {{ 
                      application.status === 'pending' ? 'На рассмотрении' : 
                      application.status === 'interview' ? 'Назначено собеседование' : 
                      application.status === 'reserve' ? 'В резерве' : 
                      application.status === 'rejected' ? 'Отклонено' : 
                      'Трудоустроен'
                    }}
                  </span>
                  <p class="mt-2 text-sm text-gray-500">
                    Последнее обновление: {{ formatDate(application.updated_at) }}
                  </p>
                </div>
                
                <!-- Форма изменения статуса -->
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                  <h3 class="text-md font-medium text-gray-900 mb-3">Изменить статус</h3>
                  
                  <!-- Выбор нового статуса -->
                  <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Новый статус</label>
                    <select 
                      v-model="newStatus" 
                      @change="handleStatusChange(newStatus)" 
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    >
                      <option value="" disabled>Выберите статус</option>
                      <option value="pending">На рассмотрении</option>
                      <option value="interview">Назначить собеседование</option>
                      <option value="rejected">Отклонить</option>
                      <option value="reserve">В резерв</option>
                      <option value="hired">Принять на работу</option>
                    </select>
                  </div>
                  
                  <!-- Динамические поля в зависимости от выбранного статуса -->
                  <div v-if="newStatus === 'interview'" class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Дата</label>
                        <input type="date" v-model="statusDetails.interview.date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Время</label>
                        <input type="time" v-model="statusDetails.interview.time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                      </div>
                      <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Место проведения</label>
                        <input type="text" v-model="statusDetails.interview.location" placeholder="Адрес или ссылка на видеоконференцию" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий для кандидата (приглашение на собеседование)</label>
                      <textarea v-model="statusDetails.interview.publicComment" rows="3" placeholder="Например: Приглашаем вас на собеседование. Пожалуйста, подтвердите свое присутствие." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="mt-3">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Внутренняя заметка (детали для команды)</label>
                      <textarea v-model="statusDetails.interview.internalNote" rows="2" placeholder="Например: Подготовить вопросы по предыдущему опыту. Проверить тестовое задание." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                  </div>
                  
                  <div v-if="newStatus === 'rejected'" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Причина отказа (для кандидата)</label>
                    <textarea v-model="statusDetails.rejected.publicComment" rows="3" placeholder="Например: Мы благодарим вас за интерес к нашей компании, но в настоящее время мы не можем предложить вам вакансию." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    <div class="mt-2 mb-3">
                      <label class="inline-flex items-center">
                        <input type="checkbox" v-model="statusDetails.rejected.sendFeedback" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Отправить этот комментарий кандидату</span>
                      </label>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Внутренняя заметка (детали для команды)</label>
                      <textarea v-model="statusDetails.rejected.internalNote" rows="2" placeholder="Например: Не хватило опыта в X, не подошел по культуре." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                  </div>
                  
                  <div v-if="newStatus === 'pending'" class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий для кандидата (опционально)</label>
                      <textarea v-model="statusDetails.pending.publicComment" rows="2" placeholder="Например: Ваша заявка принята, ожидайте обратной связи." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="mt-3">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Внутренняя заметка (опционально)</label>
                      <textarea v-model="statusDetails.pending.internalNote" rows="2" placeholder="Например: Первичный скрининг пройден, выглядит перспективно." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                  </div>
                  
                  <div v-if="newStatus === 'reserve'" class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий для кандидата (о помещении в резерв)</label>
                    <textarea v-model="statusDetails.reserve.publicComment" rows="3" placeholder="Например: В данный момент мы не готовы сделать предложение, но хотели бы сохранить ваш контакт для будущих вакансий." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    <div class="mt-3">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Внутренняя заметка (условия резерва)</label>
                      <textarea v-model="statusDetails.reserve.internalNote" rows="2" placeholder="Например: Сильный кандидат, рассмотреть на позицию Y через 3 месяца." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                  </div>
                  
                  <div v-if="newStatus === 'hired'" class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Дата выхода на работу</label>
                        <input type="date" v-model="statusDetails.hired.startDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Должность</label>
                        <input type="text" v-model="statusDetails.hired.position" placeholder="Название должности" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий для кандидата (поздравление, детали)</label>
                      <textarea v-model="statusDetails.hired.publicComment" rows="3" placeholder="Например: Поздравляем с принятием на работу! Ваш первый рабочий день [дата] в [время]. Пожалуйста, возьмите с собой [документы]." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                    <div class="mt-3">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Внутренняя заметка (онбординг, документы)</label>
                      <textarea v-model="statusDetails.hired.internalNote" rows="2" placeholder="Например: Подготовить рабочее место, выдать доступы, назначить welcome-встречу с руководителем и командой." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                  </div>
                  
                  <!-- Общие поля для комментариев и заметок удалены, т.к. теперь они специфичны для каждого статуса -->
                  
                  <!-- Кнопка сохранения -->
                  <div class="flex justify-end">
                    <button 
                      @click="saveStatusChange" 
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      :disabled="!newStatus || isProcessing"
                    >
                      {{ isProcessing ? 'Сохранение...' : 'Сохранить изменения' }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- История изменений статуса -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">История изменений</h2>
                
                <div v-if="statusHistory.length === 0" class="text-gray-500 italic">
                  История изменений пуста
                </div>
                
                <div v-else class="space-y-4">
                  <div v-for="(historyItem, index) in statusHistory" :key="index" class="border-l-4 pl-4 py-2" 
                    :class="{
                      'border-yellow-400': historyItem.status === 'pending',
                      'border-blue-400': historyItem.status === 'interview',
                      'border-purple-400': historyItem.status === 'reserve',
                      'border-red-400': historyItem.status === 'rejected',
                      'border-green-400': historyItem.status === 'hired'
                    }">
                    <div class="flex justify-between">
                      <span class="font-medium">
                        {{ 
                          historyItem.status === 'pending' ? 'На рассмотрении' : 
                          historyItem.status === 'interview' ? 'Назначено собеседование' : 
                          historyItem.status === 'reserve' ? 'В резерве' : 
                          historyItem.status === 'rejected' ? 'Отклонено' : 
                          'Трудоустроен'
                        }}
                      </span>
                      <span class="text-sm text-gray-500">{{ formatDate(historyItem.date) }}</span>
                    </div>
                    <p v-if="historyItem.comment" class="text-sm mt-1">{{ historyItem.comment }}</p>
                    <p v-if="historyItem.note" class="text-sm mt-1 text-gray-500 italic">Заметка: {{ historyItem.note }}</p>
                    
                    <!-- Детали в зависимости от статуса -->
                    <div v-if="historyItem.status === 'interview' && historyItem.details.date" class="grid grid-cols-2 gap-2">
                      <div>
                        <span class="text-gray-500">Дата:</span> 
                        {{ formatSimpleDate(historyItem.details.date) }}
                          <span class="text-gray-500">Время:</span> 
                          {{ historyItem.details.time }}
                        </div>
                        <div v-if="historyItem.details.location" class="col-span-2">
                          <span class="text-gray-500">Место:</span> 
                          {{ historyItem.details.location }}
                        </div>
                      </div>
                      
                      <!-- Причина отказа -->
                      <div v-if="historyItem.status === 'rejected' && historyItem.details.reason">
                        <span class="text-gray-500">Причина:</span> 
                        {{ historyItem.details.reason }}
                      </div>
                      
                      <!-- Комментарий для резерва -->
                      <div v-if="historyItem.status === 'reserve' && historyItem.details.comment">
                        <span class="text-gray-500">Комментарий:</span> 
                        {{ historyItem.details.comment }}
                      </div>
                      
                      <!-- Информация о трудоустройстве -->
                      <div v-if="historyItem.status === 'hired' && (historyItem.details.startDate || historyItem.details.position)" class="grid grid-cols-2 gap-2">
                        <div v-if="historyItem.details.startDate">
                          <span class="text-gray-500">Дата выхода:</span> 
                          {{ formatSimpleDate(historyItem.details.startDate) }}
                        </div>
                        <div v-if="historyItem.details.position">
                          <span class="text-gray-500">Должность:</span> 
                          {{ historyItem.details.position }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Closing <div v-else class="mt-8 space-y-6"> (from line 37) -->
          </div> <!-- Closing <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> (from line 20) -->
        </main> <!-- Closing <main> -->
    </div> <!-- Closing <div class="py-10"> -->
  </div> <!-- Closing <div class="min-h-screen bg-gray-100"> -->
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import { useRuntimeConfig } from '#app'

const route = useRoute()
const router = useRouter()
const notification = useNotification()
const runtimeConfig = useRuntimeConfig()

// Состояние
const loading = ref(true)
const error = ref(null)
const application = ref({
  id: null,
  user: {},
  job: {},
  status: 'pending',
  created_at: '',
  updated_at: '',
  cover_letter: '',
  feedback: '',
  interview: null
})
const newStatus = ref('')
// const statusComment = ref('') // Удалено
// const newStatusInternalNote = ref(''); // Удалено
const statusHistory = ref([])
const isProcessing = ref(false)

// Объект для хранения деталей, включая специфичные комментарии и заметки по каждому статусу
const statusDetails = ref({
  pending: {
    publicComment: '',
    internalNote: ''
  },
  interview: {
    date: '',
    time: '',
    location: '',
    publicComment: '',
    internalNote: ''
  },
  rejected: {
    sendFeedback: true,
    publicComment: '', // Ранее 'reason', теперь это публичный комментарий
    internalNote: ''
  },
  reserve: {
    publicComment: '', // Ранее 'comment', теперь это публичный комментарий
    internalNote: ''
  },
  hired: {
    startDate: '',
    position: '',
    publicComment: '',
    internalNote: ''
  }
})

// Загрузка данных отклика
async function loadApplication() {
  loading.value = true
  error.value = null
  
  try {
    // Получаем ID отклика из query параметра
    const applicationId = route.query.id
    
    if (!applicationId) {
      error.value = 'Не указан ID отклика'
      loading.value = false
      return
    }
    
    // Для демонстрации используем моковые данные
    // В реальном приложении здесь будет API запрос
    setTimeout(() => {
      // Набор моковых данных для разных ID откликов
      const mockApplications = {
        '1': {
          id: 1,
          user: {
            id: 101,
            fullName: 'Иванов Иван Иванович',
            email: 'ivanov@example.com',
            phone: '+7 (900) 123-45-67',
            city: 'Москва',
            avatar: null
          },
          job: {
            id: 201,
            title: 'Врач-терапевт',
            company: 'Городская клиника №1',
            salary: '80 000 - 120 000 ₽',
            location: 'Москва',
            slug: 'therapist-moscow'
          },
          status: 'pending',
          created_at: '2025-05-10T14:30:00',
          updated_at: '2025-05-10T14:30:00',
          feedback: '',
          cover_letter: 'Уважаемый работодатель! Я врач-терапевт с опытом работы 5 лет. Имею высшую квалификационную категорию. Буду рад стать частью вашей команды.',
          interview: null,
          statusHistory: [
            {
              status: 'pending',
              date: '2023-05-10T09:00:00Z',
              comment: 'Отклик получен, ожидаем рассмотрения.',
              note: 'Первичный скрининг не проводился.',
              details: null
            }
          ]
        },
        '2': {
          id: 2,
          user: {
            id: 102,
            fullName: 'Петрова Анна Сергеевна',
            email: 'petrova@example.com',
            phone: '+7 (900) 234-56-78',
            city: 'Санкт-Петербург',
            avatar: null
          },
          job: {
            id: 202,
            title: 'Медсестра в отделение кардиологии',
            company: 'Медицинский центр "Здоровье"',
            salary: '60 000 - 80 000 ₽',
            location: 'Санкт-Петербург',
            slug: 'nurse-cardiology'
          },
          status: 'interview',
          created_at: '2025-05-05T10:15:00',
          updated_at: '2025-05-05T10:15:00',
          feedback: '',
          cover_letter: 'Имею опыт работы в кардиологическом отделении 3 года. Владею всеми необходимыми навыками для работы с кардиологическими пациентами.',
          interview: {
            date: '2025-05-20',
            time: '15:00',
            location: 'Медицинский центр "Здоровье", кабинет 305',
            notes: 'Пожалуйста, принесите с собой диплом и сертификаты о повышении квалификации.'
          },
          statusHistory: [
            {
              status: 'interview',
              date: '2023-05-12T14:30:00Z',
              comment: 'Кандидат приглашен на собеседование 15.05.2023 в 11:00.',
              note: 'Кажется перспективным, проверить рекомендации.',
              details: { interview_date: '2023-05-15', interview_time: '11:00', interview_location: 'Онлайн (Google Meet)', notes: 'Обсудить опыт с проектом X' }
            }
          ]
        },
        '3': {
          id: 3,
          user: {
            id: 103,
            fullName: 'Смирнов Алексей Петрович',
            email: 'smirnov@example.com',
            phone: '+7 (900) 345-67-89',
            city: 'Екатеринбург',
            avatar: null
          },
          job: {
            id: 203,
            title: 'Врач-хирург',
            company: 'Областная больница',
            salary: '120 000 - 150 000 ₽',
            location: 'Екатеринбург',
            slug: 'surgeon'
          },
          status: 'rejected',
          created_at: '2025-05-03T09:45:00',
          updated_at: '2025-05-03T09:45:00',
          feedback: 'К сожалению, ваш опыт работы не соответствует требованиям вакансии. Мы ищем кандидата с опытом работы не менее 5 лет в хирургии.',
          cover_letter: 'Я хирург с опытом работы 2 года. Окончил медицинский университет с отличием. Владею современными методиками хирургического лечения.',
          statusHistory: [
            {
              status: 'rejected',
              date: '2023-04-20T18:00:00Z',
              comment: 'К сожалению, кандидат не прошел по требованиям вакансии.',
              note: 'Слишком мало опыта с технологией N.',
              details: { reason: 'Отсутствие необходимого опыта в XYZ' }
            }
          ]
        },
        '4': {
          id: 4,
          user: {
            id: 104,
            fullName: 'Козлова Елена Александровна',
            email: 'kozlova@example.com',
            phone: '+7 (900) 456-78-90',
            city: 'Новосибирск',
            avatar: null
          },
          job: {
            id: 204,
            title: 'Врач-педиатр',
            company: 'Детская поликлиника №3',
            salary: '70 000 - 90 000 ₽',
            location: 'Новосибирск',
            slug: 'pediatrician'
          },
          status: 'reserve',
          created_at: '2025-05-01T11:20:00',
          updated_at: '2025-05-01T11:20:00',
          feedback: 'Ваша кандидатура включена в резерв. Мы свяжемся с вами, если появится подходящая вакансия.',
          cover_letter: 'Я педиатр с опытом работы 4 года. Имею опыт работы с детьми разных возрастов. Владею методиками диагностики и лечения детских заболеваний.',
          statusHistory: [
            {
              status: 'reserve',
              date: '2023-08-05T12:00:00Z',
              comment: 'Кандидат добавлен в кадровый резерв.',
              note: 'Связаться через 3 месяца для проверки актуальности.',
              details: { reserve_period: '6 месяцев', notes: 'Хороший специалист, возможно вернемся позже' }
            }
          ]
        },
        '5': {
          id: 5,
          user: {
            id: 105,
            fullName: 'Новиков Дмитрий Игоревич',
            email: 'novikov@example.com',
            phone: '+7 (900) 567-89-01',
            city: 'Казань',
            avatar: null
          },
          job: {
            id: 205,
            title: 'Главный врач',
            company: 'Частная клиника "МедЭксперт"',
            salary: '200 000 - 250 000 ₽',
            location: 'Казань',
            slug: 'chief-physician'
          },
          status: 'hired',
          created_at: '2025-04-15T13:10:00',
          updated_at: '2025-04-15T13:10:00',
          feedback: 'Поздравляем! Вы приняты на должность. Наш HR-менеджер свяжется с вами для обсуждения деталей.',
          cover_letter: 'Я врач с 15-летним опытом работы, из которых 7 лет на руководящих должностях. Имею опыт управления медицинским учреждением и знания в области медицинского менеджмента.',
          statusHistory: [
            {
              status: 'hired',
              date: '2023-03-15T16:00:00Z',
              comment: 'Кандидат принят на работу. Дата выхода: 20.03.2023.',
              note: 'Все формальности улажены.',
              details: { start_date: '2023-03-20', offer_details: 'ЗП 150000, ДМС' }
            }
          ]
        }
      }
      
      // Получаем данные для указанного ID
      const mockData = mockApplications[applicationId]
      
      if (mockData) {
        application.value = mockData
        newStatus.value = mockData.status
        
        if (mockData.interview) {
          statusDetails.value.interview = {
            date: mockData.interview.date || '',
            time: mockData.interview.time || '',
            location: mockData.interview.location || '',
            notes: mockData.interview.notes || ''
          }
        }
        
        // Загружаем историю статусов из моковых данных
        statusHistory.value = mockData.statusHistory.map(item => ({
          ...item,
          comment: item.comment || '',
          note: item.note || '', // Добавляем обработку note
          details: item.details || null
        }))
      } else {
        error.value = 'Отклик не найден'
      }
      
      loading.value = false
    }, 1000)
  } catch (err) {
    console.error('Ошибка при загрузке данных отклика:', err)
    error.value = 'Не удалось загрузить данные отклика. Пожалуйста, попробуйте позже.'
    loading.value = false
  }
}

// Обработка изменения статуса (вызывается при изменении newStatus)
function handleStatusChange(selectedStatus) {
  // 1. Сбросить все поля во всех объектах statusDetails к начальным значениям
  for (const statusKey in statusDetails.value) {
    if (Object.hasOwnProperty.call(statusDetails.value, statusKey)) {
      const statusConfig = statusDetails.value[statusKey];
      statusConfig.publicComment = '';
      statusConfig.internalNote = '';

      if (statusKey === 'interview') {
        statusConfig.date = '';
        statusConfig.time = '';
        statusConfig.location = '';
      } else if (statusKey === 'rejected') {
        statusConfig.sendFeedback = true; // Значение по умолчанию
      } else if (statusKey === 'hired') {
        statusConfig.startDate = '';
        statusConfig.position = '';
      }
      // Для 'pending' и 'reserve' специфичных полей кроме комментариев нет в текущей структуре
    }
  }

  if (!selectedStatus) {
    return; // Если статус не выбран (например, очищен), поля остаются сброшенными
  }

  // 2. Найти последнюю запись в истории для selectedStatus
  const lastEntryForStatus = statusHistory.value
    .slice() // Создаем копию, чтобы не мутировать оригинальный массив
    .reverse()
    .find(entry => entry.status === selectedStatus);

  // 3. Предзаполнить поля, если запись найдена и для статуса есть конфигурация
  if (lastEntryForStatus && statusDetails.value[selectedStatus]) {
    const targetDetails = statusDetails.value[selectedStatus];
    targetDetails.publicComment = lastEntryForStatus.comment || '';
    targetDetails.internalNote = lastEntryForStatus.note || '';

    const historyDetails = lastEntryForStatus.details || {};

    if (selectedStatus === 'interview') {
      targetDetails.date = historyDetails.date || '';
      targetDetails.time = historyDetails.time || '';
      targetDetails.location = historyDetails.location || '';
    } else if (selectedStatus === 'rejected') {
      targetDetails.sendFeedback = typeof historyDetails.sendFeedback === 'boolean' ? historyDetails.sendFeedback : true;
      // Публичный комментарий (бывшая причина) уже взят из lastEntryForStatus.comment
    } else if (selectedStatus === 'hired') {
      targetDetails.startDate = historyDetails.startDate || historyDetails.start_date || ''; // Учитываем возможное старое имя поля
      targetDetails.position = historyDetails.position || '';
    }
    // Для 'pending' и 'reserve' основные данные (комментарии) уже установлены.
    // Если в historyDetails для reserve был специфичный 'comment', он теперь в lastEntryForStatus.comment
  }
}

// Сохранение изменений статуса
async function saveStatusChange() {
  if (!newStatus.value || isProcessing.value) return;

  const currentStatusKey = newStatus.value;
  if (!statusDetails.value[currentStatusKey]) {
    notification.show('Выбран некорректный статус.', 'error');
    return;
  }

  isProcessing.value = true;
  const currentStatusData = statusDetails.value[currentStatusKey];

  try {
    const historyEntry = {
      status: currentStatusKey,
      date: new Date().toISOString(),
      comment: currentStatusData.publicComment || '',
      note: currentStatusData.internalNote || '',
      details: {}
    };

    if (currentStatusKey === 'interview') {
      historyEntry.details = {
        date: currentStatusData.date,
        time: currentStatusData.time,
        location: currentStatusData.location
      };
      application.value.interview = { ...historyEntry.details }; // Обновляем основную информацию о собеседовании в отклике
    } else if (currentStatusKey === 'rejected') {
      historyEntry.details = {
        sendFeedback: currentStatusData.sendFeedback
        // 'reason' (публичный комментарий) уже в historyEntry.comment
      };
    } else if (currentStatusKey === 'hired') {
      historyEntry.details = {
        startDate: currentStatusData.startDate,
        position: currentStatusData.position
      };
    }
    // Для 'pending' и 'reserve' специфичные детали могут быть не нужны, если все в comment/note

    // Имитация API запроса
    setTimeout(() => {
      application.value.status = currentStatusKey;
      application.value.updated_at = new Date().toISOString();
      statusHistory.value.unshift(historyEntry);

      const applicationId = route.query.id;
      localStorage.setItem(`application_status_${applicationId}`, currentStatusKey);
      localStorage.setItem(`application_history_${applicationId}`, JSON.stringify(statusHistory.value));

      newStatus.value = ''; // Это вызовет handleStatusChange('') и очистит все поля
      // statusComment и newStatusInternalNote больше не существуют как отдельные ref

      notification.show('Статус отклика успешно обновлен', 'success');
      isProcessing.value = false;
    }, 1000);
  } catch (err) {
    console.error('Ошибка при обновлении статуса:', err);
    notification.show('Не удалось обновить статус отклика', 'error');
    isProcessing.value = false;
  }
}

// Форматирование даты с временем
const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Форматирование только даты без времени
const formatSimpleDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Возврат к списку откликов
function returnToList() {
  // Сохраняем флаг, чтобы список откликов знал, что нужно обновиться
  localStorage.setItem('applications_need_refresh', 'true')
  
  // Возвращаемся к списку откликов
  router.push('/admin/applications')
}

// Переход к странице просмотра вакансии
function viewJob(jobId) {
  router.push({
    name: 'admin-jobs-id',
    params: { id: jobId },
    query: { 
      from_application: 'true',
      application_id: route.query.id
    }
  })
}

// Загрузка данных при монтировании компонента
onMounted(() => {
  loadApplication()
})

definePageMeta({ middleware: 'admin' })
</script>
