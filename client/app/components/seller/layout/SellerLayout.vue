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
            <span>🔔</span>
            <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
              3
            </span>
          </button>

          <!-- Profile -->
          <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
              M
            </div>
            <div class="hidden md:block">
              <p class="text-sm font-semibold text-gray-800 leading-tight">Mang Bert</p>
              <p class="text-xs text-gray-400">Seller</p>
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

const isCollapsed = ref(false)

const handleCollapse = (collapsed: boolean) => {
  isCollapsed.value = collapsed
}
</script>