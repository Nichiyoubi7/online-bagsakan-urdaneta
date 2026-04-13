<template>
  <aside
    :class="[
      'fixed top-0 left-0 h-full bg-gray-900 text-white transition-all duration-300 z-40 flex flex-col',
      collapsed ? 'w-16' : 'w-64'
    ]"
  >
    <!-- Logo -->
    <div class="flex items-center gap-3 px-4 py-5 border-b border-gray-700">
      <div class="w-8 h-8 bg-green-500 rounded-xl flex items-center justify-center shrink-0">
        <span class="text-white font-black text-sm">O</span>
      </div>
      <span v-if="!collapsed" class="font-black text-white text-lg">Bagsakan</span>
    </div>

    <!-- Seller Info -->
    <div v-if="!collapsed" class="px-4 py-4 border-b border-gray-700">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
          {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'S' }}
        </div>
        <div class="min-w-0">
          <div class="text-white font-semibold text-sm">{{ authStore.user?.store_name || authStore.user?.name || 'My Shop' }}</div>
          <div class="text-white/40 text-xs">Seller Account</div>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 overflow-y-auto">
      <div v-for="group in navGroups" :key="group.label" class="mb-6">
        <p v-if="!collapsed" class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">
          {{ group.label }}
        </p>
        <NuxtLink
          v-for="item in group.items"
          :key="item.to"
          :to="item.to"
          :class="[
            'flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200',
            $route.path.startsWith(item.to)
              ? 'bg-green-500 text-white'
              : 'text-gray-400 hover:bg-gray-800 hover:text-white'
          ]"
        >
          <span class="text-lg shrink-0">{{ item.icon }}</span>
          <span v-if="!collapsed" class="text-sm font-medium">{{ item.label }}</span>
          <span
            v-if="item.badge && !collapsed"
            class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
          >
            {{ item.badge }}
          </span>
        </NuxtLink>
      </div>
    </nav>

    <!-- Collapse + Logout -->
    <div class="px-2 py-4 border-t border-gray-700">
      <button
        @click="toggleCollapse"
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white transition-colors mb-1"
      >
        <span class="text-lg">{{ collapsed ? '→' : '←' }}</span>
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

  </aside>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const authStore = useAuthStore()
const collapsed = ref(false)

const emit = defineEmits<{
  (e: 'collapse', value: boolean): void
}>()

const toggleCollapse = () => {
  collapsed.value = !collapsed.value
  emit('collapse', collapsed.value)
}

const navGroups = [
  {
    label: 'Main',
    items: [
      { icon: '📊', label: 'Dashboard', to: '/seller/dashboard', badge: null },
      { icon: '📦', label: 'Orders',    to: '/seller/orders',    badge: null },
      { icon: '🛍️', label: 'Products',  to: '/seller/products',  badge: null },
    ],
  },
  {
    label: 'Management',
    items: [
      { icon: '💰', label: 'Revenue',  to: '/seller/revenue',  badge: null },
      { icon: '⭐', label: 'Reviews',  to: '/seller/reviews',  badge: null },
      { icon: '👤', label: 'Profile',  to: '/seller/profile', badge: null },
    ],
  },
  {
    label: 'Support',
    items: [
      { icon: '⚙️', label: 'Settings', to: '/seller/settings', badge: null },
      { icon: '❓', label: 'Help',     to: '/seller/help',     badge: null },
    ],
  },
]

const handleLogout = async () => {
  await authStore.logout()
  navigateTo('/')
}
</script>