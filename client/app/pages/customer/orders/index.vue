<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">My Orders</span>
        </div>
        <h1 class="text-white text-2xl font-black mt-1">My Orders</h1>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div
          v-for="stat in stats"
          :key="stat.label"
          @click="activeFilter = stat.filter"
          :class="[
            'bg-white rounded-2xl border shadow-sm p-4 flex items-center gap-3 cursor-pointer transition-all',
            activeFilter === stat.filter ? 'border-green-400 shadow-green-100' : 'border-gray-100 hover:border-gray-200'
          ]"
        >
          <span class="text-2xl">{{ stat.icon }}</span>
          <div>
            <p class="text-xl font-black text-gray-800">{{ stat.value }}</p>
            <p class="text-xs text-gray-400">{{ stat.label }}</p>
          </div>
        </div>
      </div>

      <!-- Filter Tabs -->
      <div class="flex items-center gap-2 mb-5 flex-wrap">
        <button
          v-for="tab in tabs"
          :key="tab.label"
          @click="activeFilter = tab.filter"
          :class="[
            'px-4 py-1.5 rounded-full text-sm font-semibold border transition-all',
            activeFilter === tab.filter
              ? 'bg-green-500 text-white border-green-500'
              : 'border-gray-200 text-gray-600 hover:border-green-400'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col gap-4">
        <div v-for="n in 3" :key="n" class="bg-white rounded-2xl h-40 animate-pulse border border-gray-100" />
      </div>

      <!-- Empty -->
      <div v-else-if="filteredOrders.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
        <span class="text-6xl mb-4">📦</span>
        <h3 class="text-lg font-bold text-gray-700 mb-2">No orders found</h3>
        <p class="text-sm text-gray-400 mb-6">
          {{ activeFilter !== 'all'
            ? `You have no ${activeFilter} orders yet.`
            : "You haven't placed any orders yet." }}
        </p>
        <NuxtLink to="/customer" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full transition-colors">
          Start Shopping
        </NuxtLink>
      </div>

      <!-- Order Cards -->
      <div v-else class="flex flex-col gap-4">
        <div
          v-for="order in filteredOrders"
          :key="order.id"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
          <!-- Order Header -->
          <div class="flex items-center justify-between px-4 md:px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div>
              <p class="text-sm font-black text-gray-800">Order #{{ order.id }}</p>
              <p class="text-xs text-gray-400">{{ formatDate(order.created_at) }}</p>
            </div>
            <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-bold capitalize', statusClass(order.status)]">
              {{ statusLabel(order.status) }}
            </span>
          </div>

          <!-- Status Tracker -->
          <div class="px-4 md:px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between relative">
              <!-- Progress line -->
              <div class="absolute left-0 right-0 top-3.5 h-0.5 bg-gray-200 z-0" />
              <div
                class="absolute left-0 top-3.5 h-0.5 bg-green-500 z-0 transition-all duration-500"
                :style="{ width: progressWidth(order.status) }"
              />
              <div
                v-for="(step, i) in statusSteps"
                :key="step.key"
                class="flex flex-col items-center z-10 relative"
              >
                <div :class="[
                  'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all',
                  isCompleted(order.status, step.key)
                    ? 'bg-green-500 text-white'
                    : isActive(order.status, step.key)
                      ? 'bg-green-500 text-white ring-4 ring-green-100'
                      : 'bg-gray-200 text-gray-400'
                ]">
                  <svg v-if="isCompleted(order.status, step.key)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                  </svg>
                  <span v-else>{{ i + 1 }}</span>
                </div>
                <p :class="[
                  'text-[10px] mt-1.5 font-semibold text-center leading-tight max-w-[56px]',
                  isActive(order.status, step.key) ? 'text-green-600' : 'text-gray-400'
                ]">{{ step.label }}</p>
              </div>
            </div>
          </div>

          <!-- Seller -->
          <div class="px-4 md:px-6 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="text-lg">🏪</span>
            <span class="text-sm font-semibold text-gray-700">{{ order.seller?.name || 'OBRA Store' }}</span>
          </div>

          <!-- Order Items -->
          <div class="px-4 md:px-6 py-4">
            <div class="flex flex-col gap-3">
              <div
                v-for="item in order.items"
                :key="item.id"
                class="flex items-center gap-3"
              >
                <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                  <img
                    v-if="imageMap[item.product_name]"
                    :src="imageMap[item.product_name]"
                    :alt="item.product_name"
                    class="w-full h-full object-contain p-1"
                  />
                  <span v-else class="text-2xl">🥬</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-gray-800">{{ item.product_name }}</p>
                  <p class="text-xs text-gray-400">x{{ item.quantity }} · ₱{{ Number(item.price).toFixed(2) }} each</p>
                </div>
                <p class="text-sm font-bold text-gray-800 shrink-0">₱{{ Number(item.subtotal).toFixed(2) }}</p>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="px-4 md:px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-400">Total Amount</p>
              <p class="text-lg font-black text-green-600">₱{{ Number(order.total).toFixed(2) }}</p>
              <p class="text-xs text-gray-400 capitalize">{{ order.payment_method }} · {{ order.delivery_type }}</p>
            </div>
            <div class="flex items-center gap-2">
              <button
                v-if="order.status === 'pending'"
                @click="cancelOrder(order)"
                :disabled="cancellingId === order.id"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-red-200 text-red-500 text-sm font-semibold hover:bg-red-50 disabled:opacity-50 transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ cancellingId === order.id ? 'Cancelling...' : 'Cancel' }}
              </button>
              <NuxtLink
                :to="`/customer/orders/${order.id}`"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:border-green-400 hover:text-green-600 transition-colors"
              >
                View Details
              </NuxtLink>
            </div>
          </div>

        </div>
      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import GuestLayout from '../../../components/layout/GuestLayout.vue'

