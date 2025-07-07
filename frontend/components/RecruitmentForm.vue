<template>
  <div class="bg-white rounded-lg shadow-lg p-8">
    <h3 class="text-2xl font-bold mb-8">Заявка на подбор специалиста</h3>

    <!-- Tabs for candidates -->
    <div class="mb-8">
      <div class="flex items-center gap-4 mb-4">
        <div v-for="(candidate, index) in candidates" :key="index" class="flex items-center">
          <button
            class="px-4 py-2 rounded-lg"
            :class="currentTab === index ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
            @click="currentTab = index"
          >
            Кандидат {{ index + 1 }}
          </button>
          <button 
            v-if="candidates.length > 1"
            @click="showDeleteConfirmation(index)"
            class="ml-1 p-1 text-red-500 hover:text-red-700 transition-colors"
            title="Удалить кандидата"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        <button 
          @click="addCandidate"
          class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors"
        >
          + Добавить кандидата
        </button>
      </div>

      <!-- Progress bar -->
      <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
        <div
          class="bg-blue-600 h-2 rounded-full transition-all duration-300"
          :style="{ width: getProgress(currentTab) + '%' }"
        ></div>
      </div>

      <!-- Candidate form -->
      <div v-for="(candidate, index) in candidates" :key="index" v-show="currentTab === index">
        <div class="grid grid-cols-2 gap-6">
          <!-- Type of specialist -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Тип специалиста *
            </label>
            <select 
              v-model="candidate.specialistType"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              @change="updateSpecializations(index)"
            >
              <option value="">Выберите тип специалиста</option>
              <option v-for="type in specialistTypes" :key="type" :value="type">
                {{ type }}
              </option>
            </select>
          </div>

          <!-- Specialization -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Специализация *
            </label>
            <select 
              v-model="candidate.specialization"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              @change="updateQualifications(index)"
              :disabled="!candidate.specialistType"
            >
              <option value="">Выберите специализацию</option>
              <option v-for="spec in getSpecializations(candidate.specialistType)" :key="spec" :value="spec">
                {{ spec }}
              </option>
            </select>
          </div>

          <!-- Qualification -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Квалификация *
            </label>
            <select 
              v-model="candidate.qualification"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :disabled="!candidate.specialization"
            >
              <option value="">Выберите квалификацию</option>
              <option v-for="qual in getQualifications(candidate.specialization)" :key="qual" :value="qual">
                {{ qual }}
              </option>
            </select>
          </div>

          <!-- Gender -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Пол
            </label>
            <select 
              v-model="candidate.gender"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Не имеет значения</option>
              <option value="male">Мужской</option>
              <option value="female">Женский</option>
            </select>
          </div>

          <!-- Category (for doctors and medical specialists) -->
          <div class="col-span-2 md:col-span-1" v-if="candidate.specialistType === 'Врач' || candidate.specialistType === 'Специалист со средним мед. образованием'">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Категория *
            </label>
            <select 
              v-model="candidate.category"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Выберите категорию</option>
              <option value="none">Без категории</option>
              <option value="second">Вторая категория</option>
              <option value="first">Первая категория</option>
              <option value="highest">Высшая категория</option>
            </select>
          </div>

          <!-- Experience -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Требуемый опыт *
            </label>
            <select 
              v-model="candidate.experience"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Выберите опыт работы</option>
              <option value="0">Без опыта</option>
              <option value="1">От 1 года</option>
              <option value="3">От 3 лет</option>
              <option value="5">От 5 лет</option>
              <option value="10">От 10 лет</option>
            </select>
          </div>

          <!-- Languages -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Знание языков
            </label>
            <div class="relative">
              <div 
                @click="toggleLanguageDropdown(index, $event)"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer flex items-center justify-between"
              >
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="lang in candidate.languages" 
                    :key="lang"
                    class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm"
                  >
                    {{ lang }}
                  </span>
                  <span v-if="candidate.languages.length === 0" class="text-gray-500">Выберите языки</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
              
              <!-- Dropdown menu -->
              <div 
                v-if="languageDropdownOpen[index]" 
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
                      v-model="candidate.languages" 
                      :value="lang"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="ml-2">{{ lang }}</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Start date -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Срок выхода *
            </label>
            <select 
              v-model="candidate.startDate"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Выберите срок</option>
              <option value="immediate">Немедленно</option>
              <option value="week">В течение недели</option>
              <option value="month">В течение месяца</option>
              <option value="quarter">В течение квартала</option>
            </select>
          </div>



          <!-- Certification -->
          <div class="col-span-2" v-if="candidate.specialistType === 'Врач' || candidate.specialistType === 'Специалист со средним мед. образованием'">
            <div class="flex items-center gap-2 mb-2">
              <label class="block text-sm font-medium text-gray-700">
                Наличие действующего сертификата/аккредитации
              </label>
              <span class="text-xs text-gray-500">(для врачей и среднего мед. персонала)</span>
            </div>
            <div class="flex items-center gap-6">
              <label class="inline-flex items-center">
                <input 
                  type="radio" 
                  v-model="candidate.certification" 
                  value="required"
                  class="text-blue-600 focus:ring-blue-500"
                >
                <span class="ml-2">Обязательно</span>
              </label>
              <label class="inline-flex items-center">
                <input 
                  type="radio" 
                  v-model="candidate.certification" 
                  value="not_required"
                  class="text-blue-600 focus:ring-blue-500"
                >
                <span class="ml-2">Не обязательно</span>
              </label>
            </div>
          </div>

          <!-- Training Payment -->
          <div class="col-span-2" v-if="candidate.specialistType === 'Врач' || candidate.specialistType === 'Специалист со средним мед. образованием'">
            <div class="flex items-center gap-2 mb-2">
              <label class="block text-sm font-medium text-gray-700">
                Готовность оплатить обучение/аккредитацию специалиста
              </label>
              <div class="relative">
                <div 
                  class="text-gray-400 hover:text-gray-600 cursor-help"
                  @mouseenter="showTrainingTooltip = true"
                  @mouseleave="showTrainingTooltip = false"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                  </svg>
                  <div 
                    v-show="showTrainingTooltip"
                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-72 p-2 bg-gray-900 text-white text-sm rounded shadow-lg"
                  >
                    Бывает что у потенциального кандидата нет действующего сертификата/аккредитации и возникает вопрос, готов ли потенциальный работодатель оплатить обучение будущего сотрудника. Чаще всего с предварительным заключением контракта
                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <label class="inline-flex items-center">
                <input 
                  type="radio"
                  v-model="candidate.trainingPayment"
                  value="ready"
                  class="text-blue-600 focus:ring-blue-500"
                >
                <span class="ml-2">Готовы</span>
              </label>
              <label class="inline-flex items-center">
                <input 
                  type="radio"
                  v-model="candidate.trainingPayment"
                  value="partially"
                  class="text-blue-600 focus:ring-blue-500"
                >
                <span class="ml-2">Частично готовы</span>
              </label>
              <label class="inline-flex items-center">
                <input 
                  type="radio"
                  v-model="candidate.trainingPayment"
                  value="not_ready"
                  class="text-blue-600 focus:ring-blue-500"
                >
                <span class="ml-2">Не готовы</span>
              </label>
            </div>
          </div>

          <!-- City -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Город *
            </label>
            <select 
              v-model="candidate.city"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              @change="updateDistricts(index)"
            >
              <option value="">Выберите город</option>
              <option v-for="city in cities" :key="city" :value="city">
                {{ city }}
              </option>
            </select>
          </div>

          <!-- District -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Район/Округ
            </label>
            <select 
              v-model="candidate.district"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :disabled="!candidate.city"
            >
              <option value="">Выберите район</option>
              <option v-for="district in getDistricts(candidate.city)" :key="district" :value="district">
                {{ district }}
              </option>
            </select>
          </div>

          <!-- Employment Type -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Тип занятости *
            </label>
            <select 
              v-model="candidate.employmentType"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Выберите тип занятости</option>
              <option v-for="empType in employmentTypes" :key="empType" :value="empType">
                {{ empType }}
              </option>
            </select>
          </div>

          <!-- Salary -->
          <div class="col-span-2 md:col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Оклад на руки в месяц (₽)
            </label>
            <input 
              type="number"
              v-model.number="candidate.salary"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Введите сумму"
              min="0"
            >
          </div>

          <!-- Job Description -->
          <div class="col-span-2">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Описание вакансии
                </label>
                <textarea
                  v-model="candidate.jobDescription"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Опишите вакансию..."
                ></textarea>
              </div>

              <!-- Requirements -->
              <div class="col-span-2">
                <div class="flex justify-between items-center mb-2">
                  <label class="block text-sm font-medium text-gray-700">
                    Требования к кандидату
                  </label>
                </div>
                
                <div v-for="(req, reqIndex) in candidate.requirementsList" :key="reqIndex" class="mb-2">
                  <div class="flex items-center gap-2">
                    <input 
                      type="text" 
                      v-model="candidate.requirementsList[reqIndex]" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Укажите требование"
                    />
                    <button 
                      v-if="candidate.requirementsList.length > 1"
                      @click="removeRequirement(index, reqIndex)" 
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
                  @click="addRequirement(index)" 
                  type="button"
                  class="mt-2 text-sm text-blue-600 hover:text-blue-800 flex items-center"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                  </svg>
                  Добавить требование
                </button>
              </div>

              <!-- Responsibilities -->
              <div class="col-span-2">
                <div class="flex justify-between items-center mb-2">
                  <label class="block text-sm font-medium text-gray-700">
                    Обязанности
                  </label>
                </div>
                
                <div v-for="(resp, respIndex) in candidate.responsibilitiesList" :key="respIndex" class="mb-2">
                  <div class="flex items-center gap-2">
                    <input 
                      type="text" 
                      v-model="candidate.responsibilitiesList[respIndex]" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Укажите обязанность"
                    />
                    <button 
                      v-if="candidate.responsibilitiesList.length > 1"
                      @click="removeResponsibility(index, respIndex)" 
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
                  @click="addResponsibility(index)" 
                  type="button"
                  class="mt-2 text-sm text-blue-600 hover:text-blue-800 flex items-center"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                  </svg>
                  Добавить обязанность
                </button>
              </div>

              <div>
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
                  v-model="candidate.uniqueRequirements"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Укажите уникальные требования..."
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Copy candidate button -->
          <div class="col-span-2 flex justify-between items-center">
            <div class="text-sm text-gray-600">
              Стоимость подбора: <span class="font-semibold">{{ formatCandidateCost(getCandidateCost(candidate)) }} ₽</span>
            </div>
            <button 
              @click="copyCandidate(index)"
              class="px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors"
            >
              📄 Копировать данные кандидата
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Information -->
    <div class="border-t pt-8 mb-8">
      <h4 class="text-xl font-semibold mb-6">Контактные данные</h4>
      <div class="grid grid-cols-2 gap-6">
        <!-- Full Name -->
        <div class="col-span-2 md:col-span-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            ФИО контактного лица *
          </label>
          <input 
            type="text"
            v-model="contactInfo.fullName"
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
            v-model="contactInfo.position"
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
            v-model="contactInfo.organizationName"
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
            v-model="contactInfo.inn"
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
            v-model="contactInfo.phone"
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
            v-model="contactInfo.email"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="example@domain.com"
          >
        </div>
        </div>
      </div>

    <!-- Cost calculation and promo code -->
    <div class="border-t pt-8">
      <div class="grid grid-cols-2 gap-6">
        <!-- Promo code -->
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
              @click="applyPromoCode"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Применить
            </button>
          </div>
        </div>

        <!-- Total cost -->
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

        <!-- Submit button -->
        <div class="col-span-2 flex justify-between items-center">
          <div class="flex items-center gap-2">
            <input 
              type="checkbox"
              v-model="consentChecked"
              class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
            <span class="text-sm text-gray-600">
              Отправляя заявку вы даете согласие на обработку ваших персональных данных и соглашаетесь с политикой конфиденциальности
            </span>
          </div>
          <button 
            @click="submitForm"
            class="px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-lg font-semibold"
            :disabled="!isFormValid || !consentChecked"
          >
          Отправить заявку
        </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Модальное окно подтверждения удаления -->
  <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
      <h3 class="text-xl font-bold mb-4">Подтверждение удаления</h3>
      <p class="mb-6">Вы уверены, что хотите удалить кандидата {{ candidateToDelete + 1 }}? Это действие нельзя отменить.</p>
      <div class="flex justify-end space-x-3">
        <button 
          @click="cancelDelete" 
          class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
        >
          Отмена
        </button>
        <button 
          @click="confirmDelete" 
          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
        >
          Удалить
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onUnmounted } from 'vue'

