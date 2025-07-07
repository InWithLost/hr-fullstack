<!--
  Компонент формы создания/редактирования вакансии для компаний.
  Включает все необходимые поля для вакансии, валидацию и обработку отправки формы.
  Отображает информацию о балансе компании и стоимости размещения вакансии.
  Позволяет выбрать дополнительные услуги, такие как услуга подбора кандидатов.
  Принимает данные компании (companyData) для проверки баланса и бесплатных размещений.
-->
<template>
  <form @submit.prevent="saveJob" class="max-h-[80vh] overflow-y-auto">
    <div class="bg-white px-8 pt-6 pb-6 sm:p-8 sm:pb-8">
      <!-- Название вакансии -->
      <div class="mb-6">
        <label class="block text-lg font-medium text-gray-900 mb-2">Название вакансии *</label>
        <input
          type="text"
          v-model="form.title"
          class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          placeholder="Введите название вакансии"
          required
        >
      </div>
      
      <!-- Статус вакансии -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Статус вакансии</label>
        <div class="mt-2 space-y-2">
          <label class="inline-flex items-center">
            <input
              type="radio"
              v-model="form.status"
              value="active"
              class="form-radio h-4 w-4 text-green-600"
            />
            <span class="ml-2 text-sm text-gray-700">Активна</span>
          </label>
          <div class="block">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="form.status"
                value="inactive"
                class="form-radio h-4 w-4 text-gray-600"
              />
              <span class="ml-2 text-sm text-gray-700">Неактивна</span>
            </label>
          </div>
          <div class="block">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="form.status"
                value="moderation"
                class="form-radio h-4 w-4 text-yellow-600"
              />
              <span class="ml-2 text-sm text-gray-700">На модерации</span>
            </label>
          </div>
        </div>
      </div>
      
      <div class="grid grid-cols-2 gap-6">
        <!-- Тип специалиста -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Тип специалиста *
          </label>
          <select 
            v-model="form.specialistType"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            @change="updateSpecializations"
          >
            <option value="">Выберите тип специалиста</option>
            <option value="Врач">Врач</option>
            <option value="Специалист со средним мед. образованием">Специалист со средним мед. образованием</option>
            <option value="Административный персонал">Административный персонал</option>
          </select>
        </div>

        <!-- Специализация -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Специализация *
          </label>
          <select 
            v-model="form.specialty_id"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">Выберите специализацию</option>
            <option v-for="specialty in specialties" :key="specialty.id" :value="specialty.id">
              {{ specialty.name }}
            </option>
          </select>
        </div>
        
        <!-- Квалификация -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Квалификация *
          </label>
          <input
            type="text"
            v-model="form.qualification"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите квалификацию"
          />
        </div>
        


        <!-- Пол -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Пол
          </label>
          <select 
            v-model="form.gender"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="any">Любой</option>
            <option value="male">Мужской</option>
            <option value="female">Женский</option>
          </select>
        </div>

        <!-- Языки -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Знание языков
          </label>
          <div class="flex flex-wrap gap-3">
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                v-model="form.languages"
                value="Русский"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              >
              <span class="ml-2">Русский</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                v-model="form.languages"
                value="Английский"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              >
              <span class="ml-2">Английский</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                v-model="form.languages"
                value="Немецкий"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              >
              <span class="ml-2">Немецкий</span>
            </label>
          </div>
        </div>

        <!-- Опыт -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Требуемый опыт *
          </label>
          <select 
            v-model="form.experience"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="0">Без опыта</option>
            <option value="1">От 1 года</option>
            <option value="3">От 3 лет</option>
            <option value="5">От 5 лет</option>
            <option value="10">От 10 лет</option>
          </select>
        </div>

        <!-- Срок выхода -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Срок выхода *
          </label>
          <select 
            v-model="form.startDate"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="immediate">Немедленно</option>
            <option value="week">В течение недели</option>
            <option value="month">В течение месяца</option>
            <option value="quarter">В течение квартала</option>
          </select>
        </div>

        <!-- Сертификация (для медицинских специалистов) -->
        <div class="col-span-2" v-if="form.specialistType === 'Врач' || form.specialistType === 'Специалист со средним мед. образованием'">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Сертификация
          </label>
          <div class="flex flex-wrap gap-3">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="form.certification"
                value="required"
                class="text-blue-600 focus:ring-blue-500"
              >
              <span class="ml-2">Обязательно</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="form.certification"
                value="not_required"
                class="text-blue-600 focus:ring-blue-500"
              >
              <span class="ml-2">Не обязательно</span>
            </label>
          </div>
        </div>

        <!-- Оплата обучения -->
        <div class="col-span-2" v-if="form.specialistType === 'Врач' || form.specialistType === 'Специалист со средним мед. образованием'">
          <div class="flex items-center gap-2 mb-2">
            <label class="block text-sm font-medium text-gray-700">
              Готовность оплатить обучение/аккредитацию специалиста
            </label>
          </div>
          <div class="mt-1">
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                v-model="form.trainingPayment"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              >
              <span class="ml-2">Да, готовы оплатить</span>
            </label>
          </div>
        </div>

        <!-- Город -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Город *
          </label>
          <input
            type="text"
            v-model="form.city"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите город"
          />
        </div>

        <!-- Район -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Район
          </label>
          <input
            type="text"
            v-model="form.district"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите район"
          />
        </div>

        <!-- Тип занятости -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Тип занятости *
          </label>
          <select 
            v-model="form.employmentType"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="">Выберите тип занятости</option>
            <option value="Полная занятость">Полная занятость</option>
            <option value="Частичная занятость">Частичная занятость</option>
            <option value="Проектная работа">Проектная работа</option>
            <option value="Стажировка">Стажировка</option>
          </select>
        </div>

        <!-- Зарплата -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Зарплата *
          </label>
          <input
            type="text"
            v-model="form.salary"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Например: 100 000 - 150 000 руб."
          />
        </div>



        <!-- Описание вакансии -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Описание вакансии *
          </label>
          <textarea
            v-model="form.description"
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Опишите вакансию"
          ></textarea>
        </div>

        <!-- Требования -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Требования *
          </label>
          <textarea
            v-model="form.requirements"
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Укажите требования к кандидату"
          ></textarea>
        </div>

        <!-- Обязанности -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Обязанности *
          </label>
          <textarea
            v-model="form.responsibilities"
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Опишите обязанности"
          ></textarea>
        </div>

        <!-- Уникальные требования (необязательно) -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Уникальные требования
          </label>
          <textarea
            v-model="form.uniqueRequirements"
            rows="3"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Укажите уникальные требования (при необходимости)"
          ></textarea>
        </div>
        

      </div>
    </div>

    <!-- Контактная информация -->
    <div class="border-t pt-8 mb-8 px-8">
      <h4 class="text-xl font-semibold mb-6">Контактные данные</h4>
      
      <!-- Выбор компании -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Компания (если зарегистрирована)
        </label>
        <select
          v-model="form.company_id"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          @change="fillContactInfo"
        >
          <option value="">Выберите компанию</option>
          <option v-for="company in companies" :key="company.id" :value="company.id">
            {{ company.name }}
          </option>
        </select>
        <p class="mt-1 text-sm text-gray-500">При выборе компании контактные данные будут заполнены автоматически</p>
      </div>
      
      <div class="grid grid-cols-2 gap-6">
        <!-- Full Name -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            ФИО контактного лица *
          </label>
          <input 
            type="text"
            v-model="form.contactInfo.fullName"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите ФИО"
          >
        </div>

        <!-- Position -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Должность *
          </label>
          <input 
            type="text"
            v-model="form.contactInfo.position"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите должность"
          >
        </div>

        <!-- Organization Name -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Название Организации
          </label>
          <input 
            type="text"
            v-model="form.contactInfo.organizationName"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите название"
          >
        </div>

        <!-- INN -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            ИНН организации *
          </label>
          <input 
            type="text"
            v-model="form.contactInfo.inn"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите ИНН"
          >
        </div>

        <!-- Phone -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Телефон *
          </label>
          <input 
            type="tel"
            v-model="form.contactInfo.phone"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="+7 (___) ___-__-__"
          >
        </div>

        <!-- Email -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Email *
          </label>
          <input 
            type="email"
            v-model="form.contactInfo.email"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="example@domain.com"
          >
        </div>
      </div>
    </div>

    <!-- Промокод и расчет стоимости -->
    <div class="border-t pt-8 px-8">
      <h4 class="text-xl font-semibold mb-6">Информация о стоимости</h4>
      <div class="grid grid-cols-2 gap-6">
        <!-- Промокод -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Промокод
          </label>
          <div class="flex gap-2">
            <input
              type="text"
              v-model="promoCode"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Введите промокод"
            >
            <button 
              type="button"
              @click="applyPromoCode"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Применить
            </button>
          </div>
        </div>

        <!-- Итоговая стоимость -->
        <div class="col-span-2 md:col-span-1 flex items-end">
          <div class="w-full">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm font-medium text-gray-700">Итого, стоимость подбора:</span>
              <span class="text-xl font-bold">{{ formattedTotalCost }} ₽</span>
            </div>
            <div v-if="promoCodeApplied" class="flex justify-between items-center text-green-600">
              <span class="text-sm">Скидка по промокоду:</span>
              <span>-{{ promoCodeDiscount }}%</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-gray-50 px-8 py-4 sm:px-8 sm:flex sm:flex-row-reverse mt-6">
      <button
        type="submit"
        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-3 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-4 sm:w-auto sm:text-sm"
      >
        Сохранить
      </button>
      <button
        type="button"
        @click="$emit('cancel')"
        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-4 sm:w-auto sm:text-sm"
      >
        Отмена
      </button>
    </div>
  </form>
