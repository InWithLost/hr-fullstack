<!--
  Страница создания новой вакансии в профиле компании.
  Отображает форму создания вакансии с использованием компонента JobEditForm.
  При сохранении проверяет баланс компании и наличие бесплатных размещений.
  После успешного создания перенаправляет на страницу списка вакансий.
-->
<template>
  <div class="min-h-screen bg-gray-100">
    <CompanyProfileHeader />
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Создание вакансии</h1>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <JobEditForm 
                  :job="form" 
                  :companies="companies" 
                  :specialties="specialties" 
                  :company-data="companyData"
                  @save="saveJob" 
                  @cancel="cancelEdit"
                />
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
import JobEditForm from '~/components/JobEditForm.vue'

definePageMeta({
  middleware: 'auth-check'
})

const router = useRouter()
const notificationStore = useNotification()

const form = ref({
  title: '',
  description: '',
  requirements: '',
  salary: '',
  location: '',
  type: 'full-time',
  is_active: true,
  specialistType: null,
  contactInfo: {
    name: '',
    phone: '',
    email: '',
    inn: ''
  },
  has_recruitment_service: false,
  recruitment_service_cost: 0
})

const companies = ref([])
const specialties = ref([])

// Данные о балансе компании
const companyData = ref({
  balance: 15000, // Демо-данные для баланса
  free_job_postings: 2 // Демо-данные для бесплатных размещений
})

onMounted(async () => {
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
    console.error('Ошибка при загрузке данных:', error)
    notificationStore.error('Ошибка при загрузке данных')
  }
})

const saveJob = async (jobData) => {
  try {
    console.log('Сохранение вакансии:', jobData)
    
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    setTimeout(() => {
      notificationStore.success('Вакансия успешно создана')
      router.push('/company/profile/jobs')
    }, 1000)
    
    /* Раскомментируйте для реального API-запроса
    const response = await fetch('http://localhost:8000/api/jobs', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(jobData)
    })

    if (response.ok) {
      notificationStore.success('Вакансия успешно создана')
      router.push('/company/profile/jobs')
    } else {
      const error = await response.json()
      notificationStore.error(error.message || 'Ошибка при создании вакансии')
    }
    */
  } catch (error) {
    console.error('Ошибка при создании вакансии:', error)
    notificationStore.error('Ошибка при создании вакансии')
  }
}

const cancelEdit = () => {
  router.push('/company/profile/jobs')
}
</script>