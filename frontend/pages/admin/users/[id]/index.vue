<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
              <h1 class="text-3xl font-bold leading-tight text-gray-900">Профиль пользователя</h1>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4" v-if="user">
              <NuxtLink
                :to="`/admin/users/${user.id}/edit`"
                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Редактировать
              </NuxtLink>
            </div>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg" v-if="user">
              <!-- Основная информация -->
              <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-8">
                  <div class="flex-shrink-0 h-24 w-24">
                    <img
                      class="h-24 w-24 rounded-full"
                      :src="user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&size=96'"
                      :alt="user.name"
                    />
                  </div>
                  <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ user.name }}</h2>
                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                    <div class="mt-2">
                      <span
                        :class="[
                          user.is_admin
                            ? 'bg-green-100 text-green-800'
                            : 'bg-gray-100 text-gray-800',
                          'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                        ]"
                      >
                        {{ user.is_admin ? 'Администратор' : 'Пользователь' }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Статистика -->
                <div class="border-t border-gray-200 pt-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Статистика активности</h3>
                  <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                      <dt class="text-sm font-medium text-gray-500 truncate">Отклики на вакансии</dt>
                      <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ user.job_applications_count || 0 }}</dd>
                    </div>

                    <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                      <dt class="text-sm font-medium text-gray-500 truncate">Созданные вакансии</dt>
                      <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ user.jobs_created_count || 0 }}</dd>
                    </div>

                    <div class="px-4 py-5 bg-gray-50 shadow rounded-lg overflow-hidden sm:p-6">
                      <dt class="text-sm font-medium text-gray-500 truncate">Комментарии</dt>
                      <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ user.comments_count || 0 }}</dd>
                    </div>
                  </dl>
                </div>

                <!-- Дополнительная информация -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Дополнительная информация</h3>
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Телефон</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ user.phone || 'Не указан' }}</dd>
                    </div>

                    <div>
                      <dt class="text-sm font-medium text-gray-500">Специализация</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ user.specialization || 'Не указана' }}</dd>
                    </div>

                    <div>
                      <dt class="text-sm font-medium text-gray-500">Дата регистрации</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.created_at) }}</dd>
                    </div>

                    <div>
                      <dt class="text-sm font-medium text-gray-500">Последнее обновление</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ formatDate(user.updated_at) }}</dd>
                    </div>
                  </dl>
                </div>

                <!-- Действия -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                  <div class="flex justify-end space-x-3">
                    <button
                      @click="toggleAdminStatus"
                      class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      :disabled="isProcessing"
                    >
                      {{ user.is_admin ? 'Убрать права админа' : 'Сделать админом' }}
                    </button>
                    <button
                      @click="deleteUser"
                      class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                      :disabled="isProcessing"
                    >
                      Удалить пользователя
                    </button>
                  </div>
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

const route = useRoute()
const router = useRouter()
const notificationStore = useNotification()

const user = ref(null)
const isProcessing = ref(false)

// Загрузка данных пользователя
const loadUser = async () => {
  try {
    const response = await fetch(`http://localhost:8000/api/admin/users/${route.params.id}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      user.value = await response.json()
    } else {
      notificationStore.error('Ошибка при загрузке данных пользователя')
      router.push('/admin/users')
    }
  } catch (error) {
    console.error('Ошибка при загрузке данных пользователя:', error)
    notificationStore.error('Ошибка при загрузке данных пользователя')
    router.push('/admin/users')
  }
}

// Изменение статуса администратора
const toggleAdminStatus = async () => {
  if (isProcessing.value || !user.value) return

  isProcessing.value = true
  try {
    const response = await fetch(`http://localhost:8000/api/admin/users/${user.value.id}/toggle-admin`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      const updatedUser = await response.json()
      user.value = updatedUser
      notificationStore.success(`Права пользователя ${user.value.name} успешно обновлены`)
    } else {
      notificationStore.error('Ошибка при обновлении прав пользователя')
    }
  } catch (error) {
    console.error('Ошибка при обновлении прав пользователя:', error)
    notificationStore.error('Ошибка при обновлении прав пользователя')
  } finally {
    isProcessing.value = false
  }
}

// Удаление пользователя
const deleteUser = async () => {
  if (!user.value) return
  if (!confirm(`Вы уверены, что хотите удалить пользователя ${user.value.name}?`)) return
  if (isProcessing.value) return

  isProcessing.value = true
  try {
    const response = await fetch(`http://localhost:8000/api/admin/users/${user.value.id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })

    if (response.ok) {
      notificationStore.success(`Пользователь ${user.value.name} успешно удален`)
      router.push('/admin/users')
    } else {
      notificationStore.error('Ошибка при удалении пользователя')
    }
  } catch (error) {
    console.error('Ошибка при удалении пользователя:', error)
    notificationStore.error('Ошибка при удалении пользователя')
  } finally {
    isProcessing.value = false
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadUser()
})
</script> 