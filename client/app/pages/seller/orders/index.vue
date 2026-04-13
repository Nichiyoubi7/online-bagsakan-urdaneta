<template>
  <SellerLayout title="Orders" subtitle="Manage your customer orders">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
      <div
        v-for="stat in orderStats"
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

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between px-5 py-4 border-b border-gray-100 gap-3">
        <h3 class="text-base font-black text-gray-800">All Orders</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input
              v-model="search"
              type="text"
              placeholder="Search orders..."
              class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-36"
            />
          </div>
          <select v-model="activeFilter" class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="preparing">Preparing</option>
            <option value="ready">Ready</option>
            <option value="in_transit">In Transit</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 5" :key="n" class="h-16 bg-gray-100 rounded-xl animate-pulse" />
      </div>

      <!-- Desktop Table -->
      <div v-else class="hidden md:block overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Order</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Customer</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Items</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Total</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr
              v-for="order in filteredOrders"
              :key="order.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <td class="px-5 py-4">
                <p class="text-sm font-semibold text-gray-800">#{{ order.id }}</p>
                <p class="text-xs text-gray-400">{{ formatDate(order.created_at) }}</p>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs shrink-0">
                    {{ order.customer?.name?.charAt(0) || 'C' }}
                  </div>
                  <div>
                    <span class="text-sm text-gray-700">{{ order.customer?.name || 'Customer' }}</span>
                    <!-- Note indicator on desktop -->
                    <div v-if="order.delivery_note" class="flex items-center gap-1 mt-0.5">
                      <span class="text-[10px] bg-yellow-100 text-yellow-700 font-semibold px-1.5 py-0.5 rounded-full">
                        📝 {{ order.delivery_note }}
                      </span>
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-gray-500">
                {{ order.items?.length || 0 }} item{{ order.items?.length !== 1 ? 's' : '' }}
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-800">
                ₱{{ Number(order.total).toFixed(2) }}
              </td>
              <td class="px-5 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold capitalize', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2 flex-wrap">
                  <template v-if="order.status === 'pending'">
                    <button @click="updateStatus(order.id, 'confirmed')"
                      class="text-xs bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Accept
                    </button>
                    <button @click="updateStatus(order.id, 'cancelled')"
                      class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Reject
                    </button>
                  </template>
                  <template v-else-if="order.status === 'confirmed'">
                    <button @click="updateStatus(order.id, 'preparing')"
                      class="text-xs bg-orange-500 hover:bg-orange-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Start Preparing
                    </button>
                  </template>
                  <template v-else-if="order.status === 'preparing'">
                    <button @click="updateStatus(order.id, 'ready')"
                      class="text-xs bg-purple-500 hover:bg-purple-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Mark Ready
                    </button>
                  </template>
                  <button @click="selectedOrder = order"
                    class="text-xs text-green-500 hover:text-green-600 font-semibold">
                    View
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile Cards -->
      <div v-if="!loading" class="md:hidden flex flex-col divide-y divide-gray-100">
        <div v-for="order in filteredOrders" :key="order.id" class="px-4 py-4">
          <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-bold text-gray-800">#{{ order.id }}</p>
            <span :class="['inline-flex px-2.5 py-1 rounded-full text-xs font-semibold capitalize', statusClass(order.status)]">
              {{ order.status }}
            </span>
          </div>
          <p class="text-sm text-gray-600 mb-1">{{ order.customer?.name || 'Customer' }}</p>
          <p class="text-sm font-bold text-green-600 mb-2">₱{{ Number(order.total).toFixed(2) }}</p>
          <!-- Note visible on mobile card -->
          <div v-if="order.delivery_note" class="mb-2 bg-yellow-50 border border-yellow-200 rounded-xl px-3 py-2">
            <p class="text-xs font-semibold text-yellow-700">📝 {{ order.delivery_note }}</p>
          </div>
          <div class="flex gap-2 flex-wrap">
            <template v-if="order.status === 'pending'">
              <button @click="updateStatus(order.id, 'confirmed')"
                class="text-xs bg-green-500 text-white font-semibold px-3 py-1.5 rounded-full">Accept</button>
              <button @click="updateStatus(order.id, 'cancelled')"
                class="text-xs bg-red-100 text-red-600 font-semibold px-3 py-1.5 rounded-full">Reject</button>
            </template>
            <template v-else-if="order.status === 'confirmed'">
              <button @click="updateStatus(order.id, 'preparing')"
                class="text-xs bg-orange-500 text-white font-semibold px-3 py-1.5 rounded-full">Start Preparing</button>
            </template>
            <template v-else-if="order.status === 'preparing'">
              <button @click="updateStatus(order.id, 'ready')"
                class="text-xs bg-purple-500 text-white font-semibold px-3 py-1.5 rounded-full">Mark Ready</button>
            </template>
            <button @click="selectedOrder = order"
              class="text-xs text-green-500 font-semibold">View</button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="!loading && filteredOrders.length === 0"
        class="flex flex-col items-center justify-center py-16 text-center">
        <span class="text-5xl mb-3">📦</span>
        <h3 class="text-base font-bold text-gray-700">No orders found</h3>
      </div>

    </div>

    <!-- Order Detail Modal -->
    <Transition name="fade">
      <div v-if="selectedOrder"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
        @click.self="selectedOrder = null">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
            <h3 class="text-lg font-black text-gray-800">Order #{{ selectedOrder.id }}</h3>
            <button @click="selectedOrder = null"
              class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500">✕</button>
          </div>
          <div class="px-6 py-5 flex flex-col gap-3 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-500">Customer</span>
              <span class="font-semibold">{{ selectedOrder.customer?.name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Status</span>
              <span :class="['px-2.5 py-0.5 rounded-full text-xs font-bold capitalize', statusClass(selectedOrder.status)]">
                {{ selectedOrder.status }}
              </span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Payment</span>
              <span class="font-semibold capitalize">{{ selectedOrder.payment_method || 'COD' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Delivery</span>
              <span class="font-semibold capitalize">{{ selectedOrder.delivery_type || 'delivery' }}</span>
            </div>
            <div v-if="selectedOrder.delivery_address" class="flex justify-between">
              <span class="text-gray-500">Address</span>
              <span class="font-semibold text-right max-w-[60%]">{{ selectedOrder.delivery_address }}</span>
            </div>
            <!-- Order Note in modal -->
            <div v-if="selectedOrder.delivery_note" class="bg-yellow-50 border border-yellow-200 rounded-xl px-3 py-2">
              <p class="text-xs font-semibold text-yellow-700 mb-0.5">📝 Customer Note</p>
              <p class="text-xs text-yellow-800">{{ selectedOrder.delivery_note }}</p>
            </div>
            <div class="border-t border-gray-100 pt-3">
              <p class="font-semibold text-gray-700 mb-2">Items</p>
              <div v-for="item in selectedOrder.items" :key="item.id" class="flex justify-between py-1">
                <span class="text-gray-600">{{ item.quantity }}x {{ item.product_name }}</span>
                <span class="font-semibold">₱{{ Number(item.subtotal).toFixed(2) }}</span>
              </div>
            </div>
            <div class="border-t border-gray-100 pt-3 flex justify-between font-black text-base">
              <span>Total</span>
              <span class="text-green-600">₱{{ Number(selectedOrder.total).toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>
    </Transition>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'

const { get, put } = useApi()

const loading = ref(true)
const orders = ref<any[]>([])
const search = ref('')
const activeFilter = ref('all')
const selectedOrder = ref<any>(null)

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

const orderStats = computed(() => [
  { icon: '📦', label: 'Total',     value: orders.value.length,                                        filter: 'all'       },
  { icon: '🆕', label: 'Pending',   value: orders.value.filter(o => o.status === 'pending').length,    filter: 'pending'   },
  { icon: '👨‍🍳', label: 'Preparing', value: orders.value.filter(o => o.status === 'preparing').length, filter: 'preparing' },
  { icon: '📫', label: 'Ready',     value: orders.value.filter(o => o.status === 'ready').length,      filter: 'ready'     },
  { icon: '✅', label: 'Delivered', value: orders.value.filter(o => o.status === 'delivered').length,  filter: 'delivered' },
])

const filteredOrders = computed(() => {
  let result = [...orders.value]
  if (activeFilter.value !== 'all') {
    result = result.filter(o => o.status === activeFilter.value)
  }
  if (search.value) {
    result = result.filter(o =>
      o.customer?.name?.toLowerCase().includes(search.value.toLowerCase()) ||
      String(o.id).includes(search.value)
    )
  }
  return result
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

const formatDate = (dateStr: string) => {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('en-PH', {
    month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
  })
}

const updateStatus = async (id: number, status: string) => {
  try {
    await put(`/orders/${id}/status`, { status })
    const order = orders.value.find(o => o.id === id)
    if (order) order.status = status
    if (selectedOrder.value?.id === id) selectedOrder.value.status = status
  } catch (e) {
    console.error('Failed to update order status', e)
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>