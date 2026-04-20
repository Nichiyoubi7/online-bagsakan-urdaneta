<script setup>
import { onMounted } from 'vue'

useHead({
  meta: [
    { name: 'viewport', content: 'width=device-width, initial-scale=1' }
  ],
  link: [
    { rel: 'icon', href: '/favicon.ico' }
  ],
  htmlAttrs: {
    lang: 'en'
  }
})

useSeoMeta({
  title: 'OBRA',
  description: 'Fresh and healthy food delivery in Urdaneta City, Pangasinan.',
})

const authStore = useAuthStore()

// Load auth state from localStorage immediately on app start
onMounted(() => {
  authStore.loadFromStorage()
})
</script>

<template>
  <UApp>
    <NuxtPage />
  </UApp>
</template>