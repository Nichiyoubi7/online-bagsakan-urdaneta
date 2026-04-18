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
          <p class="text-lg md:text-xl font-black text-gray-800">{{ loading ? '...' : stat.value }}</p>
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
          <div v-for="n in 4" :key="n" class="h-6 bg-gray-100 rounded animate-pulse" />
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

    <!-- Delivery Performance -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 md:mb-8">
      <h3 class="text-base font-black text-gray-800 mb-4">Delivery Performance</h3>
      <div v-if="loading" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="n in 4" :key="n" class="h-20 bg-gray-100 rounded-xl animate-pulse" />
      </div>
      <div v-else>
        <!-- Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
          <div
            v-for="metric in deliveryMetrics"
            :key="metric.label"
            class="rounded-xl p-4 flex flex-col gap-1"
            :style="{ backgroundColor: metric.bg }"
          >
            <span class="text-2xl">{{ metric.icon }}</span>
            <p class="text-xl font-black text-gray-800">{{ metric.value }}</p>
            <p class="text-xs text-gray-500">{{ metric.label }}</p>
          </div>
        </div>

        <!-- Top Drivers -->
        <div>
          <p class="text-sm font-bold text-gray-700 mb-3">Top Drivers</p>
          <div v-if="topDrivers.length === 0" class="text-sm text-gray-400 py-4 text-center">
            No completed deliveries yet.
          </div>
          <div v-else class="flex flex-col gap-2">
            <div
              v-for="(driver, index) in topDrivers"
              :key="driver.name"
              class="flex items-center gap-3 p-3 rounded-xl bg-gray-50"
            >
              <span :class="[
                'w-7 h-7 rounded-full flex items-center justify-center text-xs font-black shrink-0',
                index === 0 ? 'bg-yellow-400 text-white' :
                index === 1 ? 'bg-gray-300 text-gray-700' :
                index === 2 ? 'bg-orange-400 text-white' : 'bg-gray-100 text-gray-500'
              ]">{{ index + 1 }}</span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ driver.name }}</p>
                <p class="text-xs text-gray-400">{{ driver.deliveries }} deliveries</p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-bold text-green-600">₱{{ driver.earnings.toLocaleString() }}</p>
                <p class="text-xs text-gray-400">earned</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="mb-6 md:mb-8">
      <AdminRecentOrders :orders="recentOrders" :loading="loading" />
    </div>

    <!-- Bottom Row: Top Products + Recent Users -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Top Products -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Top Products</h3>
          <NuxtLink to="/admin/products" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
        </div>
        <div class="p-4 flex flex-col gap-3">
          <div v-if="loading" v-for="n in 5" :key="n" class="h-8 bg-gray-100 rounded-lg animate-pulse" />
          <div v-else-if="topProducts.length === 0" class="text-center py-4 text-sm text-gray-400">No data yet</div>
          <div v-else v-for="(product, index) in topProducts" :key="product.name" class="flex items-center gap-3">
            <span :class="[
              'w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0',
              index === 0 ? 'bg-yellow-400 text-white' :
              index === 1 ? 'bg-gray-300 text-gray-700' :
              index === 2 ? 'bg-orange-400 text-white' : 'bg-gray-100 text-gray-500'
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
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Recent Users</h3>
          <NuxtLink to="/admin/users" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
        </div>
        <div class="p-4 flex flex-col gap-3">
          <div v-if="loadingUsers" v-for="n in 5" :key="n" class="h-10 bg-gray-100 rounded-xl animate-pulse" />
          <div v-else-if="recentUsers.length === 0" class="text-center py-4 text-sm text-gray-400">No users yet</div>
          <div v-else v-for="user in recentUsers" :key="user.id" class="flex items-center gap-3">
            <div
              class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-black shrink-0"
              :style="{ backgroundColor: avatarColor(user.name) }"
            >
              {{ user.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ user.name }}</p>
              <p class="text-xs text-gray-400 truncate">{{ user.email }}</p>
            </div>
            <span :class="['text-[10px] font-bold px-2 py-0.5 rounded-full capitalize', roleClass(user.roles?.[0]?.name)]">
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

const loading        = ref(true)
const loadingUsers   = ref(true)
const allOrders      = ref<any[]>([])
const recentOrders   = ref<any[]>([])
const recentUsers    = ref<any[]>([])
const allUsers       = ref<any[]>([])
const totalProducts  = ref(0)

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
    icon: '👥',
    label: 'Total Users',
    value: allUsers.value.length.toLocaleString(),
    bgColor: '#fef9c3',
  },
  {
    icon: '🛍️',
    label: 'Total Products',
    value: totalProducts.value.toLocaleString(),
    bgColor: '#fce7f3',
  },
])

