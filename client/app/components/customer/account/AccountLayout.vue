<template>
  <GuestLayout>

    <!-- Page Banner -->
    <div class="bg-gray-800 relative overflow-hidden">
      <img src="/images/banners/2.png" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40" />
      <div class="relative max-w-7xl mx-auto px-6 py-10">
        <div class="flex items-center gap-2 text-sm text-gray-300 mb-1">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-gray-400">Account</span>
          <span>/</span>
          <span class="text-green-400 font-semibold">{{ pageTitle }}</span>
        </div>
        <h1 class="text-white text-2xl font-black">{{ pageTitle }}</h1>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-10">
      <div class="flex flex-col md:flex-row gap-6">

        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 shrink-0">
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
              <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Navigation</p>
            </div>
            <nav class="flex flex-col py-2">
              <NuxtLink
                v-for="item in navItems"
                :key="item.to"
                :to="item.to"
                :class="[
                  'flex items-center gap-3 px-5 py-3 text-sm transition-colors border-l-4',
                  $route.path === item.to
                    ? 'border-green-500 bg-green-50 text-green-600 font-semibold'
                    : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-800'
                ]"
              >
                <span v-html="item.icon" class="w-4 h-4 shrink-0" />
                {{ item.label }}
              </NuxtLink>

              <!-- Logout -->
              <button
                @click="handleLogout"
                class="flex items-center gap-3 px-5 py-3 text-sm text-gray-600 hover:bg-red-50 hover:text-red-500 transition-colors border-l-4 border-transparent w-full text-left mt-1"
              >
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Log-out
              </button>
            </nav>
          </div>
        </aside>

        <!-- Page Content -->
        <div class="flex-1 min-w-0">
          <slot />
        </div>

      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import GuestLayout from '../../layout/GuestLayout.vue'

defineProps<{ pageTitle: string }>()

const authStore = useAuthStore()

const navItems = [
  {
    label: 'Dashboard',
    to: '/customer/account',
    icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>`,
  },
  {
    label: 'Order History',
    to: '/customer/account/orders',
    icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
  },
  {
    label: 'Shopping Cart',
    to: '/customer/cart',
    icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/></svg>`,
  },
  {
    label: 'Settings',
    to: '/customer/account/settings',
    icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>`,
  },
]

const handleLogout = async () => {
  await authStore.logout()
  navigateTo('/')
}
</script>