</template>

<script setup>
import { defineProps, defineEmits, ref, computed, toRef } from 'vue'

const props = defineProps({
  job: {
    type: Object,
    required: true
  },
  companies: {
    type: Array,
    default: () => []
  },
  specialties: {
    type: Array,
    default: () => []
  },
  companyData: {
    type: Object,
    default: () => ({
      balance: 0,
      free_job_postings: 0
    })
  }
})

// Данные для специализаций и квалификаций
const specializationsByType = {
  'Врач': [
    'Терапевт', 'Педиатр', 'Акушер-гинеколог', 'Анестезиолог-реаниматолог',
    'Кардиолог', 'Невролог', 'Онколог', 'Офтальмолог', 'Рентгенолог',
    'Стоматолог', 'Травматолог-ортопед', 'Уролог', 'Эндокринолог'
  ],
  'Специалист со средним мед. образованием': [
    'Медицинская сестра процедурная', 'Медицинская сестра перевязочная',
    'Медицинская сестра операционная', 'Медицинская сестра-анестезист',
    'Фельдшер', 'Акушерка', 'Рентгенлаборант', 'Медицинский лабораторный техник'
  ],
  'Административный персонал': [
    'Главный врач', 'Заместитель главного врача', 'Заведующий отделением',
    'Старшая медицинская сестра', 'Главная медицинская сестра',
    'Медицинский регистратор', 'Администратор'
  ]
}

