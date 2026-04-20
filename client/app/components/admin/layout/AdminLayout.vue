<template>
  <div class="min-h-screen bg-gray-50 flex">

    <!-- Mobile Overlay -->
    <Transition name="fade">
      <div
        v-if="mobileOpen"
        class="fixed inset-0 bg-black/50 z-30 md:hidden"
        @click="mobileOpen = false"
      />
    </Transition>

    <AdminSidebar
      :mobile-open="mobileOpen"
      @collapse="handleCollapse"
      @close="mobileOpen = false"
    />

    <div
      :class="[
        'flex-1 flex flex-col transition-all duration-300 min-w-0',
        'ml-0',
        isCollapsed ? 'md:ml-16' : 'md:ml-64'
      ]"
    >
      <!-- Top Bar -->
      <header class="bg-white border-b border-gray-200 px-4 md:px-6 py-4 flex items-center justify-between sticky top-0 z-20">

        <!-- Left: Hamburger + Title -->
        <div class="flex items-center gap-3">
          <button
            class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 transition-colors"
            @click="mobileOpen = true"
          >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
          <div>
            <h1 class="text-base md:text-lg font-black text-gray-800">{{ title }}</h1>
            <p v-if="subtitle" class="text-xs text-gray-400 hidden md:block">{{ subtitle }}</p>
          </div>
        </div>

        <!-- Right -->
        <div class="flex items-center gap-2 md:gap-4">
          <!-- Search - desktop only -->
          <div class="hidden md:flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input
              type="text"
              placeholder="Search..."
              class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-40"
            />
          </div>

          <!-- Notifications -->
          <button class="relative w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
          </button>

          <!-- Admin Profile -->
          <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'A' }}
            </div>
            <div class="hidden md:block">
              <p class="text-sm font-semibold text-gray-800 leading-tight">{{ authStore.user?.name || 'Admin' }}</p>
              <p class="text-xs text-gray-400">Administrator</p>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-4 md:p-6">
        <slot />
      </main>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import AdminSidebar from './AdminSidebar.vue'

defineProps<{
  title: string
  subtitle?: string
}>()

const authStore = useAuthStore()
const isCollapsed = ref(false)
const mobileOpen = ref(false)
const handleCollapse = (val: boolean) => { isCollapsed.value = val }
</script>