const platformStats = computed(() => {
  const total     = allOrders.value.length || 1
  const delivered = allOrders.value.filter(o => o.status === 'delivered').length
  const pending   = allOrders.value.filter(o => o.status === 'pending').length
  const cancelled = allOrders.value.filter(o => o.status === 'cancelled').length
  const totalU    = allUsers.value.length
  const customers = allUsers.value.filter(u => u.roles?.[0]?.name === 'customer').length
  return [
    { label: 'Orders Completed', value: String(delivered),  percent: Math.round(delivered / total * 100),                         color: '#22c55e' },
    { label: 'Orders Pending',   value: String(pending),    percent: Math.round(pending / total * 100),                           color: '#f59e0b' },
    { label: 'Orders Cancelled', value: String(cancelled),  percent: Math.round(cancelled / total * 100),                         color: '#ef4444' },
    { label: 'Customers',        value: String(customers),  percent: totalU ? Math.round(customers / totalU * 100) : 0,           color: '#3b82f6' },
  ]
})

// Delivery Performance metrics computed from orders
const deliveredOrders = computed(() =>
  allOrders.value.filter(o => o.status === 'delivered')
)

const deliveryMetrics = computed(() => {
  const total      = allOrders.value.length || 1
  const delivered  = deliveredOrders.value.length
  const cancelled  = allOrders.value.filter(o => o.status === 'cancelled').length
  const completion = Math.round(delivered / total * 100)

  // Average delivery time in minutes (created_at → delivered_at)
  const withTime = deliveredOrders.value.filter(o => o.delivered_at && o.created_at)
  const avgMinutes = withTime.length > 0
    ? Math.round(
        withTime.reduce((sum: number, o: any) => {
          const diff = new Date(o.delivered_at).getTime() - new Date(o.created_at).getTime()
          return sum + diff / 60000
        }, 0) / withTime.length
      )
    : 0

  const avgDisplay = avgMinutes > 0
    ? avgMinutes < 60 ? `${avgMinutes} min` : `${Math.round(avgMinutes / 60 * 10) / 10} hr`
    : 'N/A'

  return [
    { icon: '✅', label: 'Total Deliveries',    value: String(delivered),     bg: '#dcfce7' },
    { icon: '📊', label: 'Completion Rate',      value: `${completion}%`,      bg: '#dbeafe' },
    { icon: '⏱️', label: 'Avg Delivery Time',    value: avgDisplay,            bg: '#fef9c3' },
    { icon: '❌', label: 'Cancelled Orders',     value: String(cancelled),     bg: '#fee2e2' },
  ]
})

// Top drivers by number of delivered orders
const topDrivers = computed(() => {
  const map: Record<string, { name: string; deliveries: number; earnings: number }> = {}
  deliveredOrders.value.forEach((o: any) => {
    if (!o.driver) return
    const key = String(o.driver.id)
    if (!map[key]) map[key] = { name: o.driver.name, deliveries: 0, earnings: 0 }
    map[key].deliveries += 1
    map[key].earnings   += Number(o.delivery_fee) || 50
  })
  return Object.values(map).sort((a, b) => b.deliveries - a.deliveries).slice(0, 5)
})

const topProducts = computed(() => {
  const map: Record<string, { name: string; sold: number; revenue: number }> = {}
  allOrders.value.forEach((order: any) => {
    order.items?.forEach((item: any) => {
      if (!map[item.product_name]) map[item.product_name] = { name: item.product_name, sold: 0, revenue: 0 }
      map[item.product_name].sold    += item.quantity
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

const roleClass = (role: string) => {
  const map: Record<string, string> = {
    customer: 'bg-blue-100 text-blue-700',
    seller:   'bg-green-100 text-green-700',
    driver:   'bg-orange-100 text-orange-700',
    staff:    'bg-purple-100 text-purple-700',
    admin:    'bg-red-100 text-red-700',
  }
  return map[role] ?? 'bg-gray-100 text-gray-700'
}

const avatarColor = (name: string) => {
  const colors = ['#22c55e', '#3b82f6', '#f97316', '#8b5cf6', '#ec4899']
  return colors[(name?.charCodeAt(0) || 0) % colors.length]
}

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 100 })
    allOrders.value    = res.data || []
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
    const res: any = await get('/users', { per_page: 100 })
    allUsers.value    = res.data || []
    recentUsers.value = (res.data || []).slice(0, 5)
  } catch (e) {
    console.error('Failed to load users', e)
  } finally {
    loadingUsers.value = false
  }
}

const loadProducts = async () => {
  try {
    const res: any = await get('/products', { per_page: 1 })
    totalProducts.value = res.total || 0
  } catch (e) {
    console.error('Failed to load products count', e)
  }
}

onMounted(async () => {
  await Promise.all([loadOrders(), loadUsers(), loadProducts()])
})
</script>