// Квалификации по специализации
const qualificationsBySpec = {
  'Терапевт': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  // Другие квалификации для разных специализаций...
}

// Районы по городам
const districtsByCity = {
  'Москва': ['Центральный', 'Северный', 'Южный', 'Западный', 'Восточный'],
  'Санкт-Петербург': ['Центральный', 'Адмиралтейский', 'Василеостровский'],
  'Новосибирск': ['Центральный', 'Железнодорожный', 'Заельцовский']
}

// Промокод и стоимость
const promoCode = ref('')
const promoCodeApplied = ref(false)
const promoCodeDiscount = ref(0)

// Баланс и стоимость
const jobPostingCost = ref(3000) // Стоимость размещения вакансии
const companyBalance = computed(() => props.companyData?.balance || 0)
const freeJobPostings = computed(() => props.companyData?.free_job_postings || 0)

// Общая стоимость размещения
const totalCost = computed(() => {
  let cost = jobPostingCost.value
  
  // Добавляем стоимость услуги подбора, если она включена
  if (form.value && form.value.has_recruitment_service && form.value.recruitment_service_cost) {
    cost += form.value.recruitment_service_cost
  }
  
  // Применяем скидку по промокоду, если он применен
  if (promoCodeApplied.value && promoCodeDiscount.value > 0) {
    cost = cost * (1 - promoCodeDiscount.value / 100)
  }
  
  return cost
})

