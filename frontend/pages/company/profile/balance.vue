<!--
  Страница управления балансом компании.
  Отображает текущий баланс компании, количество бесплатных размещений и историю транзакций.
  Позволяет пополнить баланс и сформировать счет на оплату.
  В режиме администратора показывает дополнительную панель с возможностью
  вернуться в админ-панель.
-->
<template>
  <div class="min-h-screen bg-gray-100">
    <CompanyProfileHeader />
    
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <!-- Индикатор режима администратора -->
          <div v-if="isAdminMode" class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-yellow-700">
                  Вы управляете этой компанией в режиме администратора
                </p>
              </div>
              <div class="ml-auto">
                <button 
                  @click="returnToAdmin" 
                  class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                >
                  Вернуться в админ-панель
                </button>
              </div>
            </div>
          </div>
          
          <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Баланс компании</h1>
            <button
              @click="showTopUpModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              Пополнить баланс
            </button>
          </div>
        </div>
      </header>

      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Навигация перемещена в компонент CompanyProfileHeader -->

          <!-- Блоки с информацией -->
          <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 truncate">Текущий баланс</dt>
                          <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ formatCurrency(company.balance) }}</div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 truncate">Бесплатные размещения</dt>
                          <dd class="flex items-center">
                            <div class="text-2xl font-semibold text-gray-900">{{ company.free_job_postings }}</div>
                            <!-- Кнопка добавления бесплатных размещений (только для администратора) -->
                            <button 
                              v-if="isAdminMode" 
                              @click="showAddFreePostingsModal = true"
                              class="ml-3 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                              <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                              </svg>
                              Добавить
                            </button>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                  <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                      </div>
                      <div class="ml-5 w-0 flex-1">
                        <dl>
                          <dt class="text-sm font-medium text-gray-500 truncate">Стоимость размещения</dt>
                          <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ formatCurrency(jobPostingCost) }}</div>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>
          </div>

          <!-- История транзакций -->
          <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900">История транзакций</h3>
              <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Баланс после</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Документ</th>
                      <th v-if="isAdminMode" scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Действия</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="transaction in paginatedTransactions" :key="transaction.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDate(transaction.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ transaction.description }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          :class="[
                            transaction.type === 'deposit'
                              ? 'bg-green-100 text-green-800'
                              : transaction.type === 'withdrawal'
                                ? 'bg-red-100 text-red-800'
                                : 'bg-blue-100 text-blue-800',
                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full'
                          ]"
                        >
                          {{ 
                            transaction.type === 'deposit' 
                              ? 'Пополнение' 
                              : transaction.type === 'withdrawal' 
                                ? 'Списание' 
                                : 'Действие админа' 
                          }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          :class="[
                            transaction.status === 'confirmed'
                              ? 'bg-green-100 text-green-800'
                              : transaction.status === 'pending'
                                ? 'bg-yellow-100 text-yellow-800'
                                : 'bg-red-100 text-red-800',
                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full'
                          ]"
                        >
                          {{ 
                            transaction.status === 'confirmed' 
                              ? 'Подтверждено' 
                              : transaction.status === 'pending' 
                                ? 'Ожидает' 
                                : 'Отменено' 
                          }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm" :class="transaction.type === 'deposit' ? 'text-green-600' : 'text-red-600'">
                        {{ transaction.type === 'deposit' ? '+' : '-' }} {{ formatCurrency(Math.abs(transaction.amount)) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatCurrency(transaction.balance_after) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <a 
                          v-if="transaction.invoice_number && transaction.invoice_url" 
                          :href="transaction.invoice_url" 
                          target="_blank"
                          class="text-indigo-600 hover:text-indigo-900 underline"
                        >
                          {{ transaction.invoice_number }}
                        </a>
                        <span v-else>-</span>
                      </td>
                      <td v-if="isAdminMode" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="relative action-menu-container">
                          <!-- Иконка троеточия -->
                          <button 
                            @click="toggleActionMenu(transaction.id)" 
                            class="p-1 rounded-full hover:bg-gray-200 transition-colors"
                            title="Действия"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                          </button>
                          
                          <!-- Выпадающее меню -->
                          <div 
                            v-if="activeActionMenu === transaction.id" 
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border"
                            :class="{
                              'bottom-full mb-2': isLastRows(transaction.id)
                            }"
                          >
                            <div class="py-1">
                              <!-- Изменить статус на "Подтверждено" -->
                              <button 
                                v-if="transaction.status !== 'confirmed'"
                                @click="changeTransactionStatus(transaction, 'confirmed')" 
                                class="w-full text-left block px-4 py-2 text-sm text-green-700 hover:bg-gray-100" 
                              >
                                Статус: "Подтверждено"
                              </button>
                              
                              <!-- Изменить статус на "Ожидает" -->
                              <button 
                                v-if="transaction.status !== 'pending'"
                                @click="changeTransactionStatus(transaction, 'pending')" 
                                class="w-full text-left block px-4 py-2 text-sm text-yellow-700 hover:bg-gray-100" 
                              >
                                Статус: "Ожидает"
                              </button>
                              
                              <!-- Изменить статус на "Отменено" -->
                              <button 
                                v-if="transaction.status !== 'cancelled'"
                                @click="changeTransactionStatus(transaction, 'cancelled')" 
                                class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-gray-100" 
                              >
                                Статус: "Отменено"
                              </button>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr v-if="transactions.length === 0">
                      <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        История транзакций пуста
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- Пагинация -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
              <button 
                @click="currentPage--" 
                :disabled="currentPage === 1" 
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
              >
                Назад
              </button>
              <button 
                @click="currentPage++" 
                :disabled="currentPage === totalPages" 
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
              >
                Вперед
              </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Показано <span class="font-medium">{{ (currentPage - 1) * pageSize + 1 }}</span> - <span class="font-medium">{{ Math.min(currentPage * pageSize, totalTransactions) }}</span> из <span class="font-medium">{{ totalTransactions }}</span> транзакций
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button 
                    @click="currentPage--" 
                    :disabled="currentPage === 1" 
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                  >
                    <span class="sr-only">Предыдущая</span>
                    &lt;
                  </button>
                  <button 
                    v-for="page in paginationPages" 
                    :key="page" 
                    @click="currentPage = page" 
                    :class="[
                      page === currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                    ]"
                  >
                    {{ page }}
                  </button>
                  <button 
                    @click="currentPage++" 
                    :disabled="currentPage === totalPages" 
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                  >
                    <span class="sr-only">Следующая</span>
                    &gt;
                  </button>
                </nav>
              </div>
            </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Модальное окно пополнения баланса -->
    <div v-if="showTopUpModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75" @click="showTopUpModal = false"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Пополнение баланса
                </h3>
                <div class="mt-4">
                  <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Сумма пополнения</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">₽</span>
                      </div>
                      <input
                        type="number"
                        name="amount"
                        id="amount"
                        v-model="topUpAmount"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                        placeholder="0.00"
                        min="1000"
                        step="1000"
                      />
                      <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">RUB</span>
                      </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Минимальная сумма пополнения: 1 000 ₽</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="generateInvoice"
              :disabled="topUpAmount < 1000"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
            >
              Сформировать счет
            </button>
            <button
              type="button"
              @click="showTopUpModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Отмена
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Модальное окно добавления бесплатных размещений -->
  <div v-if="showAddFreePostingsModal" class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <form @submit.prevent="addFreePostings">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Добавление бесплатных размещений</h3>
              <p class="text-sm text-gray-500 mb-4">Укажите количество бесплатных размещений, которое вы хотите добавить компании.</p>
              
              <div class="mb-4">
                <label for="freePostingsAmount" class="block text-sm font-medium text-gray-700">Количество размещений</label>
                <input
                  type="number"
                  id="freePostingsAmount"
                  v-model.number="freePostingsAmount"
                  min="1"
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  required
                />
              </div>
            </div>
          </div>

          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="submit"
              :disabled="freePostingsAmount < 1"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
            >
              Добавить
            </button>
            <button
              type="button"
              @click="showAddFreePostingsModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Отмена
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/stores/notification'

const router = useRouter()
const notification = useNotification()

// Проверка режима администратора
const isAdminMode = ref(false)

// Функция возврата в админ-панель
const returnToAdmin = () => {
  router.push('/admin/companies')
}

// Данные компании
const company = ref({
  id: 1,
  name: 'ООО "Медтехника"',
  balance: 15000,
  free_job_postings: 2
})

// Стоимость размещения вакансии
const jobPostingCost = ref(3000)

// Пагинация
const currentPage = ref(1)
const pageSize = 5
const totalTransactions = computed(() => transactions.value.length)

// Активное меню действий
const activeActionMenu = ref(null)

// История транзакций
const transactions = ref([
  {
    id: 1,
    created_at: '2025-05-01T10:30:00',
    description: 'Пополнение баланса',
    type: 'deposit',
    amount: 20000,
    balance_before: 0,
    balance_after: 20000,
    invoice_number: '2025-05-001',
    invoice_url: '#'
  },
  {
    id: 2,
    created_at: '2025-05-05T14:15:00',
    description: 'Размещение вакансии "Терапевт"',
    type: 'withdrawal',
    amount: 3000,
    balance_before: 20000,
    balance_after: 17000,
    invoice_number: null,
    invoice_url: null
  },
  {
    id: 3,
    created_at: '2025-05-10T09:45:00',
    description: 'Услуга подбора кандидатов для вакансии "Хирург"',
    type: 'withdrawal',
    amount: 2000,
    balance_before: 17000,
    balance_after: 15000,
    invoice_number: null,
    invoice_url: null
  }
])

// Модальное окно пополнения баланса
const showTopUpModal = ref(false)
const topUpAmount = ref(1000)

// Модальное окно добавления бесплатных размещений
const showAddFreePostingsModal = ref(false)
const freePostingsAmount = ref(1)

// Форматирование даты
const formatDate = (dateString) => {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

// Форматирование валюты
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    minimumFractionDigits: 0
  }).format(amount)
}

