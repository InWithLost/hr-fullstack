<template>
  <div class="min-h-screen bg-gray-100">
    <AdminHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Панель администратора</h1>
        </div>
      </header>
      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Статистика -->
          <div class="mt-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
              <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <UserIcon class="h-6 w-6 text-gray-400" />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Пользователи</dt>
                        <dd class="flex items-baseline">
                          <div class="text-2xl font-semibold text-gray-900">{{ stats.users_count }}</div>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <BriefcaseIcon class="h-6 w-6 text-gray-400" />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Вакансии</dt>
                        <dd class="flex items-baseline">
                          <div class="text-2xl font-semibold text-gray-900">{{ stats.jobs_count }}</div>
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>

              <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <DocumentTextIcon class="h-6 w-6 text-gray-400" />
                    </div>
                    <div class="ml-5 w-0 flex-1">
                      <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Статьи блога</dt>
                        <dd class="flex items-baseline">
                          <div class="text-2xl font-semibold text-gray-900">{{ stats.blog_posts_count }}</div>
                        </dd>
                      </dl>
                    </div>
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
import { UserIcon, BriefcaseIcon, DocumentTextIcon } from '@heroicons/vue/24/outline'

definePageMeta({ middleware: 'admin' })

const stats = ref({
  users_count: 0,
  jobs_count: 0,
  blog_posts_count: 0
})

// Загрузка статистики
const loadStats = async () => {
  try {
    // Для демонстрации используем фиксированные значения
    if (process.env.NODE_ENV === 'development') {
      stats.value = {
        users_count: 5,
        jobs_count: 12,
        blog_posts_count: 8
      }
      return
    }
    
    // Реальный API запрос
    const response = await fetch('http://localhost:8000/api/admin/stats', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      }
    })
    stats.value = await response.json()
  } catch (error) {
    console.error('Ошибка при загрузке статистики:', error)
  }
}

onMounted(() => {
  loadStats()
})
</script>