const baseCost = computed(() => {
  if (!form.value || !form.value.specialistType) return 0
  
  switch(form.value.specialistType) {
    case 'Врач':
      return 50000
    case 'Специалист со средним мед. образованием':
      return 30000
    case 'Административный персонал':
      return 25000
    default:
      return 0
  }
})

const recruitmentBaseCost = computed(() => {
  if (promoCodeApplied.value) {
    return baseCost.value * (1 - promoCodeDiscount.value / 100)
  }
  return baseCost.value
})

const formattedTotalCost = computed(() => {
  return new Intl.NumberFormat('ru-RU').format(Math.round(recruitmentBaseCost.value))
})

const emit = defineEmits(['save', 'cancel'])

// Создаем реактивную ссылку на props.job
const jobRef = toRef(props, 'job')

// Используем вычисляемое свойство, чтобы избежать ошибок, если job не определен
const form = computed(() => jobRef.value || {})

// Функции
function getSpecializations(type) {
  return specializationsByType[type] || []
}

function getQualifications(spec) {
  return qualificationsBySpec[spec] || []
}

function getDistricts(city) {
  return districtsByCity[city] || []
}

function updateSpecializations() {
  if (form.value) {
    form.value.specialization = ''
    form.value.qualification = ''
  }
}

function updateQualifications() {
  if (form.value) {
    form.value.qualification = ''
  }
}

// Функция для автозаполнения контактных данных при выборе компании
function fillContactInfo() {
  if (!form.value) return
  
  console.log('fillContactInfo вызвана, company_id:', form.value.company_id)
  if (form.value.company_id) {
    // Преобразуем ID в число
    const companyId = parseInt(form.value.company_id)
    
    // Находим выбранную компанию в списке
    const selectedCompany = props.companies.find(company => company.id === companyId)
    console.log('Найденная компания:', selectedCompany)
    
    if (selectedCompany && form.value.contactInfo) {
      // Заполняем контактные данные из данных компании
      form.value.contactInfo.fullName = selectedCompany.contact_name || ''
      form.value.contactInfo.position = selectedCompany.position || ''
      form.value.contactInfo.organizationName = selectedCompany.name || ''
      form.value.contactInfo.inn = selectedCompany.inn || ''
      form.value.contactInfo.phone = selectedCompany.phone || ''
      form.value.contactInfo.email = selectedCompany.email || ''
      
      console.log('Контактные данные заполнены:', form.value.contactInfo)
    }
  } else if (form.value.contactInfo) {
    // Если компания не выбрана, очищаем поля
    form.value.contactInfo.fullName = ''
    form.value.contactInfo.position = ''
    form.value.contactInfo.organizationName = ''
    form.value.contactInfo.inn = ''
    form.value.contactInfo.phone = ''
    form.value.contactInfo.email = ''
  }
}

function applyPromoCode() {
  if (promoCode.value === 'DISCOUNT10') {
    promoCodeApplied.value = true
    promoCodeDiscount.value = 10
  }
}