// Генерация счета на пополнение баланса
const generateInvoice = async () => {
  try {
    // В реальном приложении здесь был бы API-запрос для генерации счета
    // const response = await fetch('http://localhost:8000/api/company/generate-invoice', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify({
    //     amount: topUpAmount.value
    //   })
    // })
    // const data = await response.json()
    
    // Для демо-режима просто показываем сообщение об успехе
    notification.show(`Счет на сумму ${formatCurrency(topUpAmount.value)} успешно сформирован и отправлен на вашу электронную почту.`, 'success')
    
    // Добавляем транзакцию в историю со статусом "Ожидает подтверждения"
    const newInvoiceNumber = `2025-${(new Date().getMonth() + 1).toString().padStart(2, '0')}-${(transactions.value.length + 1).toString().padStart(3, '0')}`
    transactions.value.unshift({
      id: transactions.value.length + 1,
      created_at: new Date().toISOString(),
      description: 'Пополнение баланса',
      type: 'deposit',
      status: 'pending', // Статус "Ожидает подтверждения"
      amount: topUpAmount.value,
      balance_before: company.value.balance,
      balance_after: company.value.balance, // Баланс не изменяется до подтверждения
      invoice_number: newInvoiceNumber,
      invoice_url: '#'
    })
    
    // Закрываем модальное окно
    showTopUpModal.value = false
    topUpAmount.value = 1000
  } catch (error) {
    console.error('Ошибка при генерации счета:', error)
    notification.show('Произошла ошибка при формировании счета. Пожалуйста, попробуйте еще раз.', 'error')
  }
}

