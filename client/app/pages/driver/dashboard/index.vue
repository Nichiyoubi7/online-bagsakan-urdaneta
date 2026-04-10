<template>
  <DriverLayout>
    <div class="p-6">

      <!-- Header Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
          <span class="text-2xl">💰</span>
          <div>
            <p class="text-xl font-black text-gray-800">₱{{ todayEarnings }}</p>
            <p class="text-xs text-gray-400">Today's Earnings</p>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
          <span class="text-2xl">🚀</span>
          <div>
            <p class="text-xl font-black text-gray-800">{{ activeOrders.length }}</p>
            <p class="text-xs text-gray-400">Active Deliveries</p>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
          <span class="text-2xl">✅</span>
          <div>
            <p class="text-xl font-black text-gray-800">{{ deliveredOrders.length }}</p>
            <p class="text-xs text-gray-400">Completed</p>
          </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
          <span class="text-2xl">⭐</span>
          <div>
            <p class="text-xl font-black text-gray-800">4.9</p>
            <p class="text-xs text-gray-400">Rating</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: New Requests -->
        <div class="lg:col-span-2 flex flex-col gap-6">

          <!-- New Requests -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <h3 class="text-base font-black text-gray-800">New Requests</h3>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ readyOrders.length }}</span>
              </div>
              <span class="text-xs text-gray-400">First come, first served</span>
            </div>

            <div v-if="loading" class="p-4 flex flex-col gap-3">
              <div v-for="n in 2" :key="n" class="h-24 bg-gray-100 rounded-xl animate-pulse" />
            </div>

            <div v-else-if="readyOrders.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
              <span class="text-4xl mb-3">🛵</span>
              <p class="text-sm font-bold text-gray-600">No new requests</p>
              <p class="text-xs text-gray-400">Stay online to receive delivery requests</p>
            </div>

            <div v-else class="p-4 flex flex-col gap-3">
              <div
                v-for="order in readyOrders"
                :key="order.id"
                class="border-2 border-green-400 rounded-2xl p-4 bg-green-50"
              >
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <div class="flex items-center gap-2 mb-1">
                      <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                      <span class="text-xs font-bold text-green-600 uppercase">New Request</span>
                    </div>
                    <p class="text-sm font-black text-gray-800">Order #{{ order.id }}</p>
                    <p class="text-xs text-gray-400">{{ formatTime(order.created_at) }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-lg font-black text-green-600">₱{{ Number(order.delivery_fee) || 50 }}</p>
                    <p class="text-xs text-gray-400">Earnings</p>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                  <div class="bg-white rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Pick Up</p>
                    <p class="text-sm font-bold text-gray-800">{{ order.seller?.name || 'OBRA Store' }}</p>
                    <p class="text-xs text-gray-400">Bagsakan Market, Urdaneta City</p>
                  </div>
                  <div class="bg-white rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Drop Off</p>
                    <p class="text-sm font-bold text-gray-800">{{ order.customer?.name || 'Customer' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ order.delivery_address || 'Urdaneta City' }}</p>
                  </div>
                </div>

                <div class="flex items-center gap-4 mb-3 text-sm">
                  <span class="text-gray-500">{{ order.items?.length || 0 }} items</span>
                  <span class="text-gray-300">·</span>
                  <span class="text-gray-500">~2 km</span>
                  <span class="text-gray-300">·</span>
                  <span class="capitalize text-gray-500">{{ order.payment_method }}</span>
                </div>

                <div class="flex gap-3">
                  <button
                    @click="handleReject(order.id)"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors bg-white"
                  >
                    Decline
                  </button>
                  <button
                    @click="handleAccept(String(order.id))"
                    :disabled="acceptingId === order.id"
                    class="flex-1 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white text-sm font-bold transition-colors"
                  >
                    {{ acceptingId === order.id ? 'Accepting...' : 'Accept Delivery' }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Active Delivery -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-100">
              <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
              <h3 class="text-base font-black text-gray-800">Active Delivery</h3>
            </div>

            <div v-if="loading" class="p-4">
              <div class="h-24 bg-gray-100 rounded-xl animate-pulse" />
            </div>

            <div v-else-if="activeOrders.length === 0" class="flex flex-col items-center justify-center py-10 text-center">
              <span class="text-4xl mb-2">📦</span>
              <p class="text-sm text-gray-400">No active deliveries</p>
            </div>

            <div v-else class="p-4 flex flex-col gap-3">
              <div
                v-for="order in activeOrders"
                :key="order.id"
                class="border border-blue-200 rounded-2xl p-4 bg-blue-50"
              >
                <div class="flex items-center justify-between mb-3">
                  <div>
                    <p class="text-sm font-black text-gray-800">Order #{{ order.id }}</p>
                    <p class="text-xs text-gray-400">{{ formatTime(order.created_at) }}</p>
                  </div>
                  <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full">In Transit</span>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                  <div class="bg-white rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Pick Up</p>
                    <p class="text-sm font-bold text-gray-800">{{ order.seller?.name || 'OBRA Store' }}</p>
                  </div>
                  <div class="bg-white rounded-xl p-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Drop Off</p>
                    <p class="text-sm font-bold text-gray-800">{{ order.customer?.name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ order.delivery_address }}</p>
                  </div>
                </div>

                <button
                  @click="handleDelivered(String(order.id))"
                  :disabled="deliveringId === order.id"
                  class="w-full py-2.5 rounded-xl bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white text-sm font-bold transition-colors"
                >
                  {{ deliveringId === order.id ? 'Updating...' : 'Mark as Delivered ✅' }}
                </button>
              </div>
            </div>
          </div>

        </div>

        <!-- Right: Completed + Driver Info -->
        <div class="flex flex-col gap-6">

          <!-- Driver Info -->
          <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-5 text-white">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-green-600 font-black text-lg">
                {{ authStore.user?.name?.charAt(0) || 'D' }}
              </div>
              <div>
                <p class="font-black text-lg">{{ authStore.user?.name || 'Driver' }}</p>
                <p class="text-green-200 text-sm">{{ authStore.user?.email }}</p>
              </div>
            </div>
            <div class="bg-white/20 rounded-xl px-4 py-3 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 bg-green-300 rounded-full animate-pulse"></div>
                <span class="text-sm font-semibold">Online</span>
              </div>
              <span class="text-green-200 text-xs">Ready for deliveries</span>
            </div>
          </div>

          <!-- Completed Orders -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
              <h3 class="text-base font-black text-gray-800">Completed</h3>
              <span class="text-xs text-gray-400">{{ deliveredOrders.length }} total</span>
            </div>
            <div v-if="loading" class="p-4 flex flex-col gap-2">
              <div v-for="n in 3" :key="n" class="h-12 bg-gray-100 rounded-xl animate-pulse" />
            </div>
            <div v-else-if="deliveredOrders.length === 0" class="py-8 text-center text-sm text-gray-400">
              No completed deliveries yet
            </div>
            <div v-else class="divide-y divide-gray-50">
              <div
                v-for="order in deliveredOrders.slice(0, 5)"
                :key="order.id"
                class="px-5 py-3 flex items-center justify-between"
              >
                <div>
                  <p class="text-sm font-semibold text-gray-800">Order #{{ order.id }}</p>
                  <p class="text-xs text-gray-400">{{ order.customer?.name }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-bold text-green-600">₱{{ Number(order.delivery_fee) || 50 }}</p>
                  <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">Delivered</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </DriverLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DriverLayout from '../../../components/driver/layout/DriverLayout.vue'

const authStore = useAuthStore()
const { get, put } = useApi()

const loading = ref(true)
const orders = ref<any[]>([])
const acceptingId = ref<number | null>(null)
const deliveringId = ref<number | null>(null)

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 100 })
    orders.value = res.data || []
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadOrders())