const { get, put } = useApi()
const loading = ref(true)
const orders = ref<any[]>([])
const activeFilter = ref('all')
const cancellingId = ref<number | null>(null)

const statusSteps = [
  { key: 'pending',    label: 'Order\nReceived' },
  { key: 'preparing',  label: 'Preparing' },
  { key: 'in_transit', label: 'On the\nWay' },
  { key: 'delivered',  label: 'Delivered' },
]

const statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'in_transit', 'delivered']

const progressWidth = (status: string) => {
  const idx = statusOrder.indexOf(status)
  if (idx <= 0) return '0%'
  if (status === 'delivered') return '100%'
  const percent = (idx / (statusOrder.length - 1)) * 100
  return `${percent}%`
}

const isCompleted = (orderStatus: string, stepKey: string) => {
  const orderIdx = statusOrder.indexOf(orderStatus)
  const stepIdx = statusOrder.indexOf(stepKey)
  return stepIdx < orderIdx
}

const isActive = (orderStatus: string, stepKey: string) => {
  if (stepKey === 'pending') return ['pending', 'confirmed'].includes(orderStatus)
  if (stepKey === 'preparing') return ['preparing', 'ready'].includes(orderStatus)
  if (stepKey === 'in_transit') return orderStatus === 'in_transit'
  if (stepKey === 'delivered') return orderStatus === 'delivered'
  return false
}

