<template>
  <AdminLayout title="Orders" subtitle="All platform orders">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
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
            <input v-model="search" type="text" placeholder="Search orders..."
              class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-36" />
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
        <div v-for="n in 6" :key="n" class="h-14 bg-gray-100 rounded-xl animate-pulse" />
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Order ID</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Customer</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Seller</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Total</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Date</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="order in filteredOrders" :key="order.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs shrink-0">
                    {{ order.customer?.name?.charAt(0) || 'C' }}
                  </div>
                  <span class="text-sm text-gray-700">{{ order.customer?.name || 'N/A' }}</span>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ order.seller?.name || 'N/A' }}</td>
              <td class="px-5 py-4 text-sm font-bold text-gray-800">₱{{ Number(order.total).toFixed(2) }}</td>
              <td class="px-5 py-4 text-xs text-gray-500">{{ formatDate(order.created_at) }}</td>
              <td class="px-5 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <button @click="selectedOrder = order"
                    class="text-xs text-green-500 hover:text-green-600 font-semibold">View</button>
                  <button
                    v-if="!['delivered','cancelled'].includes(order.status)"
                    @click="cancelOrder(order.id)"
                    class="text-xs text-red-500 hover:text-red-600 font-semibold">Cancel</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="filteredOrders.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
          <span class="text-5xl mb-3">📦</span>
          <p class="text-base font-bold text-gray-700">No orders found</p>
        </div>
      </div>

    </div>

    <!-- Order Detail Modal -->
    <Transition name="fade">
      <div v-if="selectedOrder" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" @click.self="selectedOrder = null">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
            <h3 class="text-lg font-black text-gray-800">Order #{{ selectedOrder.id }}</h3>
            <button @click="selectedOrder = null" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500">✕</button>
          </div>
          <div class="px-6 py-5 flex flex-col gap-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Customer</span><span class="font-semibold">{{ selectedOrder.customer?.name }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Seller</span><span class="font-semibold">{{ selectedOrder.seller?.name || 'N/A' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Status</span>
              <span :class="['px-2.5 py-0.5 rounded-full text-xs font-bold', statusClass(selectedOrder.status)]">{{ selectedOrder.status }}</span>
            </div>
            <div class="flex justify-between"><span class="text-gray-500">Payment</span><span class="font-semibold capitalize">{{ selectedOrder.payment_method }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span class="font-semibold capitalize">{{ selectedOrder.delivery_type }}</span></div>
            <div v-if="selectedOrder.delivery_address"><span class="text-gray-500">Address: </span><span>{{ selectedOrder.delivery_address }}</span></div>
            <div class="border-t border-gray-100 pt-3">
              <p class="font-semibold text-gray-700 mb-2">Items</p>
              <div v-for="item in selectedOrder.items" :key="item.id" class="flex justify-between py-1">
                <span class="text-gray-600">{{ item.quantity }}x {{ item.product_name }}</span>
                <span class="font-semibold">₱{{ Number(item.subtotal).toFixed(2) }}</span>
              </div>
            </div>
            <div class="border-t border-gray-100 pt-3 flex justify-between font-black text-gray-800 text-base">
              <span>Total</span>
              <span class="text-green-600">₱{{ Number(selectedOrder.total).toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>
    </Transition>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const { get, put } = useApi()

const search = ref('')
const activeFilter = ref('all')
const selectedOrder = ref<any>(null)
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

const orderStats = computed(() => [
  { icon: '📦', label: 'Total',     value: orders.value.length,                                                     filter: 'all'       },
  { icon: '🕐', label: 'Pending',   value: orders.value.filter(o => o.status === 'pending').length,                 filter: 'pending'   },
  { icon: '✅', label: 'Delivered', value: orders.value.filter(o => o.status === 'delivered').length,               filter: 'delivered' },
  { icon: '❌', label: 'Cancelled', value: orders.value.filter(o => o.status === 'cancelled').length,               filter: 'cancelled' },
])

const filteredOrders = computed(() => {
  let result = [...orders.value]
  if (activeFilter.value !== 'all') result = result.filter(o => o.status === activeFilter.value)
  if (search.value) result = result.filter(o =>
    o.customer?.name?.toLowerCase().includes(search.value.toLowerCase()) ||
    String(o.id).includes(search.value)
  )
  return result
})

const statusClass = (status: string) => {
  const map: Record<string, string> = {
    'pending':   'bg-yellow-100 text-yellow-700',
    'confirmed': 'bg-blue-100 text-blue-700',
    'preparing': 'bg-orange-100 text-orange-700',
    'ready':     'bg-purple-100 text-purple-700',
    'in_transit':'bg-blue-100 text-blue-700',
    'delivered': 'bg-green-100 text-green-700',
    'cancelled': 'bg-red-100 text-red-700',
  }
  return map[status] ?? 'bg-gray-100 text-gray-700'
}

const formatDate = (dateStr: string) => {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('en-PH', {
    month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
  })
}

const cancelOrder = async (id: number) => {
  if (!confirm('Cancel this order?')) return
  try {
    await put(`/orders/${id}/status`, { status: 'cancelled' })
    const order = orders.value.find(o => o.id === id)
    if (order) order.status = 'cancelled'
  } catch (e) {
    console.error('Failed to cancel order', e)
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>