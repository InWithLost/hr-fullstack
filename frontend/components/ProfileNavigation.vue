<template>
  <div class="mb-6 flex border-b">
    <NuxtLink
      to="/profile"
      :class="[
        'py-2 px-4 -mb-px border-b-2 font-medium focus:outline-none',
        currentPage === 'profile' 
          ? 'border-primary-600 text-primary-600' 
          : 'border-transparent text-gray-500 hover:text-gray-700'
      ]"
    >
      Профиль
    </NuxtLink>
    <NuxtLink
      to="/profile/anketa"
      :class="[
        'py-2 px-4 -mb-px border-b-2 font-medium focus:outline-none',
        currentPage === 'anketa' 
          ? 'border-primary-600 text-primary-600' 
          : 'border-transparent text-gray-500 hover:text-gray-700'
      ]"
    >
      Стаж
    </NuxtLink>
    <NuxtLink
      to="/profile/education"
      :class="[
        'py-2 px-4 -mb-px border-b-2 font-medium focus:outline-none',
        currentPage === 'education' 
          ? 'border-primary-600 text-primary-600' 
          : 'border-transparent text-gray-500 hover:text-gray-700'
      ]"
    >
      Образование
    </NuxtLink>
    <NuxtLink
      to="/profile/applications/"
      :class="[
        'py-2 px-4 -mb-px border-b-2 font-medium focus:outline-none',
        currentPage === 'applications' 
          ? 'border-primary-600 text-primary-600' 
          : 'border-transparent text-gray-500 hover:text-gray-700'
      ]"
    >
      Мои отклики
    </NuxtLink>
  </div>
</template>

<script setup>
const props = defineProps({
  currentPage: {
    type: String,
    required: true,
    validator: (value) => ['profile', 'anketa', 'education', 'applications'].includes(value)
  }
})
</script>
