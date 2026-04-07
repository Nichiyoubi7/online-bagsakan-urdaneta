<template>
  <SellerLayout :title="`Dashboard`" :subtitle="`Welcome back, ${authStore.user?.name || 'Seller'}!`">

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-6 md:mb-8">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3"
        :style="{ backgroundColor: stat.bgColor }"
      >
        <span class="text-2xl">{{ stat.icon }}</span>
        <div>
          <p class="text-lg md:text-xl font-black text-gray-800">{{ stat.value }}</p>
          <p class="text-xs text-gray-500">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 md:mb-8">
      <NuxtLink
        v-for="action in quickActions"
        :key="action.label"
        :to="action.to"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col items-center gap-2 hover:border-green-400 hover:shadow-md transition-all duration-200 group"
      >
        <span class="text-3xl">{{ action.icon }}</span>
        <span class="text-xs md:text-sm font-semibold text-gray-700 group-hover:text-green-600 transition-colors text-center">
          {{ action.label }}
        </span>
      </NuxtLink>
    </div>

    <!-- Recent Orders + Side Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Recent Orders -->
      <div class="lg:col-span-2">
        <RecentOrders :orders="recentOrders" :loading="loading" @update-status="handleUpdateStatus" />
      </div>

      <!-- Side Panel -->
      <div class="lg:col-span-1 flex flex-col gap-4">

        <!-- Top Products -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Top Products</h3>
            <NuxtLink to="/seller/products" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
          </div>
          <div class="p-4 flex flex-col gap-3">
            <div v-if="loading" v-for="n in 5" :key="n" class="h-8 bg-gray-100 rounded-lg animate-pulse" />
            <div v-else-if="topProducts.length === 0" class="text-center py-6 text-sm text-gray-400">
              No product data yet
            </div>
            <div
              v-else
              v-for="(product, index) in topProducts"
              :key="product.name"
              class="flex items-center gap-3"
            >
              <span :class="[
                'w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0',
                index === 0 ? 'bg-yellow-400 text-white' :
                index === 1 ? 'bg-gray-300 text-white' :
                index === 2 ? 'bg-orange-400 text-white' :
                'bg-gray-100 text-gray-500'
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
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h3 class="text-base font-black text-gray-800 mb-4">Store Status</h3>
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div :class="['w-3 h-3 rounded-full', isOpen ? 'bg-green-500 animate-pulse' : 'bg-gray-300']" />
              <span class="text-sm font-semibold text-gray-700">{{ isOpen ? 'Store is Open' : 'Store is Closed' }}</span>
            </div>
            <button
              @click="isOpen = !isOpen"
              :class="['relative w-12 h-6 rounded-full transition-colors duration-200', isOpen ? 'bg-green-500' : 'bg-gray-200']"
            >
              <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200', isOpen ? 'translate-x-6' : 'translate-x-0']" />
            </button>
          </div>
          <div class="flex flex-col gap-2 text-sm text-gray-500">
            <div class="flex items-center justify-between">
              <span>Pending Orders</span>
              <span class="font-semibold text-yellow-600">{{ pendingCount }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Total Orders Today</span>
              <span class="font-semibold text-gray-800">{{ recentOrders.length }}</span>
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
const { get, put } = useApi()

const isOpen = ref(true)
const loading = ref(true)
const recentOrders = ref<any[]>([])
const allOrders = ref<any[]>([])

const stats = computed(() => [
  {
    icon: '💰',
    label: "Total Revenue",
    value: '₱' + allOrders.value.filter(o => o.status === 'delivered').reduce((s: number, o: any) => s + Number(o.total), 0).toFixed(0),
    bgColor: '#dcfce7',
  },
  {
    icon: '📦',
    label: 'Total Orders',
    value: String(allOrders.value.length),
    bgColor: '#dbeafe',
  },
  {
    icon: '🕐',
    label: 'Pending',
    value: String(allOrders.value.filter((o: any) => o.status === 'pending').length),
    bgColor: '#fef9c3',
  },
  {
    icon: '✅',
    label: 'Delivered',
    value: String(allOrders.value.filter((o: any) => o.status === 'delivered').length),
    bgColor: '#fce7f3',
  },
])

const pendingCount = computed(() =>
  recentOrders.value.filter(o => o.status === 'pending').length
)

const topProducts = computed(() => {
  const productMap: Record<string, { name: string; sold: number; revenue: number }> = {}
  allOrders.value.forEach((order: any) => {
    order.items?.forEach((item: any) => {
      if (!productMap[item.product_name]) {
        productMap[item.product_name] = { name: item.product_name, sold: 0, revenue: 0 }
      }
      productMap[item.product_name].sold += item.quantity
      productMap[item.product_name].revenue += Number(item.subtotal)
    })
  })
  return Object.values(productMap).sort((a, b) => b.sold - a.sold).slice(0, 5)
})

const quickActions = [
  { icon: '➕', label: 'Add Product',  to: '/seller/products' },
  { icon: '📋', label: 'View Orders',  to: '/seller/orders'   },
  { icon: '💰', label: 'Revenue',      to: '/seller/revenue'  },
  { icon: '👤', label: 'Edit Profile', to: '/seller/profile'  },
]

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders')
    allOrders.value = res.data || []
    recentOrders.value = (res.data || []).slice(0, 6)
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
}

const handleUpdateStatus = async (id: number, status: string) => {
  try {
    await put(`/orders/${id}/status`, { status })
    const order = allOrders.value.find((o: any) => o.id === id)
    if (order) order.status = status
    const recent = recentOrders.value.find((o: any) => o.id === id)
    if (recent) recent.status = status
  } catch (e) {
    console.error('Failed to update order status', e)
  }
}

onMounted(() => loadOrders())
</script>