<template>
  <GuestLayout>
    <div class="max-w-3xl mx-auto px-4 py-8">

      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl font-black text-gray-900">My Orders</h1>
        <p class="text-sm text-gray-400 mt-1">Track and manage your purchases</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <button
          v-for="stat in stats"
          :key="stat.label"
          @click="activeFilter = stat.filter"
          :class="[
            'flex flex-col items-center gap-1 p-3 rounded-2xl border transition-all',
            activeFilter === stat.filter
              ? 'border-green-500 bg-green-50'
              : 'border-gray-100 bg-white hover:border-green-300'
          ]"
        >
          <span class="text-xl">{{ stat.icon }}</span>
          <span class="text-lg font-black text-gray-800">{{ stat.value }}</span>
          <span class="text-[10px] text-gray-400 font-medium">{{ stat.label }}</span>
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col gap-4">
        <div v-for="n in 3" :key="n" class="bg-white rounded-2xl border border-gray-100 h-40 animate-pulse" />
      </div>

      <!-- Empty -->
      <div v-else-if="filteredOrders.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
        <span class="text-5xl mb-3">📦</span>
        <p class="text-base font-bold text-gray-700">No orders found</p>
        <NuxtLink to="/customer" class="mt-3 text-sm text-green-500 font-semibold hover:underline">Start Shopping</NuxtLink>
      </div>

      <!-- Orders -->
      <div v-else class="flex flex-col gap-4">
        <div
          v-for="order in filteredOrders"
          :key="order.id"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
          <!-- Order Header -->
          <div class="flex items-center justify-between px-4 md:px-6 py-4 border-b border-gray-50">
            <div>
              <p class="text-sm font-black text-gray-800">Order #{{ order.id }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ formatDate(order.created_at) }}</p>
            </div>
            <span :class="['px-3 py-1 rounded-full text-xs font-bold', statusClass(order.status)]">
              {{ order.status.replace('_', ' ') }}
            </span>
          </div>

          <!-- Items Preview -->
          <div class="px-4 md:px-6 py-3 flex items-center gap-3">
            <div class="flex -space-x-2">
              <img
                v-for="(item, i) in order.items?.slice(0, 3)"
                :key="i"
                :src="item.product?.image_url || '/images/placeholder.png'"
                class="w-9 h-9 rounded-full border-2 border-white object-cover bg-gray-100"
              />
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-700 truncate">
                {{ order.items?.map((i: any) => i.product_name).join(', ') }}
              </p>
              <p class="text-xs text-gray-400">{{ order.items?.length }} item{{ order.items?.length !== 1 ? 's' : '' }} · ₱{{ Number(order.total).toFixed(2) }}</p>
            </div>
          </div>

          <!-- ETA Banner — shown when in transit -->
          <div
            v-if="order.status === 'in_transit'"
            class="mx-4 md:mx-6 mb-3 flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-2.5"
          >
            <span class="text-lg">🛵</span>
            <div>
              <p class="text-xs font-bold text-blue-700">Driver is on the way!</p>
              <p class="text-xs text-blue-500">Estimated arrival: <span class="font-semibold">30 – 45 minutes</span></p>
            </div>
          </div>

          <!-- ETA Banner — shown when ready (waiting for driver) -->
          <div
            v-else-if="order.status === 'ready'"
            class="mx-4 md:mx-6 mb-3 flex items-center gap-3 bg-purple-50 border border-purple-100 rounded-xl px-4 py-2.5"
          >
            <span class="text-lg">📦</span>
            <div>
              <p class="text-xs font-bold text-purple-700">Order is ready — waiting for a driver</p>
              <p class="text-xs text-purple-500">A driver will accept soon. Estimated delivery once picked up: <span class="font-semibold">30 – 45 min</span></p>
            </div>
          </div>

          <!-- ETA Banner — shown when preparing -->
          <div
            v-else-if="order.status === 'preparing'"
            class="mx-4 md:mx-6 mb-3 flex items-center gap-3 bg-orange-50 border border-orange-100 rounded-xl px-4 py-2.5"
          >
            <span class="text-lg">🧑‍🍳</span>
            <div>
              <p class="text-xs font-bold text-orange-700">Seller is preparing your order</p>
              <p class="text-xs text-orange-500">Estimated delivery: <span class="font-semibold">45 – 60 minutes</span></p>
            </div>
          </div>

          <!-- ETA Banner — shown when confirmed -->
          <div
            v-else-if="order.status === 'confirmed'"
            class="mx-4 md:mx-6 mb-3 flex items-center gap-3 bg-green-50 border border-green-100 rounded-xl px-4 py-2.5"
          >
            <span class="text-lg">✅</span>
            <div>
              <p class="text-xs font-bold text-green-700">Order confirmed by seller!</p>
              <p class="text-xs text-green-600">Estimated delivery: <span class="font-semibold">45 – 60 minutes</span></p>
            </div>
          </div>

          <!-- Progress Tracker -->
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

          <!-- Actions -->
          <div class="px-4 md:px-6 py-3 flex items-center gap-2 flex-wrap border-t border-gray-50">
            <button
              v-if="['delivered','cancelled'].includes(order.status)"
              @click="navigateTo('/customer')"
              class="flex items-center gap-2 border border-green-400 text-green-600 hover:bg-green-50 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
            >
              🔁 Reorder
            </button>
            <button
              v-if="order.status === 'pending'"
              @click="cancelOrder(order.id)"
              class="flex items-center gap-2 border border-red-200 text-red-500 hover:bg-red-50 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
            >
              ✕ Cancel
            </button>
            <button
              @click="selectedOrder = order"
              class="flex items-center gap-2 border border-gray-200 text-gray-600 hover:border-green-400 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
            >
              View Details
            </button>
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

            <!-- ETA in modal -->
            <div
              v-if="['confirmed','preparing','ready','in_transit'].includes(selectedOrder.status)"
              class="flex items-center gap-3 rounded-xl px-4 py-3"
              :class="{
                'bg-blue-50 border border-blue-100': selectedOrder.status === 'in_transit',
                'bg-purple-50 border border-purple-100': selectedOrder.status === 'ready',
                'bg-orange-50 border border-orange-100': selectedOrder.status === 'preparing',
                'bg-green-50 border border-green-100': selectedOrder.status === 'confirmed',
              }"
            >
              <span class="text-xl">{{ etaEmoji(selectedOrder.status) }}</span>
              <div>
                <p class="text-xs font-bold" :class="{
                  'text-blue-700': selectedOrder.status === 'in_transit',
                  'text-purple-700': selectedOrder.status === 'ready',
                  'text-orange-700': selectedOrder.status === 'preparing',
                  'text-green-700': selectedOrder.status === 'confirmed',
                }">{{ etaLabel(selectedOrder.status) }}</p>
                <p class="text-xs" :class="{
                  'text-blue-500': selectedOrder.status === 'in_transit',
                  'text-purple-500': selectedOrder.status === 'ready',
                  'text-orange-500': selectedOrder.status === 'preparing',
                  'text-green-600': selectedOrder.status === 'confirmed',
                }">ETA: <span class="font-semibold">{{ etaTime(selectedOrder.status) }}</span></p>
              </div>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Status</span>
              <span :class="['px-3 py-1 rounded-full text-xs font-bold', statusClass(selectedOrder.status)]">{{ selectedOrder.status.replace('_', ' ') }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Seller</span>
              <span class="text-sm text-gray-600">{{ selectedOrder.seller?.name || 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Payment</span>
              <span class="text-sm text-gray-600 uppercase">{{ selectedOrder.payment_method }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Delivery</span>
              <span class="text-sm text-gray-600">{{ selectedOrder.delivery_address || 'Store Pickup' }}</span>
            </div>
            <div v-if="selectedOrder.delivery_note" class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Note</span>
              <span class="text-sm text-gray-600 text-right max-w-[60%]">{{ selectedOrder.delivery_note }}</span>
            </div>

            <!-- Items -->
            <div class="border-t border-gray-100 pt-4">
              <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Items</p>
              <div class="flex flex-col gap-2">
                <div v-for="item in selectedOrder.items" :key="item.id" class="flex items-center justify-between text-sm">
                  <span class="text-gray-700">{{ item.product_name }} × {{ item.quantity }}</span>
                  <span class="font-semibold text-gray-800">₱{{ Number(item.subtotal).toFixed(2) }}</span>
                </div>
              </div>
            </div>

            <!-- Totals -->
            <div class="border-t border-gray-100 pt-3 flex flex-col gap-2 text-sm">
              <div class="flex justify-between text-gray-500">
                <span>Subtotal</span><span>₱{{ Number(selectedOrder.subtotal).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-gray-500">
                <span>Delivery fee</span>
                <span :class="Number(selectedOrder.delivery_fee) === 0 ? 'text-green-500 font-semibold' : ''">
                  {{ Number(selectedOrder.delivery_fee) === 0 ? 'Free' : '₱' + Number(selectedOrder.delivery_fee).toFixed(2) }}
                </span>
              </div>
              <div v-if="Number(selectedOrder.tip) > 0" class="flex justify-between text-gray-500">
                <span>Tip</span><span>₱{{ Number(selectedOrder.tip).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between font-black text-gray-800 text-base pt-2 border-t border-gray-100">
                <span>Total</span><span class="text-green-600">₱{{ Number(selectedOrder.total).toFixed(2) }}</span>
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

const loading = ref(false)
const orders = ref<any[]>([])
const activeFilter = ref('all')
const selectedOrder = ref<any>(null)

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

const etaEmoji = (status: string) => {
  const map: Record<string, string> = {
    confirmed: '✅', preparing: '🧑‍🍳', ready: '📦', in_transit: '🛵'
  }
  return map[status] ?? '📦'
}

const etaLabel = (status: string) => {
  const map: Record<string, string> = {
    confirmed:  'Order confirmed by seller!',
    preparing:  'Seller is preparing your order',
    ready:      'Order ready — waiting for a driver',
    in_transit: 'Driver is on the way!',
  }
  return map[status] ?? ''
}

const etaTime = (status: string) => {
  const map: Record<string, string> = {
    confirmed:  '45 – 60 minutes',
    preparing:  '45 – 60 minutes',
    ready:      '30 – 45 minutes',
    in_transit: '30 – 45 minutes',
  }
  return map[status] ?? ''
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