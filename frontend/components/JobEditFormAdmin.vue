<!--
  Компонент формы создания/редактирования вакансии для администраторов.
  Включает все необходимые поля для вакансии, соответствующие форме заявки на подбор специалиста.
  Позволяет администратору указать все детали вакансии, включая тип специалиста, специализацию,
  квалификацию, опыт работы и другие требования.
  Используется на страницах создания и редактирования вакансий в административной панели.
-->
<template>
  <form @submit.prevent="saveJob" class="space-y-6">
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
          <option v-for="type in specialistTypes" :key="type" :value="type">
            {{ type }}
          </option>
        </select>
        <p v-if="errors.specialistType" class="mt-2 text-sm text-red-600">{{ errors.specialistType }}</p>
      </div>

      <!-- Специализация -->
      <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Специализация *
        </label>
        <select 
          v-model="form.specialization"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          @change="updateQualifications"
          :disabled="!form.specialistType"
        >
          <option value="">Выберите специализацию</option>
          <option v-for="spec in getSpecializations(form.specialistType)" :key="spec" :value="spec">
            {{ spec }}
          </option>
        </select>
        <p v-if="errors.specialization" class="mt-2 text-sm text-red-600">{{ errors.specialization }}</p>
      </div>

      <!-- Квалификация -->
      <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Квалификация *
        </label>
        <select 
          v-model="form.qualification"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          :disabled="!form.specialization"
        >
          <option value="">Выберите квалификацию</option>
          <option v-for="qual in getQualifications(form.specialistType, form.specialization)" :key="qual" :value="qual">
            {{ qual }}
          </option>
        </select>
        <p v-if="errors.qualification" class="mt-2 text-sm text-red-600">{{ errors.qualification }}</p>
      </div>

      <!-- Категория (для врачей и медицинских специалистов) -->
      <div class="col-span-2 md:col-span-1" v-if="form.specialistType === 'Врач' || form.specialistType === 'Специалист со средним мед. образованием'">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Категория *
        </label>
        <select 
          v-model="form.category"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">Выберите категорию</option>
          <option value="none">Без категории</option>
          <option value="second">Вторая категория</option>
          <option value="first">Первая категория</option>
          <option value="highest">Высшая категория</option>
        </select>
        <p v-if="errors.category" class="mt-2 text-sm text-red-600">{{ errors.category }}</p>
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
          <option value="">Не имеет значения</option>
          <option value="male">Мужской</option>
          <option value="female">Женский</option>
        </select>
      </div>

      <!-- Языки -->
      <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Знание языков
        </label>
        <div class="relative">
          <div 
            @click="toggleLanguageDropdown($event)"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer flex items-center justify-between"
          >
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="lang in form.languages" 
                :key="lang"
                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm"
              >
                {{ lang }}
              </span>
              <span v-if="form.languages.length === 0" class="text-gray-500">Выберите языки</span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          
          <!-- Dropdown menu -->
          <div 
            v-if="languageDropdownOpen" 
            class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md overflow-auto max-h-60 border border-gray-300"
            @click.stop
          >
            <div class="p-2">
              <label 
                v-for="lang in languages" 
                :key="lang" 
                class="flex items-center p-2 hover:bg-gray-100 rounded cursor-pointer"
              >
                <input 
                  type="checkbox" 
                  v-model="form.languages" 
                  :value="lang"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                >
                <span class="ml-2">{{ lang }}</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Опыт работы -->
      <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Требуемый опыт *
        </label>
        <select 
          v-model="form.experience"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">Выберите требуемый опыт</option>
          <option value="0">Без опыта</option>
          <option value="1">От 1 года</option>
          <option value="3">От 3 лет</option>
          <option value="5">От 5 лет</option>
          <option value="10">От 10 лет</option>
        </select>
        <p v-if="errors.experience" class="mt-2 text-sm text-red-600">{{ errors.experience }}</p>
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
          <option value="">Выберите срок выхода</option>
          <option value="immediate">Немедленно</option>
          <option value="week">В течение недели</option>
          <option value="month">В течение месяца</option>
          <option value="quarter">В течение квартала</option>
        </select>
        <p v-if="errors.startDate" class="mt-2 text-sm text-red-600">{{ errors.startDate }}</p>
      </div>

      <!-- Сертификация -->
      <div class="col-span-2" v-if="form.specialistType === 'Врач' || form.specialistType === 'Специалист со средним мед. образованием'">
        <div class="flex items-center gap-2 mb-2">
          <label class="block text-sm font-medium text-gray-700">
            Наличие сертификата/аккредитации
          </label>
          <span class="text-xs text-gray-500">(для медицинских специалистов)</span>
        </div>
        <div class="flex items-center gap-4">
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
        <div class="flex items-center gap-4">
          <label class="inline-flex items-center">
            <input 
              type="radio" 
              v-model="form.trainingPayment" 
              value="yes"
              class="text-blue-600 focus:ring-blue-500"
            >
            <span class="ml-2">Да</span>
          </label>
          <label class="inline-flex items-center">
            <input 
              type="radio" 
              v-model="form.trainingPayment" 
              value="no"
              class="text-blue-600 focus:ring-blue-500"
            >
            <span class="ml-2">Нет</span>
          </label>
        </div>
      </div>

      <!-- Город и район -->
      <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Город *
        </label>
        <div class="relative city-dropdown">
          <input 
            type="text" 
            v-model="citySearchQuery"
            @focus="showCityDropdown = true"
            @input="searchCities"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Начните вводить название города"
          />
          <div 
            v-if="showCityDropdown && filteredCities.length > 0" 
            class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md overflow-auto max-h-60 border border-gray-300"
          >
            <ul class="py-1">
              <li 
                v-for="city in filteredCities" 
                :key="city.id"
                @click="selectCity(city)"
                class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
              >
                {{ city.name }}
              </li>
            </ul>
          </div>
        </div>
        <p v-if="errors.city" class="mt-2 text-sm text-red-600">{{ errors.city }}</p>
      </div>

      <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Район
        </label>
        <div class="relative district-dropdown">
          <input 
            type="text" 
            v-model="districtSearchQuery"
            @focus="showDistrictDropdown = true"
            @input="searchDistricts"
            :disabled="!form.city"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            :class="{ 'bg-gray-100': !form.city }"
            placeholder="Сначала выберите город"
          />
          <div 
            v-if="showDistrictDropdown && filteredDistricts.length > 0" 
            class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md overflow-auto max-h-60 border border-gray-300"
          >
            <ul class="py-1">
              <li 
                v-for="district in filteredDistricts" 
                :key="district.id"
                @click="selectDistrict(district)"
                class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
              >
                {{ district.name }}
              </li>
            </ul>
          </div>
        </div>
        <p v-if="errors.district" class="mt-2 text-sm text-red-600">{{ errors.district }}</p>
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
          <option value="full-time">Полная занятость</option>
          <option value="part-time">Частичная занятость</option>
          <option value="contract">Контрактная работа</option>
          <option value="temporary">Временная работа</option>
          <option value="internship">Стажировка</option>
        </select>
        <p v-if="errors.employmentType" class="mt-2 text-sm text-red-600">{{ errors.employmentType }}</p>
      </div>

      <!-- Зарплата -->
      <div class="col-span-2 md:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Зарплата
        </label>
        <input 
          type="text" 
          v-model="form.salary"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          placeholder="например: от 100 000 руб."
        />
      </div>



      <!-- Описание вакансии -->
      <div class="col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Описание вакансии
        </label>
        <SimpleRichTextEditor
          v-model="form.description"
          :class="errors.description ? 'border-red-300' : ''"
        />
        <p v-if="errors.description" class="mt-2 text-sm text-red-600">{{ errors.description }}</p>
      </div>

      <!-- Требования к кандидату -->
      <div class="col-span-2">
        <div class="flex justify-between items-center mb-2">
          <label class="block text-sm font-medium text-gray-700">
            Требования к кандидату
          </label>
        </div>
        
        <div v-for="(req, reqIndex) in form.requirementsList" :key="reqIndex" class="mb-2">
          <div class="flex items-center gap-2">
            <input 
              type="text" 
              v-model="form.requirementsList[reqIndex]" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Укажите требование"
            />
            <button 
              v-if="form.requirementsList.length > 1"
              @click="removeRequirement(reqIndex)" 
              type="button"
              class="p-1 text-red-500 hover:text-red-700 transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
        
        <button 
          @click="addRequirement()" 
          type="button"
          class="mt-2 text-sm text-blue-600 hover:text-blue-800 flex items-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
          </svg>
          Добавить требование
        </button>
      </div>

      <!-- Обязанности -->
      <div class="col-span-2">
        <div class="flex justify-between items-center mb-2">
          <label class="block text-sm font-medium text-gray-700">
            Обязанности
          </label>
        </div>
        
        <div v-for="(resp, respIndex) in form.responsibilitiesList" :key="respIndex" class="mb-2">
          <div class="flex items-center gap-2">
            <input 
              type="text" 
              v-model="form.responsibilitiesList[respIndex]" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Укажите обязанность"
            />
            <button 
              v-if="form.responsibilitiesList.length > 1"
              @click="removeResponsibility(respIndex)" 
              type="button"
              class="p-1 text-red-500 hover:text-red-700 transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
        
        <button 
          @click="addResponsibility()" 
          type="button"
          class="mt-2 text-sm text-blue-600 hover:text-blue-800 flex items-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
          </svg>
          Добавить обязанность
        </button>
      </div>

      <!-- Уникальные требования -->
      <div class="col-span-2">
        <div class="flex items-center gap-2 mb-1">
          <label class="block text-sm font-medium text-gray-700">
            Уникальные требования
          </label>
          <div class="relative">
            <div 
              class="text-gray-400 hover:text-gray-600 cursor-help"
              @mouseenter="showUniqueRequirementsTooltip = true"
              @mouseleave="showUniqueRequirementsTooltip = false"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
              <div 
                v-show="showUniqueRequirementsTooltip"
                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-72 p-2 bg-gray-900 text-white text-sm rounded shadow-lg"
              >
                Данную информацию увидят только специалисты по подбору, она ни где не будет опубликована
                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900"></div>
              </div>
            </div>
          </div>
        </div>
        <textarea
          v-model="form.uniqueRequirements"
          rows="3"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Укажите уникальные требования..."
        ></textarea>
      </div>

      <!-- Статус вакансии -->
      <div class="col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Статус вакансии
        </label>
        <div class="flex items-center gap-4">
          <label class="inline-flex items-center">
            <input 
              type="radio" 
              v-model="form.status" 
              value="active"
              class="text-blue-600 focus:ring-blue-500"
            >
            <span class="ml-2">Активна</span>
          </label>
          <label class="inline-flex items-center">
            <input 
              type="radio" 
              v-model="form.status" 
              value="inactive"
              class="text-blue-600 focus:ring-blue-500"
            >
            <span class="ml-2">Не активна</span>
          </label>
          <label class="inline-flex items-center">
            <input 
              type="radio" 
              v-model="form.status" 
              value="moderation"
              class="text-blue-600 focus:ring-blue-500"
            >
            <span class="ml-2">На модерации</span>
          </label>
        </div>
      </div>
      
      <!-- Выбор компании -->
      <div class="col-span-2 mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Информация о компании</h3>
        <div class="grid grid-cols-1 gap-6 mb-6">
          <!-- Выбор компании -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Компания *
            </label>
            <div class="relative company-dropdown">
              <input 
                type="text" 
                v-model="companySearchQuery"
                @focus="showCompanyDropdown = true"
                @input="searchCompanies"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Начните вводить название компании"
              />
              <div 
                v-if="showCompanyDropdown && filteredCompanies.length > 0" 
                class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md overflow-auto max-h-60 border border-gray-300"
              >
                <ul class="py-1">
                  <li 
                    v-for="company in filteredCompanies" 
                    :key="company.id"
                    @click="selectCompany(company)"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                  >
                    {{ company.name }}
                  </li>
                </ul>
              </div>
            </div>
            <p v-if="errors.companyId" class="mt-2 text-sm text-red-600">{{ errors.companyId }}</p>
          </div>
          
          <!-- ИНН организации (только для просмотра) -->
          <div v-if="form.companyId">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              ИНН организации
            </label>
            <input 
              type="text" 
              v-model="form.contactInfo.inn"
              disabled
              class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
            />
          </div>
          
          <!-- Информация о балансе и бесплатных размещениях -->
          <div v-if="form.companyId" class="p-4 bg-blue-50 rounded-lg">
            <h4 class="font-medium text-blue-800 mb-2">Баланс компании</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-700">Доступно бесплатных размещений: <span class="font-bold">{{ selectedCompanyData.freePostings || 0 }}</span></p>
              </div>
              <div>
                <p class="text-sm text-gray-700">Баланс: <span class="font-bold">{{ selectedCompanyData.balance || 0 }} ₽</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Контактные данные -->
      <div class="col-span-2">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Контактные данные</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- ФИО контактного лица -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              ФИО контактного лица *
            </label>
            <input 
              type="text" 
              v-model="form.contactInfo.fullName"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Иванов Иван Иванович"
            />
            <p v-if="errors['contactInfo.fullName']" class="mt-2 text-sm text-red-600">{{ errors['contactInfo.fullName'] }}</p>
          </div>
          
          <!-- Должность контактного лица -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Должность контактного лица *
            </label>
            <input 
              type="text" 
              v-model="form.contactInfo.position"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="HR-менеджер"
            />
            <p v-if="errors['contactInfo.position']" class="mt-2 text-sm text-red-600">{{ errors['contactInfo.position'] }}</p>
          </div>
          
          <!-- Телефон -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Телефон *
            </label>
            <input 
              type="tel" 
              v-model="form.contactInfo.phone"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="+7 (999) 123-45-67"
            />
            <p v-if="errors['contactInfo.phone']" class="mt-2 text-sm text-red-600">{{ errors['contactInfo.phone'] }}</p>
          </div>
          
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Email *
            </label>
            <input 
              type="email" 
              v-model="form.contactInfo.email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="example@mail.ru"
            />
            <p v-if="errors['contactInfo.email']" class="mt-2 text-sm text-red-600">{{ errors['contactInfo.email'] }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Блок услуги хантера и подсчет стоимости -->
    <div class="col-span-2 mt-8 border-t border-gray-200 pt-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Услуги и стоимость</h3>
      
      <!-- Услуга хантера -->
      <div class="mb-6">
        <div class="flex items-center">
          <input 
            type="checkbox" 
            id="hunter-service" 
            v-model="form.hunterService"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
          />
          <label for="hunter-service" class="ml-2 block text-sm font-medium text-gray-700">
            Привлечь хантера
          </label>
          <div class="relative ml-2 group">
            <button 
              type="button" 
              class="text-gray-400 hover:text-gray-500 focus:outline-none"
            >
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
              </svg>
            </button>
            <div class="absolute left-0 bottom-full mb-2 w-64 bg-gray-800 text-white text-xs rounded p-2 hidden group-hover:block z-10">
              Хантер - специалист, который будет искать нужного специалиста и направлять вам только описанных в вакансии кандидатов. Услуга оплачивается отдельно.
              <div class="absolute top-full left-4 transform -translate-x-1/2 -translate-y-1 border-4 border-transparent border-t-gray-800"></div>
            </div>
          </div>
        </div>
        <p class="mt-1 text-sm text-gray-500">Услуга оплачивается отдельно, стоимость: {{ hunterServicePrice }} ₽</p>
      </div>
      
      <!-- Промокод -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Промокод на скидку
        </label>
        <div class="flex">
          <input 
            type="text" 
            v-model="promoCode"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Введите промокод"
          />
          <button 
            type="button" 
            @click="applyPromoCode"
            class="px-4 py-2 border border-transparent rounded-r-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Применить
          </button>
        </div>
        <p v-if="promoCodeMessage" :class="[`mt-2 text-sm`, promoCodeValid ? 'text-green-600' : 'text-red-600']">{{ promoCodeMessage }}</p>
      </div>
      
      <!-- Расчет стоимости -->
      <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h4 class="font-medium text-gray-900 mb-3">Расчет стоимости</h4>
        <div class="space-y-2">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600">Стоимость размещения вакансии:</span>
            <span class="text-sm font-medium">{{ jobPostingPrice }} ₽</span>
          </div>
          <div v-if="form.hunterService" class="flex justify-between">
            <span class="text-sm text-gray-600">Услуга хантера:</span>
            <span class="text-sm font-medium">{{ hunterServicePrice }} ₽</span>
          </div>
          <div v-if="discount > 0" class="flex justify-between">
            <span class="text-sm text-gray-600">Скидка по промокоду:</span>
            <span class="text-sm font-medium text-green-600">-{{ discount }} ₽</span>
          </div>
          <div class="border-t border-gray-200 pt-2 mt-2">
            <div class="flex justify-between">
              <span class="text-sm font-medium text-gray-900">Итого:</span>
              <span class="text-sm font-bold">{{ totalPrice }} ₽</span>
            </div>
          </div>
          <div v-if="selectedCompanyData.freePostings > 0" class="mt-2 p-2 bg-green-50 rounded text-sm text-green-800">
            У компании есть {{ selectedCompanyData.freePostings }} бесплатных размещений. Будет использовано бесплатное размещение.
          </div>
          <div v-else-if="selectedCompanyData.balance < totalPrice" class="mt-2 p-2 bg-red-50 rounded text-sm text-red-800">
            Недостаточно средств на балансе компании. Пополните баланс или используйте промокод.
          </div>
        </div>
      </div>
    </div>
    
    <div class="flex justify-end space-x-3 pt-6">
      <button 
        type="button" 
        @click="$emit('cancel')"
        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        :disabled="isSubmitting"
      >
        Отмена
      </button>
      <button 
        type="submit" 
        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        :disabled="isSubmitting || (selectedCompanyData.freePostings === 0 && selectedCompanyData.balance < totalPrice)"
      >
        {{ isSubmitting ? 'Сохранение...' : 'Сохранить' }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { defineProps, defineEmits, ref, computed, toRef, onMounted, onUnmounted, watch } from 'vue'
import SimpleRichTextEditor from '~/components/SimpleRichTextEditor.vue'

const props = defineProps({
  job: {
    type: Object,
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  isSubmitting: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['save', 'cancel'])

// Переменные для работы с компаниями
const companySearchQuery = ref('')
const showCompanyDropdown = ref(false)
const filteredCompanies = ref([])
const selectedCompanyData = ref({ balance: 0, freePostings: 0 })

// Переменные для работы с городами и районами
const citySearchQuery = ref('')
const showCityDropdown = ref(false)
const filteredCities = ref([])

const districtSearchQuery = ref('')
const showDistrictDropdown = ref(false)
const filteredDistricts = ref([])

// Переменная для всплывающей подсказки уникальных требований
const showUniqueRequirementsTooltip = ref(false)

// Переменная для выпадающего списка языков
const languageDropdownOpen = ref(false)

// Переменные для расчета стоимости
const jobPostingPrice = ref(5000) // Стоимость размещения вакансии
const hunterServicePrice = ref(15000) // Стоимость услуги хантера
const promoCode = ref('')
const promoCodeMessage = ref('')
const promoCodeValid = ref(false)
const discount = ref(0)

// Вычисляемое свойство для расчета общей стоимости
const totalPrice = computed(() => {
  let total = 0
  
  // Если у компании есть бесплатные размещения, то стоимость размещения = 0
  if (selectedCompanyData.value.freePostings > 0) {
    total = 0
  } else {
    total = jobPostingPrice.value
  }
  
  // Добавляем стоимость услуги хантера, если выбрана
  if (form.value.hunterService) {
    total += hunterServicePrice.value
  }
  
  // Вычитаем скидку по промокоду
  total = Math.max(0, total - discount.value)
  
  return total
})

// Создаем реактивную ссылку на props.job
const jobRef = toRef(props, 'job')

// Используем вычисляемое свойство, чтобы избежать ошибок, если job не определен
const form = computed(() => {
  // Инициализируем значения по умолчанию, если они не заданы
  const job = jobRef.value || {}
  
  // Убедимся, что массив языков существует
  if (!job.languages) {
    job.languages = []
  }
  
  // Установим статус по умолчанию, если не задан
  if (!job.status) {
    job.status = 'moderation'
  }
  
  // Инициализируем объект контактных данных, если он не существует
  if (!job.contactInfo) {
    job.contactInfo = {
      fullName: '',
      position: '',
      organizationName: '',
      inn: '',
      phone: '',
      email: ''
    }
  }
  
  // Инициализируем категорию для врачей и медицинских специалистов
  if (!job.category) {
    job.category = ''
  }
  
  // Инициализируем списки требований и обязанностей
  if (!job.requirementsList) {
    job.requirementsList = ['']
  }
  
  if (!job.responsibilitiesList) {
    job.responsibilitiesList = ['']
  }
  
  // Инициализируем уникальные требования
  if (!job.uniqueRequirements) {
    job.uniqueRequirements = ''
  }
  
  return job
})

// Справочные данные для формы
const specialistTypes = [
  'Врач',
  'Специалист со средним мед. образованием',
  'Администратор',
  'Специалист без мед. образования'
]

const languages = [
  'Английский',
  'Немецкий',
  'Французский',
  'Испанский',
  'Китайский',
  'Арабский'
]

// Справочники специализаций и квалификаций
const specializations = {
  'Врач': [
    'Терапевт',
    'Хирург',
    'Педиатр',
    'Кардиолог',
    'Невролог',
    'Офтальмолог',
    'Отоларинголог',
    'Гастроэнтеролог',
    'Эндокринолог',
    'Дерматолог'
  ],
  'Специалист со средним мед. образованием': [
    'Медицинская сестра',
    'Фельдшер',
    'Акушерка',
    'Лаборант',
    'Фармацевт',
    'Рентгенлаборант'
  ],
  'Администратор': [
    'Администратор клиники',
    'Администратор регистратуры',
    'Администратор колл-центра',
    'Менеджер по работе с клиентами'
  ],
  'Специалист без мед. образования': [
    'Санитар',
    'Сиделка',
    'Младший медицинский персонал',
    'Водитель',
    'IT-специалист',
    'Бухгалтер',
    'HR-специалист'
  ]
}

const qualifications = {
  'Терапевт': ['Врач-терапевт участковый', 'Врач общей практики', 'Врач-терапевт цехового врачебного участка'],
  'Хирург': ['Врач-хирург', 'Врач-нейрохирург', 'Врач-сердечно-сосудистый хирург', 'Врач-пластический хирург'],
  'Педиатр': ['Врач-педиатр участковый', 'Врач-педиатр городской', 'Врач-неонатолог'],
  'Кардиолог': ['Врач-кардиолог', 'Врач-кардиолог детский'],
  'Невролог': ['Врач-невролог', 'Врач-невролог детский'],
  'Офтальмолог': ['Врач-офтальмолог', 'Врач-офтальмолог детский'],
  'Отоларинголог': ['Врач-оториноларинголог', 'Врач-оториноларинголог детский'],
  'Гастроэнтеролог': ['Врач-гастроэнтеролог', 'Врач-гастроэнтеролог детский'],
  'Эндокринолог': ['Врач-эндокринолог', 'Врач-эндокринолог детский'],
  'Дерматолог': ['Врач-дерматовенеролог', 'Врач-дерматолог детский'],
  'Медицинская сестра': ['Медицинская сестра палатная', 'Медицинская сестра процедурной', 'Медицинская сестра перевязочной', 'Операционная медицинская сестра'],
  'Фельдшер': ['Фельдшер скорой помощи', 'Фельдшер ФАП', 'Фельдшер-лаборант'],
  'Акушерка': ['Акушерка родильного дома', 'Акушерка женской консультации'],
  'Лаборант': ['Лаборант клинической лаборатории', 'Лаборант биохимической лаборатории', 'Лаборант микробиологической лаборатории'],
  'Фармацевт': ['Фармацевт-провизор', 'Фармацевт-технолог'],
  'Рентгенлаборант': ['Рентгенлаборант', 'Рентгенлаборант КТ', 'Рентгенлаборант МРТ'],
  'Администратор клиники': ['Администратор клиники', 'Старший администратор клиники'],
  'Администратор регистратуры': ['Администратор регистратуры', 'Старший администратор регистратуры'],
  'Администратор колл-центра': ['Оператор колл-центра', 'Старший оператор колл-центра'],
  'Менеджер по работе с клиентами': ['Менеджер по работе с клиентами', 'Старший менеджер по работе с клиентами'],
  'Санитар': ['Санитар', 'Санитар-уборщик', 'Санитар морга'],
  'Сиделка': ['Сиделка', 'Сиделка с проживанием', 'Сиделка с медицинским образованием'],
  'Младший медицинский персонал': ['Младшая медицинская сестра', 'Санитар'],
  'Водитель': ['Водитель автомобиля', 'Водитель скорой помощи'],
  'IT-специалист': ['Системный администратор', 'Программист', 'Специалист технической поддержки'],
  'Бухгалтер': ['Бухгалтер', 'Старший бухгалтер', 'Главный бухгалтер'],
  'HR-специалист': ['HR-менеджер', 'Специалист по подбору персонала', 'Руководитель HR-отдела']
}

// Функции для работы с справочниками
function getSpecializations(specialistType) {
  return specialistType ? specializations[specialistType] || [] : []
}

function getQualifications(specialistType, specialization) {
  return (specialistType && specialization) ? qualifications[specialization] || [] : []
}

// Функции обновления зависимых полей
function updateSpecializations() {
  // Сбрасываем зависимые поля при изменении типа специалиста
  form.value.specialization = ''
  form.value.qualification = ''
}

function updateQualifications() {
  // Сбрасываем квалификацию при изменении специализации
  form.value.qualification = ''
}

// Функции для работы с требованиями и обязанностями
function addRequirement() {
  form.value.requirementsList.push('')
}

function removeRequirement(index) {
  if (form.value.requirementsList.length > 1) {
    form.value.requirementsList.splice(index, 1)
  }
}

function addResponsibility() {
  form.value.responsibilitiesList.push('')
}

function removeResponsibility(index) {
  if (form.value.responsibilitiesList.length > 1) {
    form.value.responsibilitiesList.splice(index, 1)
  }
}

// Функция для управления выпадающим списком языков
function toggleLanguageDropdown(event) {
  // Если есть событие, предотвращаем всплытие события
  if (event) {
    event.stopPropagation()
  }
  languageDropdownOpen.value = !languageDropdownOpen.value
}

// Функция для закрытия выпадающего списка языков при клике вне поля
function closeLanguageDropdown() {
  languageDropdownOpen.value = false
}

// Добавляем обработчик клика на документе при монтировании компонента
onMounted(() => {
  document.addEventListener('click', closeLanguageDropdown)
})

// Удаляем обработчик клика при размонтировании компонента
onUnmounted(() => {
  document.removeEventListener('click', closeLanguageDropdown)
})

// Список компаний (для демонстрации)
const companies = [
  { id: 1, name: 'ООО "Медицинский центр Здоровье"', inn: '7701234567', balance: 50000, freePostings: 2 },
  { id: 2, name: 'ЗАО "Клиника Новая Жизнь"', inn: '7702345678', balance: 15000, freePostings: 0 },
  { id: 3, name: 'ООО "Стоматология Люкс"', inn: '7703456789', balance: 3000, freePostings: 1 },
  { id: 4, name: 'ИП Иванов И.И.', inn: '770345678901', balance: 0, freePostings: 0 },
  { id: 5, name: 'ГБУЗ "Городская поликлиника № 1"', inn: '7704567890', balance: 100000, freePostings: 5 }
]

// Функции для работы с компаниями
function searchCompanies() {
  if (companySearchQuery.value.length < 2) {
    filteredCompanies.value = []
    return
  }
  
  const query = companySearchQuery.value.toLowerCase()
  filteredCompanies.value = companies.filter(company => {
    return company.name.toLowerCase().includes(query)
  })
}

function selectCompany(company) {
  form.value.companyId = company.id
  form.value.companyName = company.name
  form.value.contactInfo.inn = company.inn
  
  // Заполняем данные о балансе и бесплатных размещениях
  selectedCompanyData.value = {
    balance: company.balance,
    freePostings: company.freePostings
  }
  
  // Закрываем выпадающий список
  showCompanyDropdown.value = false
  companySearchQuery.value = company.name
  
  // Загружаем контактные данные компании (в реальном приложении загрузка с сервера)
  loadCompanyContactInfo(company.id)
}

// Загрузка контактных данных компании (для демонстрации)
function loadCompanyContactInfo(companyId) {
  // В реальном приложении здесь был бы API запрос
  const contactInfo = {
    1: { fullName: 'Иванов Иван Иванович', position: 'HR-менеджер', phone: '+7 (999) 123-45-67', email: 'hr@zdorovie.ru' },
    2: { fullName: 'Петрова Елена Сергеевна', position: 'Директор по персоналу', phone: '+7 (999) 234-56-78', email: 'petrova@newlife.ru' },
    3: { fullName: 'Сидоров Петр Александрович', position: 'Главный врач', phone: '+7 (999) 345-67-89', email: 'sidorov@dental-lux.ru' },
    4: { fullName: 'Иванов Игорь Игоревич', position: 'Индивидуальный предприниматель', phone: '+7 (999) 456-78-90', email: 'ivanov@mail.ru' },
    5: { fullName: 'Смирнова Ольга Викторовна', position: 'Начальник отдела кадров', phone: '+7 (999) 567-89-01', email: 'smirnova@clinic1.ru' }
  }
  
  if (contactInfo[companyId]) {
    form.value.contactInfo.fullName = contactInfo[companyId].fullName
    form.value.contactInfo.position = contactInfo[companyId].position
    form.value.contactInfo.phone = contactInfo[companyId].phone
    form.value.contactInfo.email = contactInfo[companyId].email
  }
}

// Инициализация формы при монтировании компонента
onMounted(() => {
  // Если это новая вакансия, инициализируем значения по умолчанию
  if (!form.value.id) {
    // Инициализируем массив языков, если он не существует
    if (!form.value.languages) {
      form.value.languages = []
    }
    
    // Устанавливаем статус по умолчанию
    if (!form.value.status) {
      form.value.status = 'moderation'
    }
    
    // Инициализируем объект контактных данных, если он не существует
    if (!form.value.contactInfo) {
      form.value.contactInfo = {
        fullName: '',
        position: '',
        organizationName: '',
        inn: '',
        phone: '',
        email: ''
      }
    }
    
    // Инициализируем услугу хантера
    form.value.hunterService = false
  } else if (form.value.companyId) {
    // Если это редактирование и компания уже выбрана
    const company = companies.find(c => c.id === form.value.companyId)
    if (company) {
      companySearchQuery.value = company.name
      selectedCompanyData.value = {
        balance: company.balance,
        freePostings: company.freePostings
      }
    }
  }
  
  // Закрываем выпадающие списки при клике вне их
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.company-dropdown')) {
      showCompanyDropdown.value = false
    }
    if (!e.target.closest('.city-dropdown')) {
      showCityDropdown.value = false
    }
    if (!e.target.closest('.district-dropdown')) {
      showDistrictDropdown.value = false
    }
  })
})

// Список городов (для демонстрации)
const cities = [
  { id: 1, name: 'Москва' },
  { id: 2, name: 'Санкт-Петербург' },
  { id: 3, name: 'Новосибирск' },
  { id: 4, name: 'Екатеринбург' },
  { id: 5, name: 'Казань' },
  { id: 6, name: 'Нижний Новгород' },
  { id: 7, name: 'Челябинск' },
  { id: 8, name: 'Самара' },
  { id: 9, name: 'Омск' },
  { id: 10, name: 'Ростов-на-Дону' }
]

// Список районов по городам (для демонстрации)
const districts = {
  1: [ // Москва
    { id: 1, name: 'Центральный' },
    { id: 2, name: 'Северный' },
    { id: 3, name: 'Северо-Восточный' },
    { id: 4, name: 'Восточный' },
    { id: 5, name: 'Юго-Восточный' },
    { id: 6, name: 'Южный' },
    { id: 7, name: 'Юго-Западный' },
    { id: 8, name: 'Западный' },
    { id: 9, name: 'Северо-Западный' },
    { id: 10, name: 'Зеленоградский' },
    { id: 11, name: 'Новомосковский' },
    { id: 12, name: 'Троицкий' }
  ],
  2: [ // Санкт-Петербург
    { id: 13, name: 'Адмиралтейский' },
    { id: 14, name: 'Василеостровский' },
    { id: 15, name: 'Выборгский' },
    { id: 16, name: 'Калининский' },
    { id: 17, name: 'Кировский' },
    { id: 18, name: 'Колпинский' },
    { id: 19, name: 'Красногвардейский' },
    { id: 20, name: 'Кронштадтский' }
  ],
  // Для остальных городов можно добавить свои районы
  3: [ { id: 21, name: 'Центральный' }, { id: 22, name: 'Ленинский' }, { id: 23, name: 'Октябрьский' } ],
  4: [ { id: 24, name: 'Центральный' }, { id: 25, name: 'Чкаловский' }, { id: 26, name: 'Орджоникидзевский' } ]
}

// Функции для работы с городами и районами
function searchCities() {
  if (citySearchQuery.value.length < 2) {
    filteredCities.value = []
    return
  }
  
  const query = citySearchQuery.value.toLowerCase()
  filteredCities.value = cities.filter(city => {
    return city.name.toLowerCase().includes(query)
  })
}

function selectCity(city) {
  form.value.city = city.name
  form.value.cityId = city.id
  form.value.district = ''
  form.value.districtId = null
  
  // Закрываем выпадающий список
  showCityDropdown.value = false
  citySearchQuery.value = city.name
  
  // Сбрасываем поиск района
  districtSearchQuery.value = ''
}

function searchDistricts() {
  if (!form.value.cityId || districtSearchQuery.value.length < 1) {
    filteredDistricts.value = []
    return
  }
  
  const cityDistricts = districts[form.value.cityId] || []
  if (districtSearchQuery.value.length === 0) {
    filteredDistricts.value = cityDistricts
    return
  }
  
  const query = districtSearchQuery.value.toLowerCase()
  filteredDistricts.value = cityDistricts.filter(district => {
    return district.name.toLowerCase().includes(query)
  })
}

function selectDistrict(district) {
  form.value.district = district.name
  form.value.districtId = district.id
  
  // Закрываем выпадающий список
  showDistrictDropdown.value = false
  districtSearchQuery.value = district.name
}

// Функции для работы с промокодами
function applyPromoCode() {
  if (!promoCode.value) {
    promoCodeMessage.value = 'Введите промокод'
    promoCodeValid.value = false
    discount.value = 0
    return
  }
  
  // В реальном приложении здесь был бы API запрос для проверки промокода
  // Для демонстрации проверяем несколько фиксированных промокодов
  const validPromoCodes = {
    'WELCOME': 1000,
    'NEWYEAR': 2000,
    'MEDIC': 1500,
    'HR2025': 3000
  }
  
  if (validPromoCodes[promoCode.value.toUpperCase()]) {
    discount.value = validPromoCodes[promoCode.value.toUpperCase()]
    promoCodeMessage.value = `Промокод применен! Скидка: ${discount.value} ₽`
    promoCodeValid.value = true
  } else {
    promoCodeMessage.value = 'Недействительный промокод'
    promoCodeValid.value = false
    discount.value = 0
  }
}

// Функция сохранения вакансии
function saveJob() {
  // Формируем заголовок вакансии на основе квалификации, если он не указан
  if (!form.value.title && form.value.qualification) {
    form.value.title = form.value.qualification
  }
  
  // Добавляем информацию о стоимости и способе оплаты
  form.value.pricing = {
    total: totalPrice.value,
    jobPostingPrice: jobPostingPrice.value,
    hunterServicePrice: form.value.hunterService ? hunterServicePrice.value : 0,
    discount: discount.value,
    promoCode: promoCodeValid.value ? promoCode.value : null,
    usedFreePosting: selectedCompanyData.value.freePostings > 0
  }
  
  // Добавляем дату создания и дату заказа хантера
  const now = new Date()
  form.value.createdAt = now.toISOString()
  if (form.value.hunterService) {
    form.value.hunterServiceOrderedAt = now.toISOString()
  }
  
  emit('save', form.value)
}
</script>
