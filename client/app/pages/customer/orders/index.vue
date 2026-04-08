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

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-8">

      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
        <div
          v-for="stat in stats"
          :key="stat.label"
          @click="activeFilter = stat.filter"
          :class="[
            'bg-white rounded-2xl border shadow-sm p-4 flex items-center gap-3 cursor-pointer transition-colors',
            activeFilter === stat.filter ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-green-300'
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
      <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="activeFilter = tab.value"
          :class="[
            'px-4 py-2 rounded-full text-sm font-semibold transition-colors whitespace-nowrap',
            activeFilter === tab.value
              ? 'bg-green-500 text-white'
              : 'bg-white border border-gray-200 text-gray-500 hover:border-green-400'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col gap-4">
        <div v-for="n in 3" :key="n" class="bg-white rounded-2xl border border-gray-100 h-48 animate-pulse" />
      </div>

      <!-- Orders List -->
      <div v-else class="flex flex-col gap-4">

        <!-- Empty State -->
        <div
          v-if="filteredOrders.length === 0"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-20 text-center"
        >
          <span class="text-5xl mb-4">📦</span>
          <h3 class="text-lg font-bold text-gray-700 mb-2">No orders found</h3>
          <p class="text-sm text-gray-400 mb-6">
            {{ activeFilter !== 'all' ? `You have no ${activeFilter.toLowerCase()} orders yet.` : "You haven't placed any orders yet." }}
          </p>
          <NuxtLink to="/customer" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full transition-colors">
            Start Shopping
          </NuxtLink>
        </div>

        <!-- Order Cards -->
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
            <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-bold', statusClass(order.status)]">
              {{ order.status }}
            </span>
          </div>

          <!-- Seller -->
          <div class="px-4 md:px-6 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="text-lg">🏪</span>
            <span class="text-sm font-semibold text-gray-700">
              {{ order.seller?.name || 'OBRA Store' }}
            </span>
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
                  <span class="text-2xl">🥬</span>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-gray-800 truncate">{{ item.product_name }}</p>
                  <p class="text-xs text-gray-400">x{{ item.quantity }}</p>
                </div>
                <p class="text-sm font-bold text-gray-800 shrink-0">₱{{ Number(item.subtotal).toFixed(2) }}</p>
              </div>
            </div>
          </div>

          <!-- Order Footer -->
          <div class="px-4 md:px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
              <p class="text-xs text-gray-400">Total Amount</p>
              <p class="text-lg font-black text-green-600">₱{{ Number(order.total).toFixed(2) }}</p>
              <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ order.payment_method }} · {{ order.delivery_type }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
              <!-- Track button for active orders -->
              <button
                v-if="['confirmed','preparing','ready','in_transit'].includes(order.status)"
                @click="selectedOrder = order"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                🛵 Track Order
              </button>
              <!-- Rate button for delivered -->
              <button
                v-if="order.status === 'delivered'"
                class="flex items-center gap-2 border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                ⭐ Rate Order
              </button>
              <!-- Reorder -->
              <button
                v-if="['delivered','cancelled'].includes(order.status)"
                @click="navigateTo('/customer')"
                class="flex items-center gap-2 border border-green-400 text-green-600 hover:bg-green-50 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                🔁 Reorder
              </button>
              <!-- Cancel -->
              <button
                v-if="order.status === 'pending'"
                @click="cancelOrder(order.id)"
                class="flex items-center gap-2 border border-red-200 text-red-500 hover:bg-red-50 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                ✕ Cancel
              </button>
              <!-- View Details -->
              <button
                @click="selectedOrder = order"
                class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:border-green-400 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                View Details
              </button>
            </div>
          </div>

          <!-- Progress Tracker (for active orders) -->
          <div
            v-if="['confirmed','preparing','ready','in_transit','delivered'].includes(order.status)"
            class="px-4 md:px-6 py-4 border-t border-gray-100 bg-gray-50"
          >
            <div class="flex items-center gap-1 mb-2">
              <div
                v-for="(step, index) in trackingSteps"
                :key="step"
                class="flex items-center flex-1"
              >
                <div :class="[
                  'w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 transition-colors',
                  isStepCompleted(order.status, index) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400'
                ]">{{ index + 1 }}</div>
                <div v-if="index < trackingSteps.length - 1" :class="[
                  'flex-1 h-1 mx-1 rounded transition-colors',
                  isStepCompleted(order.status, index + 1) ? 'bg-green-500' : 'bg-gray-200'
                ]" />
              </div>
            </div>
            <div class="flex items-center justify-between">
              <span v-for="step in trackingSteps" :key="step" class="text-[9px] md:text-[10px] text-gray-500 text-center flex-1">{{ step }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Order Detail Modal -->
    <Transition name="fade">
      <div
        v-if="selectedOrder"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
        @click.self="selectedOrder = null"
      >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
            <h3 class="text-lg font-black text-gray-800">Order #{{ selectedOrder.id }}</h3>
            <button @click="selectedOrder = null" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500">✕</button>
          </div>
          <div class="px-6 py-5 flex flex-col gap-4">
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Status</span>
              <span :class="['px-3 py-1 rounded-full text-xs font-bold', statusClass(selectedOrder.status)]">{{ selectedOrder.status }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Seller</span>
              <span class="text-sm text-gray-600">{{ selectedOrder.seller?.name || 'OBRA Store' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Date</span>
              <span class="text-sm text-gray-600">{{ formatDate(selectedOrder.created_at) }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Payment</span>
              <span class="text-sm text-gray-600 capitalize">{{ selectedOrder.payment_method }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Delivery</span>
              <span class="text-sm text-gray-600 capitalize">{{ selectedOrder.delivery_type }}</span>
            </div>
            <div v-if="selectedOrder.delivery_address" class="flex flex-col gap-1">
              <span class="text-sm font-semibold text-gray-700">Address</span>
              <span class="text-sm text-gray-600">{{ selectedOrder.delivery_address }}</span>
            </div>
            <div class="border-t border-gray-100 pt-4">
              <p class="text-sm font-semibold text-gray-700 mb-3">Items</p>
              <div class="flex flex-col gap-2">
                <div v-for="item in selectedOrder.items" :key="item.id" class="flex items-center justify-between text-sm">
                  <span class="text-gray-600">{{ item.quantity }}x {{ item.product_name }}</span>
                  <span class="font-semibold text-gray-800">₱{{ Number(item.subtotal).toFixed(2) }}</span>
                </div>
              </div>
            </div>
            <div class="border-t border-gray-100 pt-4 flex flex-col gap-2 text-sm">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>₱{{ Number(selectedOrder.subtotal || selectedOrder.total).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Delivery fee</span>
                <span>₱{{ Number(selectedOrder.delivery_fee || 0).toFixed(2) }}</span>
              </div>
              <div v-if="Number(selectedOrder.tip) > 0" class="flex justify-between text-gray-600">
                <span>Rider tip</span>
                <span>₱{{ Number(selectedOrder.tip).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between font-black text-gray-800 text-base pt-1 border-t border-gray-100">
                <span>Total</span>
                <span class="text-green-600">₱{{ Number(selectedOrder.total).toFixed(2) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import GuestLayout from '../../../components/layout/GuestLayout.vue'

const { get, put } = useApi()

const orders = ref<any[]>([])
const loading = ref(true)
const activeFilter = ref('all')
const selectedOrder = ref<any>(null)

const tabs = [
  { label: 'All Orders', value: 'all'       },
  { label: 'Active',     value: 'active'     },
  { label: 'Delivered',  value: 'delivered'  },
  { label: 'Cancelled',  value: 'cancelled'  },
]

const trackingSteps = ['Confirmed', 'Preparing', 'Ready', 'In Transit', 'Delivered']

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders')
    orders.value = res.data || []
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadOrders())

const stats = computed(() => [
  { icon: '📦', label: 'Total Orders', value: orders.value.length, filter: 'all' },
  { icon: '🚀', label: 'Active',       value: orders.value.filter(o => ['pending','confirmed','preparing','ready','in_transit'].includes(o.status)).length, filter: 'active' },
  { icon: '✅', label: 'Delivered',    value: orders.value.filter(o => o.status === 'delivered').length, filter: 'delivered' },
  { icon: '❌', label: 'Cancelled',    value: orders.value.filter(o => o.status === 'cancelled').length, filter: 'cancelled' },
])

const filteredOrders = computed(() => {
  if (activeFilter.value === 'all') return orders.value
  if (activeFilter.value === 'active') return orders.value.filter(o => ['pending','confirmed','preparing','ready','in_transit'].includes(o.status))
  return orders.value.filter(o => o.status === activeFilter.value)
})

const statusClass = (status: string) => {
  const map: Record<string, string> = {
    'pending':    'bg-yellow-100 text-yellow-700',
    'confirmed':  'bg-blue-100 text-blue-700',
    'preparing':  'bg-orange-100 text-orange-700',
    'ready':      'bg-purple-100 text-purple-700',
    'in_transit': 'bg-blue-100 text-blue-700',
    'delivered':  'bg-green-100 text-green-700',
    'cancelled':  'bg-red-100 text-red-700',
  }
  return map[status] ?? 'bg-gray-100 text-gray-700'
}

const isStepCompleted = (status: string, stepIndex: number) => {
  const order = ['confirmed', 'preparing', 'ready', 'in_transit', 'delivered']
  return order.indexOf(status) >= stepIndex
}

const formatDate = (dateStr: string) => {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  })
}

const cancelOrder = async (id: number) => {
  if (!confirm('Are you sure you want to cancel this order?')) return
  try {
    await put(`/orders/${id}/status`, { status: 'cancelled' })
    const order = orders.value.find(o => o.id === id)
    if (order) order.status = 'cancelled'
  } catch (e) {
    alert('Failed to cancel order. Please try again.')
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>