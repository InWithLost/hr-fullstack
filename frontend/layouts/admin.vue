<template>
  <div class="min-h-screen bg-gray-100">
    <slot />
    <NotificationContainer />
  </div>
</template>

<script setup>
import NotificationContainer from '~/components/NotificationContainer.vue'
</script>
