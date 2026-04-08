<template>
  <div class="min-h-screen bg-gray-50 flex">

    <!-- Sidebar -->
    <SellerSidebar @collapse="handleCollapse" />

    <!-- Main Content -->
    <div
      :class="[
        'flex-1 flex flex-col transition-all duration-300 min-w-0',
        isCollapsed ? 'ml-16' : 'ml-64'
      ]"
    >

      <!-- Top Bar -->
      <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30">

        <!-- Page Title -->
        <div>
          <h1 class="text-lg font-black text-gray-800">{{ title }}</h1>
          <p v-if="subtitle" class="text-xs text-gray-400">{{ subtitle }}</p>
        </div>

        <!-- Right: Notifications + Profile -->
        <div class="flex items-center gap-4">

          <!-- Notifications -->
          <button class="relative w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
          </button>

          <!-- Profile -->
          <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'S' }}
            </div>
            <div class="hidden md:block">
              <p class="text-sm font-semibold text-gray-800 leading-tight">{{ authStore.user?.name || 'Seller' }}</p>
              <p class="text-xs text-gray-400">Seller Account</p>
            </div>
          </div>

        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6">
        <slot />
      </main>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import SellerSidebar from './SellerSidebar.vue'

defineProps<{
  title: string
  subtitle?: string
}>()

const authStore = useAuthStore()
const isCollapsed = ref(false)

const handleCollapse = (collapsed: boolean) => {
  isCollapsed.value = collapsed
}
</script>