// Добавление бесплатных размещений (только для администратора)
const addFreePostings = async () => {
  try {
    if (!isAdminMode.value) {
      notification.show('Только администратор может добавлять бесплатные размещения', 'error')
      return
    }

    // В реальном приложении здесь был бы API-запрос для добавления бесплатных размещений
    // const response = await fetch(`http://localhost:8000/api/admin/companies/${company.value.id}/add-free-postings`, {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify({
    //     amount: freePostingsAmount.value
    //   })
    // })
    // const data = await response.json()
    
    // Для демо-режима просто показываем сообщение об успехе
    const oldFreePostings = company.value.free_job_postings
    company.value.free_job_postings += freePostingsAmount.value
    
    // Добавляем транзакцию в историю (в демо-режиме)
    transactions.value.unshift({
      id: transactions.value.length + 1,
      created_at: new Date().toISOString(),
      description: `Добавлено ${freePostingsAmount.value} бесплатных размещений (администратором)`,
      type: 'admin_action',
      status: 'confirmed', // Статус "Подтвержден"
      amount: 0,
      balance_before: company.value.balance,
      balance_after: company.value.balance,
      invoice_number: null,
      invoice_url: null,
      free_postings_before: oldFreePostings,
      free_postings_after: company.value.free_job_postings
    })
    
    notification.show(`Успешно добавлено ${freePostingsAmount.value} бесплатных размещений`, 'success')
    
    // Закрываем модальное окно
    showAddFreePostingsModal.value = false
    freePostingsAmount.value = 1
  } catch (error) {
    console.error('Ошибка при добавлении бесплатных размещений:', error)
    notification.show('Произошла ошибка при добавлении бесплатных размещений', 'error')
  }
}

