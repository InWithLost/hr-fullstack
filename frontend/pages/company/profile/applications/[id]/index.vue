<template>
  <div class="min-h-screen bg-gray-100">
    <CompanyProfileHeader />
    
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

          <div v-else-if="application" class="mt-8 space-y-6">
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
                        <p class="text-gray-600">{{ application.job.company_name || application.job.company?.name }}</p>
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
                        <div class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden text-white text-xl font-semibold"
                             :style="{ backgroundColor: application.user.avatar ? 'transparent' : getAvatarColor(application.user.id) }"
                        >
                          <img 
                            v-if="application.user.avatar" 
                            :src="application.user.avatar" 
                            :alt="application.user.fullName || application.user.name"
                            class="h-full w-full object-cover"
                          />
                          <span v-else>{{ getInitials(application.user.fullName || application.user.name) }}</span>
                        </div>
                        <div class="ml-4">
                          <h3 class="text-lg font-medium">{{ application.user.fullName || application.user.name }}</h3>
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
                          <p>{{ application.user.location || 'Не указан' }}</p>
                        </div>
                      </div>
                      <!-- <div class="mt-4">
                        <NuxtLink 
                          v-if="application.user.resume_url" 
                          :to="application.user.resume_url" 
                          target="_blank"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          Просмотреть резюме
                        </NuxtLink>
                        <p v-else class="text-sm text-gray-500">Резюме не прикреплено</p>
                      </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Сопроводительное письмо кандидата -->
            <div v-if="application.cover_letter" class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Сопроводительное письмо</h2>
                <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">
                  {{ application.cover_letter }}
                </div>
              </div>
            </div>

            <!-- Детали собеседования (если статус - собеседование) -->
            <div v-if="application.status === 'interview' && (application.interview_details || application.interview_date)" class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Детали собеседования</h2>
                <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                  <div v-if="application.interview_date">
                    <p class="text-sm text-gray-500">Дата и время</p>
                    <p>{{ formatSimpleDate(application.interview_date) }} {{ application.interview_time || '' }}</p>
                  </div>
                  <div v-if="application.interview_format">
                    <p class="text-sm text-gray-500">Формат</p>
                    <p>{{ application.interview_format }}</p>
                  </div>
                  <div v-if="application.interview_link">
                    <p class="text-sm text-gray-500">Ссылка</p>
                    <a :href="application.interview_link" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ application.interview_link }}</a>
                  </div>
                  <div v-if="application.interview_comment">
                    <p class="text-sm text-gray-500">Комментарий</p>
                    <p class="whitespace-pre-wrap">{{ application.interview_comment }}</p>
                  </div>
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
                    :class="getStatusClass(application.status)"
                  >
                    {{ getStatusText(application.status) }}
                  </span>
                  <p class="mt-2 text-sm text-gray-500">
                    Отклик создан: {{ formatDate(application.created_at) }}
                  </p>
                  <p v-if="application.updated_at && application.updated_at !== application.created_at" class="text-sm text-gray-500">
                    Последнее обновление: {{ formatDate(application.updated_at) }}
                  </p>
                </div>
                
                <!-- Форма изменения статуса -->
                <div>
                  <h3 class="text-md font-medium text-gray-900 mb-3">Изменить статус отклика</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div>
                      <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-1">Новый статус</label>
                      <select 
                        id="newStatus"
                        v-model="newStatus"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                      >
                        <option value="" disabled>Выберите статус</option>
                        <option value="pending">На рассмотрении</option>
                        <option value="interview">Назначено собеседование</option>
                        <option value="reserve">В резерве</option>
                        <option value="rejected">Отклонено</option>
                        <option value="hired">Трудоустроен</option>
                      </select>
                    </div>
                    <div>
                      <label for="statusComment" class="block text-sm font-medium text-gray-700 mb-1">Комментарий (для истории)</label>
                      <textarea 
                        id="statusComment"
                        v-model="statusComment"
                        rows="3"
                        placeholder="Комментарий для кандидата (например, детали по собеседованию, причину отказа и т.д.)"
                      ></textarea>
                    </div>
                  </div>

                  <!-- Внутренняя заметка -->
                  <div class="mt-4">
                    <label for="internal-note-company" class="block text-sm font-medium text-gray-700">Заметка (для внутреннего использования)</label>
                    <textarea
                      v-model="newStatusInternalNote"
                      id="internal-note-company"
                      rows="3"
                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      placeholder="Введите внутреннюю заметку, которая не будет видна кандидату"
                    ></textarea>
                  </div>

                  <div class="mt-4 flex justify-end">
                    <button 
                      @click="updateStatusHandler"
                      :disabled="!newStatus || isProcessing"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                    >
                      {{ isProcessing ? 'Сохранение...' : 'Сохранить изменения' }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- История изменений статуса -->
            <div v-if="statusHistory && statusHistory.length > 0" class="bg-white shadow sm:rounded-lg overflow-hidden">
              <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">История изменений статуса</h2>
                <ul class="divide-y divide-gray-200">
                  <li v-for="(entry, index) in statusHistory" :key="index" class="py-4">
                    <div class="flex space-x-3">
                      <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between">
                          <h3 
                            class="text-sm font-medium px-2 py-0.5 rounded-full"
                            :class="getStatusClass(entry.status)"
                          >
                            {{ getStatusText(entry.status) }}
                          </h3>
                          <p class="text-sm text-gray-500">{{ formatDate(entry.date || entry.created_at) }}</p>
                        </div>
                        <p v-if="entry.comment" class="text-sm mt-1 text-gray-600 whitespace-pre-wrap">{{ entry.comment }}</p>
                        <p v-if="entry.note" class="text-sm mt-1 text-gray-500 italic whitespace-pre-wrap">Заметка: {{ entry.note }}</p>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            
          </div>
        </div>
      </main>
    </div>
    <Notification ref="notification" />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import CompanyProfileHeader from '@/components/CompanyProfileHeader.vue' 
import Notification from '@/components/Notification.vue'; 

const route = useRoute()
const router = useRouter()
const notification = ref(null)

const application = ref(null)
const loading = ref(true)
const error = ref(null)
const isProcessing = ref(false)

const newStatus = ref('')
const statusComment = ref('')
const newStatusInternalNote = ref('');

const statusHistory = computed(() => {
  return application.value?.status_history?.sort((a, b) => new Date(b.date || b.created_at) - new Date(a.date || a.created_at)) || []
})

async function loadApplication() {
  loading.value = true
  error.value = null
  try {
    const mockApplications = {
      '1': {
        id: '1',
        job: {
          id: 'job1',
          title: 'Frontend Developer',
          company_name: 'Tech Solutions Inc.',
          salary: '120000 - 150000 RUB',
          location: 'Москва',
        },
        user: {
          id: 'user1',
          name: 'Елена Иванова',
          fullName: 'Елена Петровна Иванова',
          email: 'elena.ivanova@example.com',
          phone: '+7 (916) 123-45-67',
          location: 'Москва',
          avatar: null, 
          resume_url: '#'
        },
        status: 'pending',
        cover_letter: 'Уважаемый менеджер по найму,\n\nС большим интересом ознакомилась с вакансией Frontend Developer в Tech Solutions Inc. Мой опыт в разработке пользовательских интерфейсов и глубокие знания Vue.js и React делают меня сильным кандидатом на эту позицию.\n\nПрилагаю свое резюме для более детального ознакомления. Готова обсудить, как мои навыки могут быть полезны вашей команде.\n\nС уважением,\nЕлена Иванова',
        created_at: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000).toISOString(), 
        updated_at: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString(), 
        status_history: [
          { status: 'pending', date: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000).toISOString(), comment: 'Отклик получен', note: 'Стандартный отклик.' },
        ],
        interview_date: '2025-07-15',
        interview_time: '14:30',
        interview_format: 'Онлайн (Google Meet)',
        interview_link: 'https://meet.google.com/xyz-abc-def',
        interview_comment: 'Собеседование с HR-менеджером Анной Смирновой.'
      },
      '2': {
        id: '2',
        job: {
          id: 'job2',
          title: 'UX Designer',
          company_name: 'Creative Minds LLC',
          salary: '100000 - 130000 RUB',
          location: 'Санкт-Петербург',
        },
        user: {
          id: 'user2',
          name: 'Алексей Смирнов',
          fullName: 'Алексей Викторович Смирнов',
          email: 'alex.smirnov@example.com',
          phone: '+7 (921) 987-65-43',
          location: 'Санкт-Петербург',
          avatar: 'https://i.pravatar.cc/150?u=alex.smirnov@example.com',
        },
        status: 'interview',
        cover_letter: 'Здравствуйте! Меня очень заинтересовала ваша вакансия UX Designer. Уверен, что мой опыт в проектировании пользовательского опыта и создании интуитивно понятных интерфейсов будет ценным для вашей компании.',
        created_at: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000).toISOString(),
        updated_at: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString(),
        status_history: [
          { status: 'pending', date: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000).toISOString(), comment: 'Отклик получен', note: 'Проверить резюме детальнее.' },
          { status: 'interview', date: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString(), comment: 'Назначено собеседование на 10.07 в 11:00', note: 'Подготовить вопросы по UX портфолио.' },
        ],
        interview_date: '2025-07-10',
        interview_time: '11:00',
        interview_format: 'Онлайн (Zoom)',
        interview_link: 'https://zoom.us/j/1234567890',
        interview_comment: 'Техническое собеседование с ведущим дизайнером.'
      }
    }
    const fetchedApplication = mockApplications[route.params.id] || mockApplications['1']; 
    if (fetchedApplication) {
      application.value = {
        ...fetchedApplication,
        status_history: (fetchedApplication.status_history || []).map(item => ({
          ...item,
          comment: item.comment || '',
          note: item.note || '' // Убедимся, что note есть
        }))
      };
      newStatus.value = fetchedApplication.status;
    } else {
      throw new Error('Application not found');
    }

  } catch (e) {
    console.error('Ошибка при загрузке данных отклика:', e)
    error.value = e.message || 'Не удалось загрузить данные отклика.'
    if (notification.value) notification.value.show(error.value, 'error')
  } finally {
    loading.value = false
  }
}

