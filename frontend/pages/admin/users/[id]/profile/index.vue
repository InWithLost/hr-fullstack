<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Профиль пользователя</h1>
            <NuxtLink
              v-if="fromApplication"
              :to="`/admin/view-application?id=${applicationId}`"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Назад к отклику
            </NuxtLink>
            <NuxtLink
              v-else
              to="/admin/users"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Вернуться к списку
            </NuxtLink>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mt-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <!-- Вкладки профиля -->
                <div class="border-b border-gray-200 mb-6">
                  <nav class="-mb-px flex space-x-8">
                    <button
                      v-for="tab in tabs"
                      :key="tab.id"
                      @click="activeTab = tab.id"
                      class="py-4 px-1 border-b-2 font-medium text-sm"
                      :class="[
                        activeTab === tab.id
                          ? 'border-indigo-500 text-indigo-600'
                          : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                      ]"
                    >
                      {{ tab.name }}
                    </button>
                  </nav>
                </div>

                <!-- Содержимое вкладок -->
                <div v-if="activeTab === 'profile'" class="space-y-6">
                  <div class="bg-white rounded-lg">
                    <AdminUserProfile :userId="$route.params.id" />
                  </div>
                </div>

                <div v-if="activeTab === 'education'" class="space-y-6">
                  <div class="bg-white rounded-lg">
                    <AdminUserEducation :userId="$route.params.id" />
                  </div>
                </div>

                <div v-if="activeTab === 'anketa'" class="space-y-6">
                  <div class="bg-white rounded-lg">
                    <AdminUserAnketa :userId="$route.params.id" />
                  </div>
                </div>

                <div v-if="activeTab === 'applications'" class="space-y-6">
                  <div class="bg-white rounded-lg">
                    <AdminUserApplications :userId="$route.params.id" />
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
const notification = useNotification()

// Проверяем, пришли ли мы со страницы отклика
const fromApplication = ref(false)
const applicationId = ref(null)

function checkFromApplication() {
  fromApplication.value = route.query.from_application === 'true'
  if (fromApplication.value && route.query.application_id) {
    applicationId.value = route.query.application_id
  }
}

// Вкладки профиля
const tabs = [
  { id: 'profile', name: 'Основная информация' },
  { id: 'education', name: 'Образование' },
  { id: 'anketa', name: 'Стаж' },
  { id: 'applications', name: 'Отклики' }
]

const activeTab = ref('profile')

// Вызываем функцию при монтировании компонента
onMounted(() => {
  checkFromApplication()
})

definePageMeta({ middleware: 'admin' })
</script>
