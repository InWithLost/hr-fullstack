<template>
  <div class="fixed top-0 right-0 p-6 z-50">
    <TransitionGroup 
      name="notifications" 
      tag="div"
      class="space-y-2"
    >
      <div
        v-for="notification in notifications"
        :key="notification.id"
        :class="[
          'p-4 rounded-lg shadow-lg max-w-sm',
          {
            'bg-green-500 text-white': notification.type === 'success',
            'bg-red-500 text-white': notification.type === 'error',
            'bg-yellow-500 text-white': notification.type === 'warning',
            'bg-blue-500 text-white': notification.type === 'info'
          }
        ]"
      >
        {{ notification.message }}
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useNotification } from '~/stores/notification'

const store = useNotification()
const { notifications } = storeToRefs(store)
</script>

<style scoped>
.notifications-enter-active,
.notifications-leave-active {
  transition: all 0.3s ease;
}

.notifications-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.notifications-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style> 