// Расчет стоимости услуги подбора кандидатов
function calculateRecruitmentServiceCost() {
  if (!form.value || !form.value.has_recruitment_service) {
    if (form.value) {
      form.value.recruitment_service_cost = 0
    }
    return
  }
  
  // Базовая стоимость зависит от типа специалиста
  let baseCost = 5000 // Базовая стоимость
  
  if (form.value.specialistType === 'Врач') {
    baseCost = 10000 // Для врачей стоимость выше
  } else if (form.value.specialistType === 'Специалист со средним мед. образованием') {
    baseCost = 7000 // Для среднего мед. персонала
  }
  
  // Дополнительные факторы, влияющие на стоимость
  // Например, опыт работы
  if (form.value.experience === 'more_than_6') {
    baseCost *= 1.3 // +30% для опытных специалистов
  } else if (form.value.experience === 'more_than_3') {
    baseCost *= 1.15 // +15% для специалистов с опытом 3-6 лет
  }
  
  // Округляем до целых чисел
  form.value.recruitment_service_cost = Math.round(baseCost)
}

// Функция форматирования валюты
function formatCurrency(amount) {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    minimumFractionDigits: 0
  }).format(amount)
}

async function saveJob() {
  if (!form.value) return
  
  // Проверяем, есть ли ИНН в контактных данных
  const inn = form.value.contactInfo?.inn
  let companyId = form.value.company_id
  
  // Если ИНН указан, но компания не выбрана
  if (inn && !companyId) {
    // Проверяем, есть ли компания с таким ИНН в списке
    const existingCompany = props.companies.find(company => company.inn === inn)
    
    if (existingCompany) {
      // Если компания существует, используем её ID
      companyId = existingCompany.id
      form.value.company_id = existingCompany.id
    } else if (form.value.contactInfo) {
      // Если компании нет, создаем новую
      const newCompany = {
        name: form.value.contactInfo.organizationName,
        inn: inn,
        contact_name: form.value.contactInfo.fullName,
        position: form.value.contactInfo.position,
        phone: form.value.contactInfo.phone,
        email: form.value.contactInfo.email,
        created_at: new Date().toISOString()
      }
      
      // В реальном приложении здесь был бы API-запрос на создание компании
      // Для демонстрации просто присваиваем временный ID
      // const response = await fetch('http://localhost:8000/api/admin/companies', {
      //   method: 'POST',
      //   headers: {
      //     'Content-Type': 'application/json',
      //     'Authorization': `Bearer ${localStorage.getItem('token')}`
      //   },
      //   body: JSON.stringify(newCompany)
      // })
      // const data = await response.json()
      // companyId = data.id
      
      // Для демонстрации используем временный ID
      const tempId = Math.floor(Math.random() * 1000) + 100 // Генерируем случайный ID
      companyId = tempId
      form.value.company_id = tempId
      
      // Добавляем новую компанию в список (для демонстрации)
      newCompany.id = tempId
      props.companies.push(newCompany)
      
      // Уведомляем пользователя о создании новой компании
      alert(`Создана новая компания: ${newCompany.name}`)
    }
  }
  
  // Проверяем достаточно ли средств на балансе или бесплатных размещений
  if (form.value.id === undefined || form.value.id === null) { // Только для новых вакансий
    if (freeJobPostings.value <= 0 && companyBalance.value < totalCost.value) {
      if (!confirm(`Недостаточно средств на балансе для размещения вакансии. Необходимо: ${formatCurrency(totalCost.value)}. На балансе: ${formatCurrency(companyBalance.value)}. Перейти к пополнению баланса?`)) {
        return // Отменяем сохранение, если пользователь отказался пополнить баланс
      } else {
        // Перенаправляем на страницу пополнения баланса
        window.location.href = '/company/profile/balance'
        return
      }
    }
  }
  
  // Добавляем данные о промокоде, стоимости и балансе в форму
  const formData = {
    ...form.value,
    company_id: companyId,
    promoCode: promoCode.value,
    promoCodeApplied: promoCodeApplied.value,
    promoCodeDiscount: promoCodeDiscount.value,
    recruitmentCost: totalCost.value,
    useFreePosting: freeJobPostings.value > 0,
    totalCost: totalCost.value
  }
  
  emit('save', formData)
}
</script>
