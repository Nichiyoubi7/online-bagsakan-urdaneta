<template>
  <AdminLayout title="Dashboard" subtitle="Welcome back, Admin!">

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-6 md:mb-8">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3"
        :style="{ backgroundColor: stat.bgColor }"
      >
        <span class="text-2xl">{{ stat.icon }}</span>
        <div>
          <p class="text-lg md:text-xl font-black text-gray-800">{{ stat.value }}</p>
          <p class="text-xs text-gray-500">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 md:mb-8">

      <!-- Platform Overview -->
      <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-base font-black text-gray-800 mb-4">Platform Overview</h3>
        <div v-if="loading" class="flex flex-col gap-3">
          <div v-for="n in 5" :key="n" class="h-6 bg-gray-100 rounded animate-pulse" />
        </div>
        <div v-else class="flex flex-col gap-3">
          <div v-for="item in platformStats" :key="item.label">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs text-gray-500">{{ item.label }}</span>
              <span class="text-xs font-semibold text-gray-800">{{ item.value }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
              <div class="h-2 rounded-full transition-all duration-500"
                :style="{ width: item.percent + '%', backgroundColor: item.color }" />
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-base font-black text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <NuxtLink
            v-for="action in quickActions"
            :key="action.label"
            :to="action.to"
            class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 hover:border-green-400 hover:shadow-md transition-all duration-200 group"
          >
            <span class="text-3xl">{{ action.icon }}</span>
            <span class="text-xs font-semibold text-gray-600 group-hover:text-green-600 text-center transition-colors">
              {{ action.label }}
            </span>
          </NuxtLink>
        </div>
      </div>

    </div>

    <!-- Recent Orders -->
    <div class="mb-6 md:mb-8">
      <AdminRecentOrders :orders="recentOrders" :loading="loading" />
    </div>

    <!-- Bottom Row: Top Products + Users -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Top Products from Orders -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Top Products</h3>
          <NuxtLink to="/admin/products" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
        </div>
        <div class="p-4 flex flex-col gap-3">
          <div v-if="loading" v-for="n in 5" :key="n" class="h-8 bg-gray-100 rounded-lg animate-pulse" />
          <div v-else-if="topProducts.length === 0" class="text-center py-6 text-sm text-gray-400">No data yet</div>
          <div v-else v-for="(product, index) in topProducts" :key="product.name" class="flex items-center gap-3">
            <span :class="[
              'w-7 h-7 rounded-full flex items-center justify-center text-xs font-black shrink-0 text-white',
              index === 0 ? 'bg-yellow-400' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-orange-400' : 'bg-gray-200'
            ]">{{ index + 1 }}</span>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ product.name }}</p>
              <p class="text-xs text-gray-400">{{ product.sold }} sold</p>
            </div>
            <p class="text-sm font-bold text-green-600 shrink-0">₱{{ Number(product.revenue).toFixed(0) }}</p>
          </div>
        </div>
      </div>

      <!-- Recent Users -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Recent Users</h3>
          <NuxtLink to="/admin/users" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
        </div>
        <div class="p-4 flex flex-col gap-3">
          <div v-if="loadingUsers" v-for="n in 5" :key="n" class="h-10 bg-gray-100 rounded-lg animate-pulse" />
          <div v-else-if="recentUsers.length === 0" class="text-center py-6 text-sm text-gray-400">No users yet</div>
          <div v-else v-for="user in recentUsers" :key="user.id" class="flex items-center gap-3">
            <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
              {{ user.name?.charAt(0) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ user.name }}</p>
              <p class="text-xs text-gray-400 truncate">{{ user.email }}</p>
            </div>
            <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full capitalize shrink-0">
              {{ user.roles?.[0]?.name || 'user' }}
            </span>
          </div>
        </div>
      </div>

    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'
import AdminRecentOrders from '../../../components/admin/dashboard/AdminRecentOrders.vue'

const { get } = useApi()

const loading = ref(true)
const loadingUsers = ref(true)
const allOrders = ref<any[]>([])
const recentOrders = ref<any[]>([])
const recentUsers = ref<any[]>([])

// --- Stats computed from real data ---
const stats = computed(() => [
  {
    icon: '💰',
    label: 'Total Revenue',
    value: '₱' + allOrders.value
      .filter(o => o.status === 'delivered')
      .reduce((s: number, o: any) => s + Number(o.total), 0)
      .toLocaleString(),
    bgColor: '#dcfce7',
  },
  {
    icon: '📦',
    label: 'Total Orders',
    value: allOrders.value.length.toLocaleString(),
    bgColor: '#dbeafe',
  },
  {
    icon: '🕐',
    label: 'Pending Orders',
    value: String(allOrders.value.filter(o => o.status === 'pending').length),
    bgColor: '#fef9c3',
  },
  {
    icon: '✅',
    label: 'Delivered',
    value: String(allOrders.value.filter(o => o.status === 'delivered').length),
    bgColor: '#fce7f3',
  },
])

const platformStats = computed(() => {
  const total = allOrders.value.length || 1
  const delivered = allOrders.value.filter(o => o.status === 'delivered').length
  const pending = allOrders.value.filter(o => o.status === 'pending').length
  const cancelled = allOrders.value.filter(o => o.status === 'cancelled').length
  return [
    { label: 'Orders Completed', value: String(delivered), percent: Math.round(delivered / total * 100), color: '#22c55e' },
    { label: 'Orders Pending',   value: String(pending),   percent: Math.round(pending / total * 100),   color: '#f59e0b' },
    { label: 'Orders Cancelled', value: String(cancelled), percent: Math.round(cancelled / total * 100), color: '#ef4444' },
    { label: 'Total Users',      value: String(recentUsers.value.length), percent: 75, color: '#3b82f6' },
  ]
})

const topProducts = computed(() => {
  const map: Record<string, { name: string; sold: number; revenue: number }> = {}
  allOrders.value.forEach((order: any) => {
    order.items?.forEach((item: any) => {
      if (!map[item.product_name]) map[item.product_name] = { name: item.product_name, sold: 0, revenue: 0 }
      map[item.product_name].sold += item.quantity
      map[item.product_name].revenue += Number(item.subtotal)
    })
  })
  return Object.values(map).sort((a, b) => b.sold - a.sold).slice(0, 5)
})

const quickActions = [
  { icon: '👥', label: 'Manage Users',   to: '/admin/users'    },
  { icon: '🏪', label: 'Manage Sellers', to: '/admin/sellers'  },
  { icon: '📦', label: 'View Orders',    to: '/admin/orders'   },
  { icon: '🛍️', label: 'Products',       to: '/admin/products' },
]

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders')
    allOrders.value = res.data || []
    recentOrders.value = (res.data || []).slice(0, 5)
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
}

const loadUsers = async () => {
  loadingUsers.value = true
  try {
    const res: any = await get('/users')
    recentUsers.value = (res.data || []).slice(0, 5)
  } catch (e) {
    console.error('Failed to load users', e)
  } finally {
    loadingUsers.value = false
  }
}

onMounted(async () => {
  await Promise.all([loadOrders(), loadUsers()])
})
</script>