const readyOrders = computed(() =>
  orders.value.filter(o => o.status === 'ready' && !o.driver_id)
)

const activeOrders = computed(() =>
  orders.value.filter(o => o.status === 'in_transit')
)

const deliveredOrders = computed(() =>
  orders.value.filter(o => o.status === 'delivered')
)

const todayEarnings = computed(() => {
  const today = new Date().toDateString()
  return deliveredOrders.value
    .filter(o => new Date(o.updated_at || o.created_at).toDateString() === today)
    .reduce((sum, o) => sum + (Number(o.delivery_fee) || 50), 0)
})

const handleAccept = async (id: string) => {
  const order = orders.value.find(o => String(o.id) === id)
  if (!order) return
  acceptingId.value = order.id
  try {
    await put(`/orders/${order.id}/status`, { status: 'in_transit' })
    order.status = 'in_transit'
    order.driver_id = authStore.user?.id
  } catch (e) {
    console.error('Failed to accept delivery', e)
  } finally {
    acceptingId.value = null
  }
}

const handleReject = (id: number) => {
  orders.value = orders.value.filter(o => o.id !== id)
}

const handleDelivered = async (id: string) => {
  const order = orders.value.find(o => String(o.id) === id)
  if (!order) return
  deliveringId.value = order.id
  try {
    await put(`/orders/${order.id}/status`, { status: 'delivered' })
    order.status = 'delivered'
  } catch (e) {
    console.error('Failed to mark delivered', e)
  } finally {
    deliveringId.value = null
  }
}

const formatTime = (dateStr: string) => {
  if (!dateStr) return ''
  const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 60000)
  if (diff < 1) return 'Just now'
  if (diff < 60) return `${diff} min ago`
  return `${Math.floor(diff / 60)} hr ago`
}
</script>
