<template>
  <div>
    <NuxtLayout>
      <AppHeader />
      <NuxtPage />
      <AppFooter />
      <NotificationContainer />
    </NuxtLayout>
  </div>
</template>

<script setup>
import AppHeader from '~/components/AppHeader.vue'
import AppFooter from '~/components/AppFooter.vue'
import NotificationContainer from '@/components/NotificationContainer.vue'
</script>