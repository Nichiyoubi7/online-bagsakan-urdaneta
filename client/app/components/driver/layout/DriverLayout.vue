<template>
  <div class="min-h-screen bg-gray-50 flex">

    <!-- Sidebar (desktop only) -->
    <aside :class="['fixed top-0 left-0 h-full bg-[#0f2d1f] flex-col z-40 transition-all duration-300 hidden md:flex', collapsed ? 'w-16' : 'w-64']">

      <!-- Brand -->
      <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
        <div class="w-9 h-9 bg-green-500 rounded-lg flex items-center justify-center font-bold text-white text-lg shrink-0">B</div>
        <div v-if="!collapsed">
          <div class="text-white font-semibold text-sm">Bagsakan</div>
          <div class="text-white/40 text-xs">Driver Panel</div>
        </div>
      </div>

      <!-- Nav -->
      <nav class="flex-1 px-3 py-4 overflow-y-auto">
        <NuxtLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          :class="[
            'flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/60 hover:bg-white/10 hover:text-white transition-all text-sm mb-1',
            $route.path.startsWith(item.to) ? 'bg-green-600 text-white' : ''
          ]"
        >
          <span class="text-lg shrink-0">{{ item.icon }}</span>
          <span v-if="!collapsed" class="font-medium">{{ item.label }}</span>
        </NuxtLink>
      </nav>

      <!-- Online Toggle -->
      <div v-if="!collapsed" class="px-4 py-3 border-t border-white/10">
        <div class="flex items-center justify-between">
          <span :class="['text-sm font-semibold', isOnline ? 'text-green-400' : 'text-gray-400']">
            {{ isOnline ? 'Online' : 'Offline' }}
          </span>
          <button
            @click="isOnline = !isOnline"
            :class="['relative w-11 h-6 rounded-full transition-colors duration-200', isOnline ? 'bg-green-500' : 'bg-gray-600']"
          >
            <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200', isOnline ? 'translate-x-5' : 'translate-x-0']" />
          </button>
        </div>
      </div>

      <!-- Collapse + Logout -->
      <div class="px-3 py-4 border-t border-white/10 flex flex-col gap-2">
        <button
          @click="collapsed = !collapsed"
          class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/40 hover:bg-white/10 hover:text-white transition-colors"
        >
          <span class="text-sm">{{ collapsed ? '→' : '←' }}</span>
          <span v-if="!collapsed" class="text-sm font-medium">Collapse</span>
        </button>
        <button
          @click="handleLogout"
          class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-red-500/20 hover:text-red-400 transition-colors"
        >
          <span class="text-lg">🚪</span>
          <span v-if="!collapsed" class="text-sm font-medium">Logout</span>
        </button>
      </div>

      <!-- User -->
      <div v-if="!collapsed" class="flex items-center gap-3 px-4 py-4 border-t border-white/10">
        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
          {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'D' }}
        </div>
        <div class="min-w-0">
          <div class="text-white text-xs font-medium truncate">{{ authStore.user?.name || 'Driver' }}</div>
          <div class="text-white/40 text-[11px] truncate">{{ authStore.user?.email }}</div>
        </div>
      </div>

    </aside>

    <!-- Main Content -->
    <div :class="['flex-1 flex flex-col transition-all duration-300', collapsed ? 'md:ml-16' : 'md:ml-64']">

      <!-- Top Bar -->
      <header class="bg-white border-b border-gray-200 px-4 md:px-6 py-4 flex items-center justify-between sticky top-0 z-30">
        <div>
          <h1 class="text-base md:text-lg font-black text-gray-800">Driver Dashboard</h1>
          <p class="text-xs text-gray-400 hidden md:block">Manage your deliveries</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- Online Toggle -->
          <div class="flex items-center gap-2">
            <span :class="['text-xs md:text-sm font-semibold', isOnline ? 'text-green-600' : 'text-gray-400']">
              {{ isOnline ? 'Online' : 'Offline' }}
            </span>
            <button
              @click="isOnline = !isOnline"
              :class="['relative w-11 h-6 rounded-full transition-colors duration-200', isOnline ? 'bg-green-500' : 'bg-gray-300']"
            >
              <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200', isOnline ? 'translate-x-5' : 'translate-x-0']" />
            </button>
          </div>
          <!-- Notification Bell -->
          <NotificationBell />
          <!-- Profile -->
          <div class="flex items-center gap-2">
            <div class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'D' }}
            </div>
            <div class="hidden md:block">
              <p class="text-sm font-semibold text-gray-800 leading-tight">{{ authStore.user?.name || 'Driver' }}</p>
              <p class="text-xs text-gray-400">Driver Account</p>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto pb-16 md:pb-0">
        <slot />
      </main>

      <!-- Bottom Nav (mobile only) -->
      <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 shadow-lg z-30">
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
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import NotificationBell from '~/components/shared/NotificationBell.vue'

const route = useRoute()
const authStore = useAuthStore()
const collapsed = ref(false)
const isOnline = ref(true)

const navItems = [
  { icon: '📊', label: 'Dashboard',  to: '/driver/dashboard'  },
  { icon: '🛵', label: 'Deliveries', to: '/driver/deliveries' },
  { icon: '💰', label: 'Earnings',   to: '/driver/earnings'   },
  { icon: '👤', label: 'Profile',    to: '/driver/profile'    },
]

const handleLogout = async () => {
  await authStore.logout()
  navigateTo('/')
}
</script>