// Справочные данные
const specialistTypes = [
  'Врач',
  'Специалист со средним мед. образованием',
  'Администратор',
  'Специалист без мед. образования'
]

// Создание пустого кандидата
function createEmptyCandidate() {
  return {
    specialistType: '',
    specialization: '',
    qualification: '',
    category: '',
    gender: '',
    languages: [],
    experience: '',
    startDate: '',
    certification: 'required',
    trainingPayment: 'yes',
    city: '',
    district: '',
    employmentType: '',
    jobDescription: '',
    requirements: '',
    responsibilities: '',
    // Списки требований и обязанностей в виде пунктов
    requirementsList: [''],
    responsibilitiesList: [''],
    conditions: '',
    salary: ''
  }
}
const languages = ['Английский', 'Немецкий', 'Французский', 'Китайский']
const cities = ['Москва', 'Санкт-Петербург', 'Новосибирск']
const employmentTypes = ['Полная занятость', 'Частичная занятость', 'Проектная работа', 'Сменный график']
const showTrainingTooltip = ref(false)
const showUniqueRequirementsTooltip = ref(false)

// Specializations by specialist type
const specializationsByType = {
  'Врач': ['Терапевт', 'Хирург', 'Педиатр', 'Акушер-гинеколог', 'Анестезиолог-реаниматолог', 'Кардиолог', 'Невролог', 'Онколог', 'Офтальмолог', 'Рентгенолог', 'Стоматолог', 'Травматолог-ортопед', 'Уролог', 'Эндокринолог'],
  'Специалист со средним мед. образованием': ['Медицинская сестра процедурная', 'Медицинская сестра перевязочная', 'Медицинская сестра операционная', 'Медицинская сестра-анестезист', 'Фельдшер', 'Акушерка', 'Рентгенлаборант', 'Медицинский лабораторный техник'],
  'Административный персонал': ['Главный врач', 'Заместитель главного врача', 'Заведующий отделением', 'Старшая медицинская сестра', 'Главная медицинская сестра', 'Медицинский регистратор', 'Администратор']
}

