<!--
  Страница создания новой вакансии в административной панели.
  Отображает форму создания вакансии с использованием компонента JobEditFormAdmin.
  Администратор может указать все параметры вакансии, включая компанию, статус и другие атрибуты.
  После успешного создания перенаправляет на страницу списка вакансий в админке.
-->
<template>
  <div>
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
                <JobEditFormAdmin
                  :job="form"
                  :errors="errors"
                  :isSubmitting="isSubmitting"
                  @save="saveJob"
                  @cancel="goBack"
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import JobEditFormAdmin from '~/components/JobEditFormAdmin.vue'
// AdminHeader импортируется в макете admin.vue

definePageMeta({
  layout: 'admin',
  middleware: 'jobs-admin'
})

const router = useRouter()
const notificationStore = useNotification()

const errors = ref({})
const isSubmitting = ref(false)

const form = ref({
  title: '',
  company: '',
  location: '',
  salary: '',
  type: 'full-time',
  description: '',
  requirements: '',
  is_active: true
})

const saveJob = async (formData) => {
  errors.value = {}
  isSubmitting.value = true

  try {
    // Проверяем данные формы перед отправкой
    if (!formData.title) {
      errors.value.title = 'Название должности обязательно для заполнения'
      isSubmitting.value = false
      return
    }
    
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    console.log('Creating new job with data:', formData)
    
    setTimeout(() => {
      // Показываем уведомление об успешном создании
      notificationStore.success('Вакансия успешно создана')
      
      // Перенаправляем на страницу списка вакансий
      router.push('/admin/jobs')
      
      // Сбрасываем флаг отправки формы
      isSubmitting.value = false
    }, 1000)
    
    /* Раскомментируйте для реального API-запроса
    const response = await fetch('http://localhost:8000/api/admin/jobs', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${process.client ? localStorage.getItem('token') : ''}`
      },
      body: JSON.stringify(formData)
    })

    if (response.ok) {
      notificationStore.success('Вакансия успешно создана')
      router.push('/admin/jobs')
    } else {
      const error = await response.json()
      if (error.errors) {
        errors.value = error.errors
      } else {
        notificationStore.error(error.message || 'Ошибка при создании вакансии')
      }
    }
    */
  } catch (error) {
    console.error('Ошибка при создании вакансии:', error)
    notificationStore.error('Ошибка при создании вакансии')
  } finally {
    isSubmitting.value = false
  }
}

const goBack = () => {
  router.push('/admin/jobs')
}
</script>
