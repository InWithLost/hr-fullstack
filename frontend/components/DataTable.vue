<template>
  <div>
    <!-- Таблица -->
    <div v-if="items.length > 0" class="bg-white shadow rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th 
                v-for="column in columns" 
                :key="column.key" 
                :class="[
                  column.align === 'right' ? 'text-right' : 'text-left',
                  'px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider'
                ]"
              >
                {{ column.label }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(item, index) in items" :key="getItemKey(item, index)" class="hover:bg-gray-50">
              <td 
                v-for="column in columns" 
                :key="column.key"
                :class="[
                  column.align === 'right' ? 'text-right' : 'text-left',
                  'px-6 py-4 whitespace-nowrap',
                  column.tdClass || ''
                ]"
              >
                <!-- Если есть слот для ячейки, используем его -->
                <slot 
                  :name="column.key" 
                  :item="item" 
                  :column="column" 
                  :index="index"
                >
                  <!-- Если есть функция форматирования, используем её -->
                  <template v-if="column.format">
                    {{ column.format(item[column.key], item) }}
                  </template>
                  <!-- Иначе выводим значение как есть -->
                  <template v-else>
                    {{ item[column.key] }}
                  </template>
                </slot>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Пагинация -->
      <div v-if="showPagination" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="$emit('page-change', currentPage - 1)"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
          >
            Предыдущая
          </button>
          <button
            @click="$emit('page-change', currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
          >
            Следующая
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Показано <span class="font-medium">{{ (currentPage - 1) * perPage + 1 }}</span> - 
              <span class="font-medium">{{ Math.min(currentPage * perPage, totalItems) }}</span> из 
              <span class="font-medium">{{ totalItems }}</span> записей
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button
                @click="$emit('page-change', currentPage - 1)"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
              >
                <span class="sr-only">Предыдущая</span>
                &lt;
              </button>
              
              <!-- Номера страниц -->
              <template v-for="page in displayedPages" :key="page">
                <button
                  v-if="page !== '...'"
                  @click="$emit('page-change', page)"
                  :class="[
                    page === currentPage
                      ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                  ]"
                >
                  {{ page }}
                </button>
                <span
                  v-else
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                >
                  ...
                </span>
              </template>

              <button
                @click="$emit('page-change', currentPage + 1)"
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
    <!-- Пустое состояние -->
    <div v-else class="bg-white shadow rounded-lg p-8 text-center">
      <slot name="empty">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ emptyText }}</h3>
        <p class="mt-1 text-sm text-gray-500">
          {{ emptyDescription }}
        </p>
      </slot>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  // Данные для таблицы
  items: {
    type: Array,
    required: true
  },
  // Описание колонок
  columns: {
    type: Array,
    required: true
    // Ожидаемая структура:
    // [
    //   {
    //     key: 'id', // ключ для доступа к данным
    //     label: 'ID', // заголовок колонки
    //     align: 'left', // выравнивание (left, right)
    //     tdClass: 'text-sm', // дополнительные классы для ячейки
    //     format: (value, item) => `#${value}` // функция форматирования (опционально)
    //   }
    // ]
  },
  // Ключ для уникальной идентификации строк
  itemKey: {
    type: String,
    default: 'id'
  },
  // Текст для пустого состояния
  emptyText: {
    type: String,
    default: 'Нет данных'
  },
  // Описание для пустого состояния
  emptyDescription: {
    type: String,
    default: 'В данный момент записи отсутствуют.'
  },
  // Настройки пагинации
  showPagination: {
    type: Boolean,
    default: false
  },
  currentPage: {
    type: Number,
    default: 1
  },
  perPage: {
    type: Number,
    default: 10
  },
  totalItems: {
    type: Number,
    default: 0
  }
})

// Вычисляем общее количество страниц
const totalPages = computed(() => {
  return Math.ceil(props.totalItems / props.perPage)
})

// Функция для получения ключа элемента
const getItemKey = (item, index) => {
  return item[props.itemKey] || index
}

// Вычисляем страницы для отображения в пагинации
const displayedPages = computed(() => {
  const pages = []
  const maxVisiblePages = 5 // Максимальное количество видимых страниц
  
  if (totalPages.value <= maxVisiblePages) {
    // Если страниц мало, показываем все
    for (let i = 1; i <= totalPages.value; i++) {
      pages.push(i)
    }
  } else {
    // Иначе показываем текущую страницу и соседние
    let startPage = Math.max(1, props.currentPage - Math.floor(maxVisiblePages / 2))
    let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1)
    
    // Корректируем, если мы близко к концу
    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }
    
    // Добавляем первую страницу и многоточие, если нужно
    if (startPage > 1) {
      pages.push(1)
      if (startPage > 2) {
        pages.push('...')
      }
    }
    
    // Добавляем страницы
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
    
    // Добавляем последнюю страницу и многоточие, если нужно
    if (endPage < totalPages.value) {
      if (endPage < totalPages.value - 1) {
        pages.push('...')
      }
      pages.push(totalPages.value)
    }
  }
  
  return pages
})

// События
defineEmits(['page-change'])
</script>