// Qualifications by specialization
const qualificationsBySpec = {
  // Для врачей
  'Терапевт': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Хирург': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Педиатр': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Акушер-гинеколог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Анестезиолог-реаниматолог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Кардиолог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Невролог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Онколог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Офтальмолог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Рентгенолог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Стоматолог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Травматолог-ортопед': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Уролог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Эндокринолог': ['Врач', 'Врач второй категории', 'Врач первой категории', 'Врач высшей категории', 'Кандидат медицинских наук', 'Доктор медицинских наук'],

  // Для среднего медицинского персонала
  'Медицинская сестра процедурная': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Медицинская сестра перевязочная': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Медицинская сестра операционная': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Медицинская сестра-анестезист': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Фельдшер': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Акушерка': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Рентгенлаборант': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Медицинский лабораторный техник': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],

  // Для административного персонала
  'Главный врач': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Заместитель главного врача': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Заведующий отделением': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория', 'Кандидат медицинских наук', 'Доктор медицинских наук'],
  'Старшая медицинская сестра': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Главная медицинская сестра': ['Без категории', 'Вторая категория', 'Первая категория', 'Высшая категория'],
  'Медицинский регистратор': ['Без опыта', 'С опытом работы'],
  'Администратор': ['Без опыта', 'С опытом работы']
}

