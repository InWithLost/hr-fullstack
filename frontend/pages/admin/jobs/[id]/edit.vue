<!--
  Страница редактирования вакансии в административной панели.
  Загружает данные вакансии по ID из параметра маршрута.
  Отображает форму редактирования вакансии с использованием компонента JobEditFormAdmin.
  Администратор может изменить все параметры вакансии, включая статус и принадлежность к компании.
  После успешного сохранения перенаправляет на страницу списка вакансий в админке.
-->
<template>
  <div>
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
                <JobEditFormAdmin
                  v-if="job"
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
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import JobEditFormAdmin from '~/components/JobEditFormAdmin.vue'
// AdminHeader импортируется в макете admin.vue

definePageMeta({
  layout: 'admin',
  middleware: 'jobs-admin'
})

const route = useRoute()
const router = useRouter()
const notification = useNotification()

const job = ref(null)
const form = ref({})
const errors = ref({})
const isSubmitting = ref(false)

onMounted(async () => {
  const jobId = route.params.id
  if (!jobId) {
    notification.error('ID вакансии не указан')
    router.push('/admin/jobs')
    return
  }

  try {
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    setTimeout(() => {
      // Имитация получения данных вакансии
      job.value = {
        id: jobId,
        title: 'Разработчик JavaScript',
        company: 'WindSurf',
        location: 'Москва',
        salary: 'от 150 000 руб.',
        type: 'full-time',
        description: '<p>Требуется опытный разработчик JavaScript для работы над веб-приложениями.</p>',
        requirements: '<p>Опыт работы от 3 лет, знание React/Vue, опыт работы с REST API</p>',
        is_active: true,
        created_at: '2023-03-05T09:15:00.000Z',
        updated_at: '2023-03-20T11:30:00.000Z'
      }
      
      // Копируем данные в форму
      form.value = { ...job.value }
    }, 500)
    
    /* Раскомментируйте для реального API-запроса
    const response = await fetch(`http://localhost:8000/api/admin/jobs/${jobId}`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${process.client ? localStorage.getItem('token') : ''}`
      }
    })

    if (response.ok) {
      job.value = await response.json()
      form.value = { ...job.value }
    } else {
      const error = await response.json()
      notification.error(error.message || 'Ошибка при загрузке вакансии')
      router.push('/admin/jobs')
    }
    */
  } catch (error) {
    console.error('Ошибка при загрузке вакансии:', error)
    notification.error('Ошибка при загрузке вакансии')
    router.push('/admin/jobs')
  }
})

const saveJob = async (formData) => {
  errors.value = {}
  isSubmitting.value = true

  try {
    // Получаем ID из параметров маршрута
    const jobId = route.params.id
    console.log('Saving job with ID:', jobId)
    
    // Проверяем данные формы перед отправкой
    if (!formData.title) {
      errors.value.title = 'Название должности обязательно для заполнения'
      isSubmitting.value = false
      return
    }
    
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    setTimeout(() => {
      // Выводим данные формы для отладки
      console.log('Form data to save:', formData)
      
      // Показываем уведомление об успешном сохранении
      notification.success('Вакансия успешно обновлена')
      
      // Перенаправляем на страницу списка вакансий
      router.push('/admin/jobs')
      
      // Сбрасываем флаг отправки формы
      isSubmitting.value = false
    }, 1000)
    
    /* Раскомментируйте для реального API-запроса
    const response = await fetch(`http://localhost:8000/api/admin/jobs/${jobId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${process.client ? localStorage.getItem('token') : ''}`
      },
      body: JSON.stringify(formData)
    })

    if (response.ok) {
      notification.success('Вакансия успешно обновлена')
      router.push('/admin/jobs')
    } else {
      const error = await response.json()
      if (error.errors) {
        errors.value = error.errors
      } else {
        notification.error(error.message || 'Ошибка при обновлении вакансии')
      }
    }
    */
  } catch (error) {
    console.error('Ошибка при обновлении вакансии:', error)
    notification.error('Ошибка при обновлении вакансии')
  } finally {
    isSubmitting.value = false
  }
}

const goBack = () => {
  router.push('/admin/jobs')
}
</script>