// Изменение статуса транзакции (только для администратора)
const changeTransactionStatus = async (transaction, newStatus) => {
  try {
    if (!isAdminMode.value) {
      notification.show('Только администратор может изменять статус транзакций', 'error')
      return
    }

    if (transaction.status === newStatus) {
      notification.show(`Транзакция уже имеет статус "${newStatus}"`, 'warning')
      return
    }

    // В реальном приложении здесь был бы API-запрос для изменения статуса транзакции
    // const response = await fetch(`http://localhost:8000/api/admin/transactions/${transaction.id}/status`, {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify({
    //     status: newStatus
    //   })
    // })
    // const data = await response.json()
    
    const oldStatus = transaction.status
    const oldBalance = company.value.balance
    
    // Обработка изменения баланса в зависимости от типа транзакции и изменения статуса
    if (transaction.type === 'deposit') {
      // Если это пополнение баланса
      if (oldStatus === 'confirmed' && newStatus !== 'confirmed') {
        // Если было подтверждено, а стало не подтверждено - вычитаем из баланса
        company.value.balance = transaction.balance_before
        notification.show(`Статус изменен на "${getStatusName(newStatus)}". Баланс уменьшен на ${formatCurrency(transaction.amount)}`, 'success')
      } else if (oldStatus !== 'confirmed' && newStatus === 'confirmed') {
        // Если было не подтверждено, а стало подтверждено - прибавляем к балансу
        company.value.balance += transaction.amount
        notification.show(`Статус изменен на "Подтверждено". Баланс увеличен на ${formatCurrency(transaction.amount)}`, 'success')
      } else {
        notification.show(`Статус изменен на "${getStatusName(newStatus)}"`, 'success')
      }
    } else if (transaction.type === 'withdrawal') {
      // Если это списание с баланса
      // Здесь можно добавить логику для списаний, если необходимо
      notification.show(`Статус изменен на "${getStatusName(newStatus)}"`, 'success')
    } else {
      notification.show(`Статус изменен на "${getStatusName(newStatus)}"`, 'success')
    }
    
    // Обновляем статус транзакции
    transaction.status = newStatus
    
    // Обновляем значение balance_after в зависимости от статуса
    if (newStatus === 'confirmed') {
      transaction.balance_after = company.value.balance
    } else {
      // Если статус не подтвержден, то balance_after равен balance_before
      transaction.balance_after = transaction.balance_before
    }
    
  } catch (error) {
    console.error('Ошибка при изменении статуса транзакции:', error)
    notification.show('Произошла ошибка при изменении статуса транзакции', 'error')
  }
}

// Функция для получения человекочитаемого названия статуса
const getStatusName = (status) => {
  switch (status) {
    case 'pending':
      return 'Ожидает'
    case 'confirmed':
      return 'Подтверждено'
    case 'cancelled':
      return 'Отменено'
    default:
      return status
  }
}

// Загрузка данных компании
const loadCompanyData = async () => {
  try {
    // В реальном приложении здесь был бы API-запрос
    // const response = await fetch('http://localhost:8000/api/company/profile', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // const data = await response.json()
    // company.value = data
    
    // Для демо-режима используем заранее определенные данные
  } catch (error) {
    console.error('Ошибка при загрузке данных компании:', error)
  }
}

