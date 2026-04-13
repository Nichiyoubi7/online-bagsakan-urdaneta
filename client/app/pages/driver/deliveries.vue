<template>
  <DriverLayout>
    <div class="p-4">

      <h2 class="text-lg font-black text-gray-800 mb-4">My Deliveries</h2>

      <!-- Filter Tabs -->
      <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="activeTab = tab.key"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-colors shrink-0',
            activeTab === tab.key
              ? 'bg-green-500 text-white'
              : 'bg-white border border-gray-200 text-gray-600'
          ]"
        >
          {{ tab.label }} ({{ tab.count }})
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col gap-3">
        <div v-for="n in 4" :key="n" class="h-28 bg-gray-100 rounded-2xl animate-pulse" />
      </div>

      <!-- Empty -->
      <div v-else-if="filteredOrders.length === 0" class="flex flex-col items-center justify-center py-20 text-center text-gray-400">
        <span class="text-5xl mb-3">🛵</span>
        <p class="text-sm font-bold text-gray-600">No deliveries found</p>
        <p class="text-xs text-gray-400 mt-1">{{ activeTab === 'active' ? 'Accept orders from the dashboard' : 'Your completed deliveries will appear here' }}</p>
      </div>

      <!-- Orders List -->
      <div v-else class="flex flex-col gap-3">
        <div
          v-for="order in filteredOrders"
          :key="order.id"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4"
        >
          <!-- Header -->
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-sm font-black text-gray-800">Order #{{ order.id }}</p>
              <p class="text-xs text-gray-400">{{ formatTime(order.created_at) }}</p>
            </div>
            <span :class="statusClass(order.status)" class="text-xs font-bold px-3 py-1 rounded-full capitalize">
              {{ order.status.replace('_', ' ') }}
            </span>
          </div>

          <!-- Pickup + Dropoff -->
          <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="bg-green-50 rounded-xl p-3">
              <p class="text-[10px] font-bold text-green-600 uppercase mb-1">📦 Pick Up</p>
              <p class="text-sm font-semibold text-gray-800">{{ order.seller?.name || 'Seller' }}</p>
              <p class="text-xs text-gray-400">Bagsakan Market</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-3">
              <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">📍 Drop Off</p>
              <p class="text-sm font-semibold text-gray-800">{{ order.customer?.name || 'Customer' }}</p>
              <p class="text-xs text-gray-400 truncate">{{ order.delivery_address || 'Urdaneta City' }}</p>
            </div>
          </div>

          <!-- Details -->
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-3 text-gray-500 text-xs">
              <span>{{ order.items?.length || 0 }} items</span>
              <span>·</span>
              <span class="capitalize">{{ order.payment_method }}</span>
            </div>
            <p class="font-black text-green-600">₱{{ Number(order.delivery_fee) || 50 }} earned</p>
          </div>

          <!-- Action buttons for active orders -->
          <div v-if="order.status === 'in_transit'" class="mt-3">
            <button
              @click="markDelivered(order)"
              :disabled="deliveringId === order.id"
              class="w-full py-2.5 bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white text-sm font-bold rounded-xl transition-colors"
            >
              {{ deliveringId === order.id ? 'Updating...' : 'Mark as Delivered ✅' }}
            </button>
          </div>
        </div>
      </div>

    </div>
  </DriverLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DriverLayout from '~/components/driver/layout/DriverLayout.vue'

const { get, put } = useApi()
const authStore = useAuthStore()

const loading = ref(true)
const orders = ref<any[]>([])
const activeTab = ref('active')
const deliveringId = ref<number | null>(null)

const tabs = computed(() => [
  { key: 'active',    label: 'Active',    count: orders.value.filter(o => o.status === 'in_transit').length },
  { key: 'delivered', label: 'Completed', count: orders.value.filter(o => o.status === 'delivered').length  },
  { key: 'all',       label: 'All',       count: orders.value.length },
])

const filteredOrders = computed(() => {
  if (activeTab.value === 'active')    return orders.value.filter(o => o.status === 'in_transit')
  if (activeTab.value === 'delivered') return orders.value.filter(o => o.status === 'delivered')
  return orders.value
})

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 100 })
    orders.value = res.data || []
  } catch (e) {
    console.error('Failed to load deliveries', e)
  } finally {
    loading.value = false
  }
}

const markDelivered = async (order: any) => {
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

const statusClass = (status: string) => {
  const map: Record<string, string> = {
    in_transit: 'bg-blue-100 text-blue-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
  }
  return map[status] ?? 'bg-gray-100 text-gray-700'
}

const formatTime = (dateStr: string) => {
  if (!dateStr) return ''
  const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 60000)
  if (diff < 1) return 'Just now'
  if (diff < 60) return `${diff} min ago`
  if (diff < 1440) return `${Math.floor(diff / 60)} hr ago`
  return new Date(dateStr).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' })
}

onMounted(loadOrders)
</script>