<template>
  <div class="min-h-screen flex flex-col bg-white">
    <TheNavbar />
    <main class="flex-1">
      <slot />
    </main>
    <TheFooter />
  </div>
</template>

<script setup lang="ts">
import TheNavbar from '~/components/nav/TheNavbar.vue'
import TheFooter from '~/components/TheFooter.vue'
</script>