// Загрузка истории транзакций
const loadTransactions = async () => {
  try {
    // В реальном приложении здесь был бы API-запрос
    // const response = await fetch('http://localhost:8000/api/company/transactions', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // const data = await response.json()
    // transactions.value = data
    
    // Для демо-режима используем заранее определенные данные
    
    // Добавляем свойство showMenu для каждой транзакции
    transactions.value.forEach(transaction => {
      transaction.showMenu = false;
      // Если статус не указан, устанавливаем по умолчанию
      if (!transaction.status) {
        transaction.status = transaction.type === 'admin_action' ? 'confirmed' : 'pending';
      }
    });
    
    // Обновляем общее количество транзакций для пагинации
    totalTransactions.value = transactions.value.length;
  } catch (error) {
    console.error('Ошибка при загрузке истории транзакций:', error)
  }
}

// Загрузка стоимости размещения вакансии
const loadJobPostingCost = async () => {
  try {
    // В реальном приложении здесь был бы API-запрос
    // const response = await fetch('http://localhost:8000/api/settings/job-posting-cost', {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // const data = await response.json()
    // jobPostingCost.value = data.cost
    
    // Для демо-режима используем заранее определенное значение
  } catch (error) {
    console.error('Ошибка при загрузке стоимости размещения вакансии:', error)
  }
}

// Проверка режима администратора
const checkAdminMode = () => {
  try {
    // Проверяем, есть ли данные о компании администратора в localStorage
    const adminCompanyStr = localStorage.getItem('admin_managing_company')
    if (adminCompanyStr) {
      try {
        // Парсим данные о компании, которой управляет администратор
        const adminCompanyData = JSON.parse(adminCompanyStr)
        isAdminMode.value = adminCompanyData.admin_mode === true
        
        // Если мы в режиме администратора, загружаем данные компании по ID
        if (isAdminMode.value) {
          console.log(`Загрузка данных компании в режиме администратора, ID: ${adminCompanyData.id}`)
          
          // В реальном приложении здесь был бы API-запрос для получения данных компании по ID
          // Для демо-режима просто используем имя из данных администратора
          company.value.name = adminCompanyData.name
        }
      } catch (error) {
        console.error('Ошибка при парсинге данных компании администратора:', error)
      }
    }
  } catch (error) {
    console.error('Ошибка при проверке режима администратора:', error)
  }
}

// Функция для переключения меню действий
const toggleActionMenu = (transactionId) => {
  if (activeActionMenu.value === transactionId) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = transactionId
  }
}

// Функция для определения, является ли транзакция одной из последних в списке
const isLastRows = (transactionId) => {
  const index = paginatedTransactions.value.findIndex(t => t.id === transactionId)
  return index >= paginatedTransactions.value.length - 2
}

// Обработчик клика вне меню
const handleClickOutside = (event) => {
  const menus = document.querySelectorAll('.action-menu-container')
  let clickedInsideMenu = false
  
  menus.forEach(menu => {
    if (menu.contains(event.target)) {
      clickedInsideMenu = true
    }
  })
  
  if (!clickedInsideMenu) {
    activeActionMenu.value = null
  }
};

// Вычисляемое свойство для отображения транзакций с учетом пагинации
const paginatedTransactions = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize
  const endIndex = startIndex + pageSize
  return transactions.value.slice(startIndex, endIndex)
})

// Вычисляемое свойство для общего количества страниц
const totalPages = computed(() => {
  return Math.ceil(totalTransactions.value / pageSize)
})

// Вычисляемое свойство для отображения кнопок пагинации
const paginationPages = computed(() => {
  const maxVisiblePages = 5
  let pages = []
  
  if (totalPages.value <= maxVisiblePages) {
    // Если общее количество страниц меньше или равно максимальному количеству видимых страниц,
    // показываем все страницы
    for (let i = 1; i <= totalPages.value; i++) {
      pages.push(i)
    }
  } else {
    // Иначе показываем страницы вокруг текущей страницы
    let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2))
    let endPage = startPage + maxVisiblePages - 1
    
    if (endPage > totalPages.value) {
      endPage = totalPages.value
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
  }
  
  return pages
})

// Инициализация
onMounted(async () => {
  checkAdminMode()
  await loadCompanyData()
  await loadTransactions()
  await loadJobPostingCost()
  
  // Добавляем обработчик клика для закрытия меню
  document.addEventListener('click', handleClickOutside);
})

onUnmounted(() => {
  // Удаляем обработчик клика при уничтожении компонента
  document.removeEventListener('click', handleClickOutside);
})
</script>
