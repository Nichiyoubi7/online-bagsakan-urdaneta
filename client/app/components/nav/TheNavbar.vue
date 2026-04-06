<template>
  <header class="sticky top-0 z-50 w-full shadow-sm">
    <NavTopBar @open-auth="showAuthModal = true" />
    <NavMainBar @open-auth="showAuthModal = true" />
    <NavMenuBar />
    <AuthModal v-model="showAuthModal" />
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import NavTopBar from '~/components/nav/NavTopBar.vue'
import NavMainBar from '~/components/nav/NavMainBar.vue'
import NavMenuBar from '~/components/nav/NavMenuBar.vue'
import AuthModal from '~/components/auth/AuthModal.vue'

const showAuthModal = ref(false)
</script>