async function updateStatusHandler() {
  if (!newStatus.value || isProcessing.value) return
  
  isProcessing.value = true
  try {
    const now = new Date().toISOString()
    application.value.status = newStatus.value
    application.value.updated_at = now
    
    const historyEntry = {
      status: newStatus.value,
      date: now,
      comment: statusComment.value || null,
      note: newStatusInternalNote.value || null // Добавляем заметку
    }
    if (!application.value.status_history) {
      application.value.status_history = []
    }
    application.value.status_history.push(historyEntry)

    if (newStatus.value === 'interview' && statusComment.value) {
        if (!application.value.interview_details) application.value.interview_details = {};
        const dateMatch = statusComment.value.match(/(\d{2}\.\d{2}(?:\.\d{2,4})?)/);
        const timeMatch = statusComment.value.match(/(\d{2}:\d{2})/);
        if (dateMatch) application.value.interview_date = dateMatch[0];
        if (timeMatch) application.value.interview_time = timeMatch[0];
        application.value.interview_comment = statusComment.value;
    }

    if (notification.value) notification.value.show('Статус отклика успешно обновлен', 'success')
    statusComment.value = ''
    newStatusInternalNote.value = '' // Очищаем заметку
    newStatus.value = application.value.status 

  } catch (e) {
    console.error('Ошибка при обновлении статуса:', e)
    if (notification.value) notification.value.show(e.message || 'Не удалось обновить статус.', 'error')
  } finally {
    isProcessing.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatSimpleDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getInitials = (fullName) => {
  if (!fullName) return ''
  return fullName
    .split(' ')
    .map(name => name.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const avatarColors = ['#FFC107', '#4CAF50', '#2196F3', '#E91E63', '#9C27B0', '#00BCD4', '#FF5722'];
const getAvatarColor = (userId) => {
  if (!userId) return avatarColors[0];
  const num = String(userId).split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
  return avatarColors[num % avatarColors.length];
};

const getStatusText = (status) => {
  const statuses = {
    pending: 'На рассмотрении',
    interview: 'Назначено собеседование',
    reserve: 'В резерве',
    rejected: 'Отклонено',
    hired: 'Трудоустроен',
  }
  return statuses[status] || 'Неизвестный статус'
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    interview: 'bg-blue-100 text-blue-800',
    reserve: 'bg-purple-100 text-purple-800',
    rejected: 'bg-red-100 text-red-800',
    hired: 'bg-green-100 text-green-800',
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

function returnToList() {
  localStorage.setItem('applications_need_refresh_company', 'true') 
  router.push('/company/profile/applications')
}

function viewJob(jobId) {
  if (!jobId) return;
  router.push(`/company/profile/jobs/${jobId}`)
}

onMounted(() => {
  loadApplication()
})

</script>

<style scoped>
.prose {
  max-width: none;
}
</style>
