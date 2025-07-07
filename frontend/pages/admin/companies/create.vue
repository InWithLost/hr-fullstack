<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Создание компании</h1>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <form @submit.prevent="saveCompany">
                  <div class="space-y-6">
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700">Название организации</label>
                      <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.name ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                    </div>

                    <div>
                      <label for="inn" class="block text-sm font-medium text-gray-700">ИНН</label>
                      <input
                        type="text"
                        id="inn"
                        v-model="form.inn"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.inn ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.inn" class="mt-2 text-sm text-red-600">{{ errors.inn }}</p>
                    </div>

                    <div>
                      <label for="contact_name" class="block text-sm font-medium text-gray-700">Контактное лицо</label>
                      <input
                        type="text"
                        id="contact_name"
                        v-model="form.contact_name"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.contact_name ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.contact_name" class="mt-2 text-sm text-red-600">{{ errors.contact_name }}</p>
                    </div>

                    <div>
                      <label for="position" class="block text-sm font-medium text-gray-700">Должность</label>
                      <input
                        type="text"
                        id="position"
                        v-model="form.position"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.position ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.position" class="mt-2 text-sm text-red-600">{{ errors.position }}</p>
                    </div>

                    <div>
                      <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                      <input
                        type="text"
                        id="phone"
                        v-model="form.phone"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.phone ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.phone" class="mt-2 text-sm text-red-600">{{ errors.phone }}</p>
                    </div>

                    <div>
                      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                      <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        :class="[
                          'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
                          errors.email ? 'border-red-300' : ''
                        ]"
                      />
                      <p v-if="errors.email" class="mt-2 text-sm text-red-600">{{ errors.email }}</p>
                    </div>

                    <div>
                      <label for="status" class="block text-sm font-medium text-gray-700">Статус</label>
                      <select
                        id="status"
                        v-model="form.status"
                        :class="[
                          'mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md',
                          errors.status ? 'border-red-300' : ''
                        ]"
                      >
                        <option value="active">Активна</option>
                        <option value="inactive">Неактивна</option>
                        <option value="moderation">На модерации</option>
                      </select>
                      <p v-if="errors.status" class="mt-2 text-sm text-red-600">{{ errors.status }}</p>
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'
import AdminHeader from '~/components/AdminHeader.vue'

definePageMeta({
  layout: 'admin',
  middleware: 'companies-admin'
})

const router = useRouter()
const notificationStore = useNotification()
const errors = ref({})
const isSubmitting = ref(false)

const form = ref({
  name: '',
  inn: '',
  contact_name: '',
  position: '',
  phone: '',
  email: '',
  status: 'active'
})

const saveCompany = async () => {
  errors.value = {}
  isSubmitting.value = true

  try {
    // Проверяем данные формы перед отправкой
    if (!form.value.name) {
      errors.value.name = 'Название организации обязательно для заполнения'
      isSubmitting.value = false
      return
    }
    
    // Для демо-режима используем моковые данные
    // В реальном приложении здесь будет API-запрос
    console.log('Creating new company with data:', form.value)
    
    setTimeout(() => {
      // Показываем уведомление об успешном создании
      notificationStore.success('Компания успешно создана')
      
      // Перенаправляем на страницу списка компаний
      router.push('/admin/companies')
      
      // Сбрасываем флаг отправки формы
      isSubmitting.value = false
    }, 1000)
    
    /* Раскомментируйте для реального API-запроса
    const response = await fetch('http://localhost:8000/api/admin/companies', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${process.client ? localStorage.getItem('token') : ''}`
      },
      body: JSON.stringify(form.value)
    })

    if (response.ok) {
      notificationStore.success('Компания успешно создана')
      router.push('/admin/companies')
    } else {
      const error = await response.json()
      if (error.errors) {
        errors.value = error.errors
      } else {
        notificationStore.error(error.message || 'Ошибка при создании компании')
      }
    }
    */
  } catch (error) {
    console.error('Ошибка при создании компании:', error)
    notificationStore.error('Ошибка при создании компании')
  } finally {
    isSubmitting.value = false
  }
}

const goBack = () => {
  router.push('/admin/companies')
}
</script>
