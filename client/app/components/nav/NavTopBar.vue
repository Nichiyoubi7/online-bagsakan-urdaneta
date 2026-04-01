<template>
  <div class="bg-green-600 text-white text-xs py-2.5">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-center gap-4">

      <!-- Left: Sign up as Seller -->
      <button
        @click="$emit('open-auth')"
        class="flex items-center gap-2 border border-white/60 hover:border-white hover:bg-white/10 text-white font-semibold px-4 py-1.5 rounded-full transition-all duration-200"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        SIGN UP AS A SELLER PARTNER
      </button>

      <!-- Divider -->
      <span class="text-white/40">|</span>

      <!-- Right: Sign up as Driver -->
      <button
        @click="$emit('open-auth')"
        class="flex items-center gap-2 border border-white/60 hover:border-white hover:bg-white/10 text-white font-semibold px-4 py-1.5 rounded-full transition-all duration-200"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        SIGN UP AS A DELIVERY DRIVER
      </button>

    </div>
  </div>
</template>

<script setup lang="ts">
defineEmits<{ (e: 'open-auth'): void }>()
</script>