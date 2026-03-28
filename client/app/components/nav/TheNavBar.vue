<template>
  <header class="sticky top-0 z-50 w-full shadow-sm">
    <NavTopBar />
    <NavMainBar />
    <NavMenuBar />
  </header>
</template>

<script setup lang="ts">
import NavTopBar from '~/components/nav/NavTopBar.vue'
import NavMainBar from '~/components/nav/NavMainBar.vue'
import NavMenuBar from '~/components/nav/NavMenuBar.vue'
</script>