// Districts by city
const districtsByCity = {
  'Москва': ['Центральный', 'Северный', 'Южный', 'Западный', 'Восточный'],
  'Санкт-Петербург': ['Центральный', 'Адмиралтейский', 'Василеостровский'],
  'Новосибирск': ['Центральный', 'Железнодорожный', 'Заельцовский']
}

// Form state
const currentTab = ref(0)
const candidates = ref([createEmptyCandidate()])
const languageDropdownOpen = ref({})
const candidateToDelete = ref(-1)
const showDeleteModal = ref(false)
const contactInfo = ref({
  fullName: '',
  position: '',
  organizationName: '',
  inn: '',
  phone: '',
  email: ''
})
const promoCode = ref('')
const promoCodeApplied = ref(false)
const promoCodeDiscount = ref(0)
const consentChecked = ref(true)

// Helper functions

function getSpecializations(type) {
  return specializationsByType[type] || []
}

function getQualifications(spec) {
  return qualificationsBySpec[spec] || []
}

function getDistricts(city) {
  return districtsByCity[city] || []
}

function updateSpecializations(index) {
  candidates.value[index].specialization = ''
  candidates.value[index].qualification = ''
}

function updateQualifications(index) {
  candidates.value[index].qualification = ''
}

function updateDistricts(index) {
  candidates.value[index].district = ''
}

