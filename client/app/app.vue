<script setup>
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
</script>

<template>
  <UApp>
    <NuxtPage />
  </UApp>
</template>