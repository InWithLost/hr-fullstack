<!--
  Страница редактирования вакансии в профиле компании.
  Загружает данные вакансии по ID из параметра маршрута.
  Отображает форму редактирования вакансии с использованием компонента JobEditForm.
  При сохранении обновляет данные вакансии и перенаправляет на страницу списка вакансий.
  В случае ошибки загрузки вакансии показывает уведомление и перенаправляет на список вакансий.
-->
<template>
  <div class="min-h-screen bg-gray-100">
    <CompanyProfileHeader />
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Редактирование вакансии</h1>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <JobEditForm 
                  v-if="job"
                  :job="form" 
                  :companies="companies" 
                  :specialties="specialties" 
                  :company-data="companyData"
                  @save="saveJob" 
                  @cancel="cancelEdit"
                />
                <div v-else-if="loading" class="flex justify-center py-10">
                  <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
                </div>
                <div v-else class="text-center text-red-600 py-10">
                  <p>Ошибка при загрузке вакансии</p>
                  <button 
                    @click="loadJob" 
                    class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                  >
                    Попробовать снова
                  </button>
                </div>
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
import { useRoute, useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import JobEditForm from '~/components/JobEditForm.vue'

definePageMeta({
  middleware: 'auth-check'
})

const route = useRoute()
const router = useRouter()
const notificationStore = useNotification()

const job = ref(null)
const form = ref({})
const loading = ref(true)
const companies = ref([])
const specialties = ref([])

// Данные о балансе компании
const companyData = ref({
  balance: 15000, // Демо-данные для баланса
  free_job_postings: 2 // Демо-данные для бесплатных размещений
})

onMounted(async () => {
  await loadJob()
  await loadReferences()
})

const loadJob = async () => {
  loading.value = true
  job.value = null
  
  try {
    const jobId = route.params.id
    if (!jobId) {
      notificationStore.error('Идентификатор вакансии не указан')
      router.push('/company/profile/jobs')
      return
    }
    
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    setTimeout(() => {
      job.value = {
        id: jobId,
        title: 'Врач-терапевт',
        description: '<p>Требуется врач-терапевт для работы в современной клинике.</p>',
        requirements: '<p>Высшее медицинское образование, опыт работы от 3 лет</p>',
        salary: '80 000 - 120 000 ₽',
        location: 'Москва',
        type: 'full-time',
        is_active: true,
        specialistType: { id: 1, name: 'Терапевт' },
        contactInfo: {
          name: 'Иванов Иван',
          phone: '+7 (999) 123-45-67',
          email: 'hr@medcenter.ru',
          inn: '7712345678'
        },
        has_recruitment_service: false,
        recruitment_service_cost: 0
      }
      
      // Копируем данные в форму
      form.value = { ...job.value }
      loading.value = false
    }, 500)
    
    /* Раскомментируйте для реального API-запроса
    const response = await fetch(`http://localhost:8000/api/jobs/${jobId}`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      job.value = await response.json()
      form.value = { ...job.value }
    } else {
      const error = await response.json()
      notificationStore.error(error.message || 'Ошибка при загрузке вакансии')
      router.push('/company/profile/jobs')
    }
    */
  } catch (error) {
    console.error('Ошибка при загрузке вакансии:', error)
    notificationStore.error('Ошибка при загрузке вакансии')
  }
}

const loadReferences = async () => {
  try {
    // Загрузка списка компаний и специальностей
    // В реальном приложении здесь будет API-запрос
    companies.value = [
      { id: 1, name: 'ООО "Медицинский центр"' },
      { id: 2, name: 'Городская больница №1' }
    ]
    
    specialties.value = [
      { id: 1, name: 'Терапевт' },
      { id: 2, name: 'Хирург' },
      { id: 3, name: 'Педиатр' },
      { id: 4, name: 'Стоматолог' }
    ]
  } catch (error) {
    console.error('Ошибка при загрузке справочников:', error)
    notificationStore.error('Ошибка при загрузке справочников')
  }
}

const saveJob = async (jobData) => {
  try {
    const jobId = route.params.id
    console.log('Сохранение вакансии:', jobData)
    
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    setTimeout(() => {
      notificationStore.success('Вакансия успешно обновлена')
      router.push('/company/profile/jobs')
    }, 1000)
    
    /* Раскомментируйте для реального API-запроса
    const response = await fetch(`http://localhost:8000/api/jobs/${jobId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(jobData)
    })

    if (response.ok) {
      notificationStore.success('Вакансия успешно обновлена')
      router.push('/company/profile/jobs')
    } else {
      const error = await response.json()
      notificationStore.error(error.message || 'Ошибка при обновлении вакансии')
    }
    */
  } catch (error) {
    console.error('Ошибка при обновлении вакансии:', error)
    notificationStore.error('Ошибка при обновлении вакансии')
  }
}

const cancelEdit = () => {
  router.push('/company/profile/jobs')
}
</script>