function addCandidate() {
  candidates.value.push(createEmptyCandidate())
  currentTab.value = candidates.value.length - 1
}

// Функции для удаления кандидата
function showDeleteConfirmation(index) {
  candidateToDelete.value = index
  showDeleteModal.value = true
}

function cancelDelete() {
  showDeleteModal.value = false
  candidateToDelete.value = -1
}

function confirmDelete() {
  if (candidateToDelete.value >= 0) {
    removeCandidate(candidateToDelete.value)
  }
  showDeleteModal.value = false
  candidateToDelete.value = -1
}

function removeCandidate(index) {
  if (candidates.value.length > 1) {
    candidates.value.splice(index, 1)
    if (currentTab.value >= candidates.value.length) {
      currentTab.value = candidates.value.length - 1
    }
  }
}

// Функции для работы с выпадающим списком языков
function toggleLanguageDropdown(index, event) {
  // Если есть событие, предотвращаем всплытие события
  if (event) {
    event.stopPropagation()
  }
  
  languageDropdownOpen.value = {
    ...languageDropdownOpen.value,
    [index]: !languageDropdownOpen.value[index]
  }
  
  // Закрываем другие выпадающие списки
  Object.keys(languageDropdownOpen.value).forEach(key => {
    if (Number(key) !== index && languageDropdownOpen.value[key]) {
      languageDropdownOpen.value[key] = false
    }
  })
}

// Функция для закрытия всех выпадающих списков языков при клике вне поля
function closeAllLanguageDropdowns() {
  Object.keys(languageDropdownOpen.value).forEach(key => {
    if (languageDropdownOpen.value[key]) {
      languageDropdownOpen.value[key] = false
    }
  })
}

// Добавляем обработчик клика на документе при монтировании компонента
onMounted(() => {
  document.addEventListener('click', closeAllLanguageDropdowns)
})

// Удаляем обработчик клика при размонтировании компонента
onUnmounted(() => {
  document.removeEventListener('click', closeAllLanguageDropdowns)
})

// Функции для работы с требованиями
function addRequirement(candidateIndex) {
  candidates.value[candidateIndex].requirementsList.push('')
}

function removeRequirement(candidateIndex, requirementIndex) {
  if (candidates.value[candidateIndex].requirementsList.length > 1) {
    candidates.value[candidateIndex].requirementsList.splice(requirementIndex, 1)
  }
}

