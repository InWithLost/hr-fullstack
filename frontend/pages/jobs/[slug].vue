<template>
  <div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Заголовок и основная информация -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold mb-4">{{ job.title }}</h1>
      <div class="flex flex-wrap items-center gap-4 text-gray-600">
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
          </svg>
          <span>{{ job.location.city }}, {{ job.location.district }}</span>
        </div>
        <span>•</span>
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
          </svg>
          <span>{{ job.employmentType }}</span>
        </div>
        <span>•</span>
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 3a1 1 0 011-1h.01a1 1 0 010 2H7a1 1 0 01-1-1zm2 3a1 1 0 00-2 0v1a2 2 0 00-2 2v1a2 2 0 002 2h.5a1 1 0 100 2h1a1 1 0 100-2H8a1 1 0 110-2h1a1 1 0 100-2H8V6zm7 0a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0V8h1a1 1 0 100-2h-1V6z" clip-rule="evenodd" />
          </svg>
          <span>Опыт работы {{ job.experience }}</span>
        </div>
      </div>
    </div>

    <!-- Основная информация -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Левая колонка -->
      <div class="lg:col-span-2">
        <!-- Описание -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
          <h2 class="text-2xl font-semibold mb-4">Описание вакансии</h2>
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ job.description }}</p>
        </div>

        <!-- Требования -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
          <h2 class="text-2xl font-semibold mb-4">Требования</h2>
          <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li v-for="(requirement, index) in job.requirements" :key="index">
              {{ requirement }}
            </li>
          </ul>
        </div>

        <!-- Обязанности -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
          <h2 class="text-2xl font-semibold mb-4">Обязанности</h2>
          <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li v-for="(duty, index) in job.duties" :key="index">
              {{ duty }}
            </li>
          </ul>
        </div>

        <!-- Дополнительная информация -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h2 class="text-2xl font-semibold mb-4">Дополнительная информация</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="font-medium text-gray-900 mb-2">Квалификация</h3>
              <p class="text-gray-700">{{ job.qualification }}</p>
            </div>
            <div v-if="job.languages && job.languages.length">
              <h3 class="font-medium text-gray-900 mb-2">Знание языков</h3>
              <p class="text-gray-700">{{ job.languages.join(', ') }}</p>
            </div>
            <div v-if="job.certification">
              <h3 class="font-medium text-gray-900 mb-2">Сертификация</h3>
              <p class="text-gray-700">{{ job.certification === 'required' ? 'Обязательна' : 'Не обязательна' }}</p>
            </div>
            <div v-if="job.startDate">
              <h3 class="font-medium text-gray-900 mb-2">Срок выхода</h3>
              <p class="text-gray-700">{{ formatStartDate(job.startDate) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Правая колонка -->
      <div>
        <!-- Зарплата и условия -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-6 sticky top-6">
          <h2 class="text-2xl font-semibold mb-4">Условия работы</h2>
          <div class="space-y-4">
            <div>
              <h3 class="font-medium text-gray-900 mb-1">Зарплата</h3>
              <p class="text-3xl font-bold text-blue-600">{{ formatSalary(job.salary) }} ₽</p>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 mb-1">График работы</h3>
              <p class="text-gray-700">{{ job.employmentType }}</p>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 mb-1">Адрес</h3>
              <p class="text-gray-700">{{ job.location.city }}, {{ job.location.district }}</p>
            </div>
            
            <!-- Кнопка отклика -->
            <div class="pt-4 mt-4 border-t border-gray-200">
              <button 
                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors mb-3"
                @click="handleApplicationClick"
              >
                Откликнуться на вакансию
              </button>
              
              <!-- Информация о модерации -->
              <div class="text-sm text-gray-500 text-center">
                Вакансия проверена и является актуальной
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Модальное окно с формой отклика -->
    <div v-if="showApplicationForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Отклик на вакансию</h2>
            <button @click="showApplicationForm = false" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <!-- Здесь будет форма отклика -->
          <form @submit.prevent="submitApplication" class="space-y-6">
            <!-- Личные данные -->
            <div>
              <h3 class="text-lg font-medium mb-4">Личные данные</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">ФИО *</label>
                  <input 
                    type="text" 
                    v-model="application.fullName" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Иванов Иван Иванович"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Дата рождения *</label>
                  <input 
                    type="date" 
                    v-model="application.birthDate" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Пол</label>
                  <select 
                    v-model="application.gender"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Выберите пол</option>
                    <option value="male">Мужской</option>
                    <option value="female">Женский</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Гражданство *</label>
                  <select 
                    v-model="application.citizenship"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Выберите гражданство</option>
                    <option value="russia">Россия</option>
                    <option value="belarus">Беларусь</option>
                    <option value="kazakhstan">Казахстан</option>
                    <option value="other">Другое</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Контактная информация -->
            <div>
              <h3 class="text-lg font-medium mb-4">Контактная информация</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Телефон *</label>
                  <input 
                    type="tel" 
                    v-model="application.phone" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="+7 (999) 123-45-67"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                  <input 
                    type="email" 
                    v-model="application.email" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="example@mail.ru"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Город проживания *</label>
                  <input 
                    type="text" 
                    v-model="application.city" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Москва"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Район</label>
                  <input 
                    type="text" 
                    v-model="application.district"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ховрино"
                  >
                </div>
              </div>
            </div>
            
            <!-- Образование и квалификация -->
            <div>
              <h3 class="text-lg font-medium mb-4">Образование и квалификация</h3>
              <div class="grid grid-cols-1 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Образование *</label>
                  <select 
                    v-model="application.education"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Выберите уровень образования</option>
                    <option value="secondary">Среднее</option>
                    <option value="special_secondary">Среднее специальное</option>
                    <option value="higher">Высшее</option>
                    <option value="phd">Кандидат наук</option>
                    <option value="doctor">Доктор наук</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Учебное заведение *</label>
                  <input 
                    type="text" 
                    v-model="application.institution" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Название учебного заведения"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Специализация *</label>
                  <input 
                    type="text" 
                    v-model="application.specialization" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Например: Терапевт"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Год окончания *</label>
                  <input 
                    type="number" 
                    v-model.number="application.graduationYear" 
                    required
                    min="1950"
                    :max="new Date().getFullYear()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Год"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Категория</label>
                  <select 
                    v-model="application.category"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Выберите категорию</option>
                    <option value="none">Без категории</option>
                    <option value="second">Вторая категория</option>
                    <option value="first">Первая категория</option>
                    <option value="highest">Высшая категория</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Наличие сертификата *</label>
                  <select 
                    v-model="application.certification"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Выберите вариант</option>
                    <option value="yes">Есть действующий сертификат</option>
                    <option value="expired">Сертификат просрочен</option>
                    <option value="no">Нет сертификата</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Опыт работы -->
            <div>
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Опыт работы</h3>
                <button 
                  type="button"
                  @click="addWorkExperience"
                  class="text-sm text-blue-600 hover:text-blue-800"
                >
                  + Добавить место работы
                </button>
              </div>
              
              <div v-for="(exp, index) in application.workExperience" :key="index" class="mb-4 border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                  <h4 class="font-medium">Место работы #{{ index + 1 }}</h4>
                  <button 
                    v-if="application.workExperience.length > 1"
                    type="button"
                    @click="removeWorkExperience(index)"
                    class="text-red-600 hover:text-red-800"
                  >
                    Удалить
                  </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Организация *</label>
                    <input 
                      type="text" 
                      v-model="exp.company" 
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Название организации"
                    >
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Должность *</label>
                    <input 
                      type="text" 
                      v-model="exp.position" 
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Должность"
                    >
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Дата начала *</label>
                    <input 
                      type="month" 
                      v-model="exp.startDate" 
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Дата окончания</label>
                    <input 
                      type="month" 
                      v-model="exp.endDate"
                      :max="getCurrentMonth()"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="По настоящее время, если не указано"
                    >
                  </div>
                </div>
                
                <div class="mt-3">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Обязанности</label>
                  <textarea 
                    v-model="exp.responsibilities"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Опишите ваши основные обязанности"
                  ></textarea>
                </div>
              </div>
            </div>
            
            <!-- Дополнительная информация -->
            <div>
              <h3 class="text-lg font-medium mb-4">Дополнительная информация</h3>
              <div class="grid grid-cols-1 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Знание языков</label>
                  <div class="space-y-2">
                    <div class="flex flex-wrap gap-4">
                      <label class="inline-flex items-center">
                        <input type="checkbox" v-model="application.languages" value="english" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2">Английский</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="checkbox" v-model="application.languages" value="german" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2">Немецкий</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="checkbox" v-model="application.languages" value="french" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2">Французский</span>
                      </label>
                      <label class="inline-flex items-center">
                        <input type="checkbox" v-model="application.languages" value="spanish" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2">Испанский</span>
                      </label>
                    </div>
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Желаемая зарплата (₽) *</label>
                  <input 
                    type="number" 
                    v-model.number="application.desiredSalary" 
                    required
                    min="0"
                    step="5000"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Например: 120000"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Дата возможного выхода на работу *</label>
                  <select 
                    v-model="application.possibleStartDate"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="">Выберите срок</option>
                    <option value="immediate">Немедленно</option>
                    <option value="week">В течение недели</option>
                    <option value="month">В течение месяца</option>
                    <option value="quarter">В течение 3 месяцев</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">О себе</label>
                  <textarea 
                    v-model="application.aboutMe"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Расскажите о своих профессиональных качествах, достижениях или увлечениях"
                  ></textarea>
                </div>
                
                <div class="mt-1">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Загрузить резюме</label>
                  <input 
                    type="file" 
                    ref="resumeUpload"
                    accept=".pdf,.doc,.docx"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  >
                  <p class="mt-1 text-xs text-gray-500">Поддерживаемые форматы: PDF, DOC, DOCX. Макс. размер: 5MB.</p>
                </div>
              </div>
            </div>
            
            <!-- Согласие на обработку данных -->
            <div class="mt-6">
              <label class="inline-flex items-center">
                <input 
                  type="checkbox" 
                  v-model="application.consentChecked"
                  required
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <span class="ml-2 text-sm text-gray-600">
                  Я согласен на обработку моих персональных данных в соответствии с <a href="#" class="text-blue-600 hover:underline">политикой конфиденциальности</a> *
                </span>
              </label>
            </div>
            
            <!-- Кнопка отправки -->
            <div class="mt-6 flex justify-end space-x-3">
              <button 
                type="button"
                @click="showApplicationForm = false"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
              >
                Отмена
              </button>
              <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!isFormValid"
              >
                Отправить отклик
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Модальное окно успешного отклика -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
          <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-center">Вы успешно откликнулись на вакансию</h2>
            <p class="text-gray-600 text-center mt-2">Посмотреть ваши отклики и их статус вы можете в личном кабинете, там же отклик можно отозвать.</p>
          </div>

          <div class="flex flex-col space-y-4">
            <button 
              @click="showSuccessModal = false" 
              class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors"
            >
              Остаться на странице
            </button>
            <button 
              @click="goToMyApplications" 
              class="w-full bg-white border border-blue-600 text-blue-600 py-3 px-6 rounded-lg hover:bg-blue-50 transition-colors"
            >
              Перейти в мои отклики
            </button>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="bg-yellow-50 p-4 rounded-lg">
              <p class="text-sm text-gray-700">Помните, чем подробнее заполнен ваш профиль, тем больше шансов трудоустроиться.</p>
              <button 
                @click="goToProfile" 
                class="mt-2 text-sm font-medium text-blue-600 hover:text-blue-800"
              >
                Заполнить профиль
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const route = useRoute()
const router = useRouter()
const showApplicationForm = ref(false)
const showSuccessModal = ref(false)
const isAuthenticated = ref(false) // В реальном приложении это будет определяться через store или API
const redirectAfterLogin = ref('')

// Форматирование даты выхода
function formatStartDate(startDate) {
  const formats = {
    'immediate': 'Немедленно',
    'week': 'В течение недели',
    'month': 'В течение месяца',
    'quarter': 'В течение квартала'
  }
  return formats[startDate] || startDate
}

// Обработка клика по кнопке отклика
function handleApplicationClick() {
  // Если пользователь не авторизован, перенаправляем на страницу авторизации
  if (!isAuthenticated.value) {
    // Сохраняем текущий URL для возврата после авторизации
    redirectAfterLogin.value = window.location.pathname
    // В реальном приложении можно сохранить это в localStorage или передать через query параметр
    localStorage.setItem('redirectAfterLogin', redirectAfterLogin.value)
    router.push('/login?redirect=' + encodeURIComponent(redirectAfterLogin.value))
  } else {
    // Если пользователь авторизован, показываем форму отклика
    showApplicationForm.value = true
  }
}

// Форматирование зарплаты
function formatSalary(salary) {
  return new Intl.NumberFormat('ru-RU').format(salary)
}

// Данные вакансии
const job = ref({
  title: 'Врач-терапевт',
  specialistType: 'Врач',
  specialization: 'Терапевт',
  qualification: 'Врач высшей категории',
  location: {
    city: 'Москва',
    district: 'Ховрино'
  },
  employmentType: 'Полная занятость',
  experience: 'от 3 лет',
  salary: 120000,
  startDate: 'immediate',
  languages: ['Английский'],
  certification: 'required',
  description: 'Мы ищем опытного врача-терапевта для работы в современной клинике. Наша клиника оснащена современным оборудованием и предоставляет широкий спектр медицинских услуг.\n\nМы предлагаем комфортные условия работы, дружный коллектив и возможности для профессионального роста.',
  requirements: [
    'Высшее медицинское образование',
    'Опыт работы от 3 лет',
    'Наличие действующего сертификата',
    'Знание современных методов диагностики и лечения',
    'Умение работать с медицинской документацией',
    'Навыки работы с ПК',
    'Ответственность и внимательность к пациентам'
  ],
  duties: [
    'Проведение первичного осмотра пациентов',
    'Назначение необходимых анализов и исследований',
    'Постановка диагнозов и разработка планов лечения',
    'Ведение медицинской документации',
    'Консультация пациентов по вопросам профилактики заболеваний',
    'Участие в консилиумах',
    'Контроль выполнения назначений'
  ]
})

// Форма отклика
const application = ref({
  fullName: '',
  birthDate: '',
  gender: '',
  citizenship: '',
  phone: '',
  email: '',
  city: '',
  district: '',
  education: '',
  institution: '',
  specialization: '',
  graduationYear: null,
  category: '',
  certification: '',
  workExperience: [
    {
      company: '',
      position: '',
      startDate: '',
      endDate: '',
      responsibilities: ''
    }
  ],
  additionalInfo: ''
})

function removeWorkExperience(index) {
  application.value.workExperience.splice(index, 1)
}

function getCurrentMonth() {
  const now = new Date()
  const year = now.getFullYear()
  const month = (now.getMonth() + 1).toString().padStart(2, '0')
  return `${year}-${month}`
}

// Валидация формы
const isFormValid = computed(() => {
  // Базовая валидация наличия обязательных полей
  return (
    application.value.fullName &&
    application.value.birthDate &&
    application.value.citizenship &&
    application.value.phone &&
    application.value.email &&
    application.value.city &&
    application.value.education &&
    application.value.institution &&
    application.value.specialization &&
    application.value.graduationYear &&
    application.value.workExperience.length > 0 &&
    application.value.workExperience.every(exp => (
      exp.company && exp.position && exp.startDate && exp.responsibilities
    ))
  )
})

// Отправка формы отклика
async function submitApplication() {
  try {
    // Здесь будет логика отправки отклика на сервер
    // В реальном приложении здесь будет API запрос
    // const response = await fetch('http://localhost:8000/api/applications', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify({
    //     job_id: job.value.id,
    //     ...application.value
    //   })
    // })
    // 
    // if (!response.ok) {
    //   throw new Error('Failed to submit application')
    // }
    
    console.log('Application submitted:', application.value)
    
    // Закрываем форму отклика и показываем модальное окно успеха
    showApplicationForm.value = false
    showSuccessModal.value = true
    
    // Сбрасываем форму
    application.value = {
      fullName: '',
      birthDate: '',
      gender: '',
      citizenship: '',
      phone: '',
      email: '',
      city: '',
      district: '',
      education: '',
      institution: '',
      specialization: '',
      graduationYear: null,
      category: '',
      certification: '',
      workExperience: [
        {
          company: '',
          position: '',
          startDate: '',
          endDate: '',
          responsibilities: ''
        }
      ],
      additionalInfo: ''
    }
  } catch (error) {
    console.error('Error submitting application:', error)
    alert('Произошла ошибка при отправке отклика. Пожалуйста, попробуйте еще раз.')
  }
}

// Переход в раздел "Мои отклики"
function goToMyApplications() {
  router.push('/profile/applications/')
}

// Переход в профиль пользователя
function goToProfile() {
  router.push('/profile')
}
</script>