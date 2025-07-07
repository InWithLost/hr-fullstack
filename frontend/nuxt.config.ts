// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  devtools: { enabled: true },

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt'
  ],



  css: ['~/assets/css/main.css'],

  typescript: {
    strict: true,
    typeCheck: true
  },

  build: {
    transpile: ['pinia']
  },

  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000'
    }
  },

  nitro: {
    devProxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true
      },
      '/sanctum/csrf-cookie': {
        target: 'http://localhost:8000',
        changeOrigin: true
      }
    }
  },

  compatibilityDate: '2025-04-26',
  
  // Явная конфигурация маршрутизации
  pages: true,
  app: {
    pageTransition: false,
    layoutTransition: false
  }
})