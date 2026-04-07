<template>
  <div class="min-h-screen bg-gray-50 flex flex-col max-w-md mx-auto relative">

    <!-- Header -->
    <header class="bg-white border-b border-gray-100 px-4 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 bg-green-500 rounded-xl flex items-center justify-center">
          <span class="text-white font-black text-sm">O</span>
        </div>
        <div>
          <p class="text-sm font-black text-gray-800">Bagsakan</p>
          <p class="text-xs text-gray-400">Driver App</p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold" :class="isOnline ? 'text-green-600' : 'text-gray-400'">
            {{ isOnline ? 'Online' : 'Offline' }}
          </span>
          <button
            @click="isOnline = !isOnline"
            :class="['relative w-11 h-6 rounded-full transition-colors duration-200', isOnline ? 'bg-green-500' : 'bg-gray-300']"
          >
            <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200', isOnline ? 'translate-x-5' : 'translate-x-0']" />
          </button>
        </div>
        <button class="relative w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
        </button>
      </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 pb-20 overflow-y-auto">
      <slot />
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-gray-100 shadow-lg z-30">
      <div class="flex items-center justify-around px-2 py-2">
        <NuxtLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-colors"
          :class="$route.path.startsWith(item.to) ? 'text-green-500' : 'text-gray-400'"
        >
          <span class="text-xl">{{ item.icon }}</span>
          <span class="text-[10px] font-semibold">{{ item.label }}</span>
        </NuxtLink>
      </div>
    </nav>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const isOnline = ref(true)

const navItems = [
  { icon: '🏠', label: 'Home',       to: '/driver/dashboard'  },
  { icon: '🛵', label: 'Deliveries', to: '/driver/deliveries' },
  { icon: '💰', label: 'Earnings',   to: '/driver/earnings'   },
  { icon: '👤', label: 'Profile',    to: '/driver/profile'    },
]
</script>