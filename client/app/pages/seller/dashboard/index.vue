<template>
  <SellerLayout title="Dashboard" :subtitle="`Welcome back, ${authStore.user?.name || 'Seller'}!`">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4"
        :style="{ backgroundColor: stat.bgColor }"
      >
        <span class="text-3xl">{{ stat.icon }}</span>
        <div>
          <p class="text-xl font-black text-gray-800">
            {{ loading ? '...' : stat.value }}
          </p>
          <p class="text-xs text-gray-500">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <NuxtLink
        v-for="action in quickActions"
        :key="action.label"
        :to="action.to"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col items-center gap-2 hover:border-green-400 hover:shadow-md transition-all duration-200 group"
      >
        <span class="text-3xl">{{ action.icon }}</span>
        <span class="text-sm font-semibold text-gray-700 group-hover:text-green-600 transition-colors">
          {{ action.label }}
        </span>
      </NuxtLink>
    </div>

    <!-- Recent Orders + Top Products + Store Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Recent Orders — 2 columns -->
      <div class="lg:col-span-2">
        <RecentOrders :orders="recentOrders" :loading="loading" @update-status="handleUpdateStatus" />
      </div>

      <!-- Right Column -->
      <div class="flex flex-col gap-6">

        <!-- Top Products -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Top Products</h3>
            <NuxtLink to="/seller/products" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
          </div>
          <div class="p-4 flex flex-col gap-3">
            <div v-if="loading" v-for="n in 4" :key="n" class="h-8 bg-gray-100 rounded-lg animate-pulse" />
            <div v-else-if="topProducts.length === 0" class="text-center py-4 text-sm text-gray-400">No data yet</div>
            <div
              v-else
              v-for="(product, index) in topProducts"
              :key="product.name"
              class="flex items-center gap-3"
            >
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

        <!-- Store Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Store Status</h3>
          </div>
          <div class="p-5">
            <div class="flex items-center justify-between mb-4">
              <span :class="['text-sm font-semibold', isOpen ? 'text-green-600' : 'text-gray-400']">
                {{ isOpen ? 'Store is Open' : 'Store is Closed' }}
              </span>
              <button
                @click="isOpen = !isOpen"
                :class="['relative w-12 h-6 rounded-full transition-colors duration-200', isOpen ? 'bg-green-500' : 'bg-gray-200']"
              >
                <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200', isOpen ? 'translate-x-6' : 'translate-x-0']" />
              </button>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500">
              <div class="flex items-center justify-between">
                <span>Today's Orders</span>
                <span class="font-semibold text-gray-800">{{ todayOrders }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span>Pending</span>
                <span class="font-semibold text-yellow-600">{{ pendingOrders }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span>Completed</span>
                <span class="font-semibold text-green-600">{{ deliveredOrders }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'
import RecentOrders from '../../../components/seller/dashboard/RecentOrders.vue'

const authStore = useAuthStore()
const { get } = useApi()

const loading = ref(true)
const isOpen = ref(true)
const allOrders = ref<any[]>([])
const recentOrders = ref<any[]>([])

const loadData = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders')
    allOrders.value = res.data || []
    recentOrders.value = (res.data || []).slice(0, 5)
  } catch (e) {
    console.error('Failed to load seller dashboard data', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadData())

const todayOrders = computed(() => {
  const today = new Date().toDateString()
  return allOrders.value.filter(o =>
    new Date(o.created_at).toDateString() === today
  ).length
})

const pendingOrders = computed(() =>
  allOrders.value.filter(o => o.status === 'pending').length
)

const deliveredOrders = computed(() =>
  allOrders.value.filter(o => o.status === 'delivered').length
)

const totalRevenue = computed(() =>
  allOrders.value
    .filter(o => o.status === 'delivered')
    .reduce((s: number, o: any) => s + Number(o.total), 0)
)

const stats = computed(() => [
  {
    icon: '💰',
    label: "Today's Revenue",
    value: '₱' + allOrders.value
      .filter(o => {
        const today = new Date().toDateString()
        return o.status === 'delivered' && new Date(o.created_at).toDateString() === today
      })
      .reduce((s: number, o: any) => s + Number(o.total), 0)
      .toLocaleString(),
    bgColor: '#dcfce7',
  },
  {
    icon: '📦',
    label: 'Total Orders',
    value: String(allOrders.value.length),
    bgColor: '#dbeafe',
  },
  {
    icon: '🛍️',
    label: 'Total Revenue',
    value: '₱' + totalRevenue.value.toLocaleString(),
    bgColor: '#fef9c3',
  },
  {
    icon: '✅',
    label: 'Delivered',
    value: String(deliveredOrders.value),
    bgColor: '#fce7f3',
  },
])

const topProducts = computed(() => {
  const map: Record<string, { name: string; sold: number; revenue: number }> = {}
  allOrders.value.forEach((order: any) => {
    order.items?.forEach((item: any) => {
      if (!map[item.product_name]) {
        map[item.product_name] = { name: item.product_name, sold: 0, revenue: 0 }
      }
      map[item.product_name].sold += item.quantity
      map[item.product_name].revenue += Number(item.subtotal)
    })
  })
  return Object.values(map).sort((a, b) => b.sold - a.sold).slice(0, 5)
})

const quickActions = [
  { icon: '📋', label: 'View Orders',  to: '/seller/orders'   },
  { icon: '🛍️', label: 'Products',    to: '/seller/products'  },
  { icon: '👤', label: 'Profile',     to: '/customer/profile' },
  { icon: '⚙️', label: 'Settings',    to: '/seller/settings'  },
]

const { put } = useApi()

const handleUpdateStatus = async (id: number, status: string) => {
  try {
    await put(`/orders/${id}/status`, { status })
    const order = recentOrders.value.find((o: any) => o.id === id)
    if (order) order.status = status
    const allOrder = allOrders.value.find((o: any) => o.id === id)
    if (allOrder) allOrder.status = status
  } catch (e) {
    console.error('Failed to update order status', e)
  }
}
</script>