const imageMap: Record<string, string> = {
  'Tomato':        '/images/products/vegetables/Tomato.png',
  'Eggplant':      '/images/products/vegetables/eggplant.png',
  'Bitter Gourd':  '/images/products/vegetables/bitter_gourd.png',
  'Okra':          '/images/products/vegetables/okra.png',
  'Sitaw':         '/images/products/vegetables/sitaw.png',
  'Kangkong':      '/images/products/vegetables/kangkong.png',
  'Repolyo':       '/images/products/vegetables/repolyo.png',
  'Carrot':        '/images/products/vegetables/carrot.png',
  'Potato':        '/images/products/vegetables/potato.png',
  'Sibuyas':       '/images/products/vegetables/sibuyas.png',
  'Bawang':        '/images/products/vegetables/bawang.png',
  'Luya':          '/images/products/vegetables/luya.png',
  'Mais':          '/images/products/vegetables/mais.png',
  'Siling Haba':   '/images/products/vegetables/siling_haba.png',
  'Siling Labuyo': '/images/products/vegetables/siling_labuyo.png',
  'Upo':           '/images/products/vegetables/upo.png',
  'Patola':        '/images/products/vegetables/patola.png',
  'Sigarilyas':    '/images/products/vegetables/sigarilyas.png',
  'Gabi':          '/images/products/vegetables/gabi.png',
  'Kamote':        '/images/products/vegetables/kamote.png',
  'Labanos':       '/images/products/vegetables/labanos.png',
  'Banana':        '/images/products/fruits/saging.png',
  'Mango':         '/images/products/fruits/mango.png',
  'Papaya':        '/images/products/fruits/papaya.png',
  'Pakwan':        '/images/products/fruits/pakwan.png',
  'Bayabas':       '/images/products/fruits/Guava.png',
  'Melon':         '/images/products/fruits/melon.png',
  'Pineapple':     '/images/products/fruits/Pineapple.png',
  'Avocado':       '/images/products/fruits/Avocado.png',
  'Guava':         '/images/products/fruits/Guava.png',
  'Rambutan':      '/images/products/fruits/Rambutan.png',
  'Lanzones':      '/images/products/fruits/Lanzones.jpg',
  'Calamansi':     '/images/products/fruits/Calamansi.png',
  'Orange':        '/images/products/fruits/Orange.png',
  'Apple':         '/images/products/fruits/Apple.png',
  'Grapes':        '/images/products/fruits/Grapes.png',
  'Chicken':       '/images/products/meat/Chicken.png',
  'Pork Meat':     '/images/products/meat/pork_meat.png',
  'Egg':           '/images/products/meat/Egg.png',
  'Rice':          '/images/products/meat/rice.png',
  'Bangus':        '/images/products/meat/Chicken.png',
  'Tilapia':       '/images/products/meat/Chicken.png',
  'Hipon':         '/images/products/meat/Chicken.png',
}

const tabs = [
  { label: 'All Orders', filter: 'all' },
  { label: 'Active',     filter: 'active' },
  { label: 'Delivered',  filter: 'delivered' },
  { label: 'Cancelled',  filter: 'cancelled' },
]

const stats = computed(() => [
  { icon: '📦', label: 'Total Orders', value: orders.value.length, filter: 'all' },
  { icon: '🚀', label: 'Active',       value: orders.value.filter(o => !['delivered','cancelled'].includes(o.status)).length, filter: 'active' },
  { icon: '✅', label: 'Delivered',    value: orders.value.filter(o => o.status === 'delivered').length, filter: 'delivered' },
  { icon: '❌', label: 'Cancelled',    value: orders.value.filter(o => o.status === 'cancelled').length, filter: 'cancelled' },
])

const filteredOrders = computed(() => {
  if (activeFilter.value === 'all') return orders.value
  if (activeFilter.value === 'active') return orders.value.filter(o => !['delivered','cancelled'].includes(o.status))
  return orders.value.filter(o => o.status === activeFilter.value)
})

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}

const statusLabel = (s: string) => {
  const map: Record<string, string> = {
    pending: 'Pending', confirmed: 'Confirmed', preparing: 'Preparing',
    ready: 'Ready', in_transit: 'In Transit', delivered: 'Delivered', cancelled: 'Cancelled',
  }
  return map[s] ?? s
}

const statusClass = (s: string) => {
  const map: Record<string, string> = {
    pending:    'bg-yellow-100 text-yellow-700',
    confirmed:  'bg-blue-100 text-blue-700',
    preparing:  'bg-orange-100 text-orange-700',
    ready:      'bg-purple-100 text-purple-700',
    in_transit: 'bg-blue-100 text-blue-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
  }
  return map[s] ?? 'bg-gray-100 text-gray-700'
}

const cancelOrder = async (order: any) => {
  if (!confirm('Are you sure you want to cancel this order?')) return
  cancellingId.value = order.id
  try {
    await put(`/orders/${order.id}/status`, { status: 'cancelled' })
    order.status = 'cancelled'
  } catch (e: any) {
    alert(e?.data?.message || 'Failed to cancel order.')
  } finally {
    cancellingId.value = null
  }
}

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 50 })
    orders.value = res.data || []
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadOrders())
</script>