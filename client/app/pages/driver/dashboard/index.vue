<template>
  <DriverLayout>

    <!-- Driver Profile Banner -->
    <div class="bg-gradient-to-br from-green-500 to-green-700 px-4 pt-5 pb-8">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-green-600 font-black text-xl shadow-md">
          {{ authStore.user?.name?.charAt(0) || 'D' }}
        </div>
        <div>
          <p class="text-white font-black text-lg">{{ authStore.user?.name || 'Driver' }}</p>
          <div class="flex items-center gap-1">
            <span class="text-yellow-300 text-sm">⭐</span>
            <span class="text-white text-sm font-semibold">4.9</span>
            <span class="text-green-200 text-xs">({{ deliveredOrders.length }} deliveries)</span>
          </div>
        </div>
      </div>

      <!-- Status Bar -->
      <div class="bg-white/20 rounded-2xl px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-2.5 h-2.5 bg-green-300 rounded-full animate-pulse"></div>
          <span class="text-white text-sm font-semibold">You are Online</span>
        </div>
        <span class="text-green-200 text-xs">Ready for deliveries</span>
      </div>
    </div>

    <!-- Stats Bar -->
    <div class="-mt-4">
      <DriverStatsBar
        :today-earnings="todayEarnings"
        :total-deliveries="deliveredOrders.length"
        :rating="4.9"
      />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="px-4 flex flex-col gap-3 mt-2">
      <div v-for="n in 3" :key="n" class="h-32 bg-white rounded-2xl border border-gray-100 animate-pulse" />
    </div>

    <template v-else>

      <!-- Active Delivery -->
      <template v-if="activeDelivery">
        <div class="px-4 mb-2 mt-2">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
            <h2 class="text-sm font-black text-gray-800 uppercase tracking-wide">Active Delivery</h2>
          </div>
        </div>
        <DeliveryCard
          :delivery="activeDelivery"
          :is-new="false"
          @picked-up="handlePickedUp"
          @delivered="handleDelivered"
        />
      </template>

      <!-- New Requests (ready orders) -->
      <div class="px-4 mb-2 mt-2">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <h2 class="text-sm font-black text-gray-800 uppercase tracking-wide">
              New Requests ({{ readyOrders.length }})
            </h2>
          </div>
          <span class="text-xs text-gray-400">Tap to accept</span>
        </div>
      </div>

      <DeliveryCard
        v-for="order in readyOrders"
        :key="order.id"
        :delivery="mapOrderToDelivery(order)"
        :is-new="true"
        @accept="handleAccept"
        @reject="handleReject"
      />

      <!-- No Requests -->
      <div
        v-if="readyOrders.length === 0 && !activeDelivery"
        class="flex flex-col items-center justify-center py-16 text-center px-8"
      >
        <span class="text-5xl mb-3">🛵</span>
        <h3 class="text-base font-bold text-gray-700 mb-1">No deliveries yet</h3>
        <p class="text-sm text-gray-400">Stay online to receive delivery requests</p>
      </div>

      <!-- Completed Today -->
      <div class="px-4 mt-4 mb-2">
        <h2 class="text-sm font-black text-gray-800 uppercase tracking-wide">
          Completed ({{ deliveredOrders.length }})
        </h2>
      </div>

      <DeliveryCard
        v-for="order in deliveredOrders"
        :key="order.id"
        :delivery="mapOrderToDelivery(order)"
        :is-new="false"
      />

    </template>

  </DriverLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DriverLayout from '../../../components/driver/layout/DriverLayout.vue'
import DriverStatsBar from '../../../components/driver/dashboard/DriverStatsBar.vue'
import DeliveryCard from '../../../components/driver/dashboard/DeliveryCard.vue'

const authStore = useAuthStore()
const { get, put } = useApi()

const loading = ref(true)
const orders = ref<any[]>([])

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

// Orders with status 'ready' are available for driver to pick up
const readyOrders = computed(() =>
  orders.value.filter(o => o.status === 'ready')
)

// Active = accepted by this driver (in_transit)
const activeDelivery = computed(() => {
  const active = orders.value.find(o => o.status === 'in_transit')
  return active ? mapOrderToDelivery(active) : null
})

// Delivered orders
const deliveredOrders = computed(() =>
  orders.value.filter(o => o.status === 'delivered')
)

// Today's earnings from delivered orders
const todayEarnings = computed(() => {
  const today = new Date().toDateString()
  return deliveredOrders.value
    .filter(o => new Date(o.updated_at || o.created_at).toDateString() === today)
    .reduce((sum: number, o: any) => sum + 50, 0) // ₱50 per delivery fee
})

const mapOrderToDelivery = (order: any) => ({
  id: String(order.id),
  time: formatTime(order.created_at),
  seller: order.seller?.name || 'OBRA Store',
  sellerAddress: 'Bagsakan Market, Urdaneta City',
  customer: order.customer?.name || 'Customer',
  customerAddress: order.delivery_address || 'Urdaneta City',
  items: order.items?.length || 0,
  distance: '~2 km',
  earnings: Number(order.delivery_fee) || 50,
  status: order.status,
  orderId: order.id,
})

const formatTime = (dateStr: string) => {
  if (!dateStr) return ''
  const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 60000)
  if (diff < 1) return 'Just now'
  if (diff < 60) return `${diff} min ago`
  return `${Math.floor(diff / 60)} hr ago`
}

const handleAccept = async (id: string) => {
  const order = orders.value.find(o => String(o.id) === id)
  if (!order) return
  try {
    await put(`/orders/${order.id}/status`, { status: 'in_transit' })
    order.status = 'in_transit'
  } catch (e) {
    console.error('Failed to accept delivery', e)
  }
}

const handleReject = (id: string) => {
  // Just remove from local list (no API endpoint for driver reject)
  orders.value = orders.value.filter(o => String(o.id) !== id)
}

const handlePickedUp = async (id: string) => {
  // Already in_transit, just a UI acknowledgment
  console.log('Picked up order', id)
}

const handleDelivered = async (id: string) => {
  const order = orders.value.find(o => String(o.id) === id)
  if (!order) return
  try {
    await put(`/orders/${order.id}/status`, { status: 'delivered' })
    order.status = 'delivered'
  } catch (e) {
    console.error('Failed to mark delivered', e)
  }
}
</script>