<template>
  <div class="fixed top-4 right-4 z-50 space-y-4">
    <div
      v-for="notification in notifications"
      :key="notification.id"
      :class="[
        'p-4 rounded-md shadow-lg max-w-sm transition-all transform',
        {
          'bg-green-50 text-green-800': notification.type === 'success',
          'bg-red-50 text-red-800': notification.type === 'error',
          'bg-blue-50 text-blue-800': notification.type === 'info'
        }
      ]"
    >
      {{ notification.message }}
    </div>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useNotification } from '~/stores/notification'

const store = useNotification()
const { notifications } = storeToRefs(store)
</script> 