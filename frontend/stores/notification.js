import { defineStore } from 'pinia'

export const useNotification = defineStore('notification', {
  state: () => ({
    notifications: []
  }),

  getters: {
    hasNotifications: (state) => state.notifications.length > 0
  },

  actions: {
    show(message, type = 'info') {
      const id = Date.now()
      this.notifications.push({
        id,
        message,
        type,
      })

      // Автоматически удаляем уведомление через 3 секунды
      setTimeout(() => {
        this.remove(id)
      }, 3000)
    },

    remove(id) {
      const index = this.notifications.findIndex(n => n.id === id)
      if (index > -1) {
        this.notifications.splice(index, 1)
      }
    },

    success(message) {
      this.show(message, 'success')
    },

    error(message) {
      this.show(message, 'error')
    },

    warning(message) {
      this.show(message, 'warning')
    },

    info(message) {
      this.show(message, 'info')
    },

    clear() {
      this.notifications = []
    }
  }
}) 