// Функции для работы с обязанностями
function addResponsibility(candidateIndex) {
  candidates.value[candidateIndex].responsibilitiesList.push('')
}

function removeResponsibility(candidateIndex, responsibilityIndex) {
  if (candidates.value[candidateIndex].responsibilitiesList.length > 1) {
    candidates.value[candidateIndex].responsibilitiesList.splice(responsibilityIndex, 1)
  }
}

function copyCandidate(index) {
  const copy = JSON.parse(JSON.stringify(candidates.value[index]))
  candidates.value.push(copy)
  currentTab.value = candidates.value.length - 1
}

function getProgress(index) {
  const candidate = candidates.value[index]
  const requiredFields = ['specialistType', 'specialization', 'qualification', 'experience', 'startDate', 'city', 'employmentType']
  const filledFields = requiredFields.filter(field => candidate[field])
  return Math.round((filledFields.length / requiredFields.length) * 100)
}

// Cost calculation
const baseCost = 15000
const getCandidateCost = (candidate) => {
  let cost = baseCost
  
  // Add cost based on specialist type
  if (candidate.specialistType === 'Врач') cost *= 1.5
  
  // Add cost for languages
  cost += candidate.languages.length * 5000
  
  // Add cost for urgent start
  if (candidate.startDate === 'immediate') cost *= 1.2
  
  return cost
}

const totalCost = computed(() => {
  let cost = 0
  candidates.value.forEach(candidate => {
    cost += getCandidateCost(candidate)
  })
  
  // Apply promo code discount
  if (promoCodeApplied.value) {
    cost = cost * (1 - promoCodeDiscount.value / 100)
  }
  
  return cost
})

const formattedTotalCost = computed(() => {
  return new Intl.NumberFormat('ru-RU').format(Math.round(totalCost.value))
})

const formatCandidateCost = (cost) => {
  return new Intl.NumberFormat('ru-RU').format(Math.round(cost))
}

// Form validation
const isFormValid = computed(() => {
  // Check candidates
  const candidatesValid = candidates.value.every(candidate => {
    return candidate.specialistType &&
           candidate.specialization &&
           candidate.qualification &&
           candidate.experience &&
           candidate.startDate &&
           candidate.city &&
           candidate.employmentType &&
           candidate.jobDescription &&
           candidate.requirements &&
           candidate.responsibilities
  })
  
  // Check contact info
  const contactValid = contactInfo.value.fullName &&
                      contactInfo.value.position &&
                      contactInfo.value.organizationName &&
                      contactInfo.value.inn &&
                      contactInfo.value.phone &&
                      contactInfo.value.email
  
  return candidatesValid && contactValid && consentChecked.value
})

// Actions
function applyPromoCode() {
  if (promoCode.value === 'DISCOUNT10') {
    promoCodeApplied.value = true
    promoCodeDiscount.value = 10
  }
}

// Функция для проверки существования компании по ИНН
async function checkCompanyByInn(inn) {
  try {
    // В реальном приложении здесь был бы API-запрос на проверку ИНН
    // const response = await fetch(`http://localhost:8000/api/admin/companies/check-inn/${inn}`, {
    //   headers: {
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   }
    // })
    // const data = await response.json()
    // return data.company // возвращает компанию или null
    
    // Для демонстрации просто имитируем проверку
    // Для теста будем считать, что ИНН начинающиеся с "770" уже существуют
    if (inn.startsWith('770')) {
      return {
        id: 999,
        name: 'Тестовая компания',
        inn: inn,
        contact_name: 'Иванов Иван Иванович',
        position: 'Директор',
        phone: '+7 (495) 123-45-67',
        email: 'test@example.com'
      }
    }
    
    return null
  } catch (error) {
    console.error('Ошибка при проверке компании по ИНН:', error)
    return null
  }
}

