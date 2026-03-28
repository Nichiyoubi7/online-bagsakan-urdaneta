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

    <!-- Admin Info -->
    <div v-if="!collapsed" class="px-4 py-4 border-b border-gray-700">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
          A
        </div>
        <div class="min-w-0">
          <p class="text-sm font-semibold text-white truncate">Super Admin</p>
          <p class="text-xs text-red-400">Administrator</p>
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
            $route.path === item.to
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
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white transition-colors"
      >
        <span class="text-lg">{{ collapsed ? '→' : '←' }}</span>
        <span v-if="!collapsed" class="text-sm font-medium">Collapse</span>
      </button>
      <button
        @click="navigateTo('/')"
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-red-500/20 hover:text-red-400 transition-colors mt-1"
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
    label: 'Overview',
    items: [
      { icon: '📊', label: 'Dashboard',  to: '/admin/dashboard', badge: null },
      { icon: '📦', label: 'Orders',     to: '/admin/orders',    badge: '12' },
    ],
  },
  {
    label: 'User Management',
    items: [
      { icon: '👥', label: 'All Users',  to: '/admin/users',    badge: null },
      { icon: '🏪', label: 'Sellers',    to: '/admin/sellers',  badge: '3'  },
      { icon: '🛵', label: 'Drivers',    to: '/admin/drivers',  badge: null },
    ],
  },
  {
    label: 'Catalog',
    items: [
      { icon: '🛍️', label: 'Products',   to: '/admin/products',   badge: null },
      { icon: '🏷️', label: 'Categories', to: '/admin/categories', badge: null },
    ],
  },
  {
    label: 'System',
    items: [
      { icon: '📈', label: 'Reports',   to: '/admin/reports',  badge: null },
      { icon: '⚙️', label: 'Settings',  to: '/admin/settings', badge: null },
    ],
  },
]
</script>