// Функция для создания новой компании
async function createCompany(companyData) {
  try {
    // В реальном приложении здесь был бы API-запрос на создание компании
    // const response = await fetch('http://localhost:8000/api/admin/companies', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Authorization': `Bearer ${localStorage.getItem('token')}`
    //   },
    //   body: JSON.stringify(companyData)
    // })
    // const data = await response.json()
    // return data.company
    
    // Для демонстрации просто возвращаем данные компании с добавлением ID
    return {
      id: 1000,
      ...companyData,
      created_at: new Date().toISOString()
    }
  } catch (error) {
    console.error('Ошибка при создании компании:', error)
    return null
  }
}

async function submitForm() {
  if (!isFormValid.value) return
  
  try {
    // Проверяем, существует ли компания с таким ИНН
    let company = await checkCompanyByInn(contactInfo.value.inn)
    let companyId = null
    
    if (company) {
      // Если компания существует, используем её ID
      companyId = company.id
      console.log('Найдена существующая компания:', company)
    } else {
      // Если компании нет, создаем новую
      const newCompany = {
        name: contactInfo.value.organizationName,
        inn: contactInfo.value.inn,
        contact_name: contactInfo.value.fullName,
        position: contactInfo.value.position,
        phone: contactInfo.value.phone,
        email: contactInfo.value.email
      }
      
      company = await createCompany(newCompany)
      if (company) {
        companyId = company.id
        console.log('Создана новая компания:', company)
      }
    }
    
    // Создаем вакансии для каждого кандидата
    for (const candidate of candidates.value) {
      // Формируем данные вакансии
      const jobData = {
        title: `${candidate.qualification}`,
        company_id: companyId,
        company: { id: companyId, name: contactInfo.value.organizationName },
        specialty: { name: candidate.specialization },
        status: 'moderation', // Устанавливаем статус "Модерация"
        description: candidate.jobDescription,
        requirements: candidate.requirements,
        responsibilities: candidate.responsibilities,
        conditions: candidate.conditions,
        salary: candidate.salary,
        specialistType: candidate.specialistType,
        qualification: candidate.qualification,
        gender: candidate.gender,
        languages: candidate.languages,
        experience: candidate.experience,
        startDate: candidate.startDate,
        certification: candidate.certification,
        trainingPayment: candidate.trainingPayment,
        city: candidate.city,
        district: candidate.district,
        employmentType: candidate.employmentType,
        contactInfo: {
          fullName: contactInfo.value.fullName,
          position: contactInfo.value.position,
          organizationName: contactInfo.value.organizationName,
          inn: contactInfo.value.inn,
          phone: contactInfo.value.phone,
          email: contactInfo.value.email
        }
      }
      
      // Отправляем данные на сервер
      // В реальном приложении здесь будет запрос к API
      console.log('Creating job with data:', jobData)
      
      // Имитация отправки запроса на сервер
      // await fetch('http://localhost:8000/api/admin/jobs', {
      //   method: 'POST',
      //   headers: {
      //     'Content-Type': 'application/json',
      //     'Authorization': `Bearer ${localStorage.getItem('token')}`
      //   },
      //   body: JSON.stringify(jobData)
      // })
    }
    
    // Отправляем данные о заказчике
    console.log('Form submitted', {
      candidates: candidates.value,
      contactInfo: contactInfo.value,
      company: company,
      totalCost: totalCost.value
    })
    
    // Показываем успешное сообщение
    alert(`Заявка успешно отправлена! ${company ? 'Использована ' + (company.id === 1000 ? 'новая' : 'существующая') + ' компания: ' + company.name : ''}`)
    
    // Сбрасываем форму
    resetForm()
  } catch (error) {
    console.error('Error submitting form:', error)
    alert('Произошла ошибка при отправке формы. Пожалуйста, попробуйте еще раз.')
  }
}

// Сброс формы
function resetForm() {
  candidates.value = [createEmptyCandidate()]
  currentTab.value = 0
  contactInfo.value = {
    fullName: '',
    position: '',
    organizationName: '',
    inn: '',
    phone: '',
    email: ''
  }
  promoCode.value = ''
  promoCodeApplied.value = false
  consentChecked.value = false
}
</script>