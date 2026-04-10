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

      <!-- Empty -->
      <div v-else-if="filteredOrders.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
        <span class="text-5xl mb-3">📦</span>
        <p class="text-base font-bold text-gray-700">No orders found</p>
        <p class="text-sm text-gray-400">Orders will appear here when customers place them</p>
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
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Type</th>
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
                    <p class="text-sm text-gray-700">{{ order.customer?.name || 'Customer' }}</p>
                    <p class="text-xs text-gray-400">{{ order.customer?.phone || '—' }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="flex flex-col gap-0.5">
                  <span
                    v-for="item in order.items?.slice(0, 2)"
                    :key="item.id"
                    class="text-xs text-gray-600"
                  >
                    {{ item.product_name }} x{{ item.quantity }}
                  </span>
                  <span v-if="order.items?.length > 2" class="text-xs text-gray-400">
                    +{{ order.items.length - 2 }} more
                  </span>
                </div>
              </td>
              <td class="px-5 py-4">
                <p class="text-sm font-bold text-gray-800">₱{{ Number(order.total).toFixed(2) }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ order.payment_method }}</p>
              </td>
              <td class="px-5 py-4">
                <span :class="[
                  'text-xs font-semibold px-2 py-1 rounded-full capitalize',
                  order.delivery_type === 'delivery' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'
                ]">
                  {{ order.delivery_type }}
                </span>
              </td>
              <td class="px-5 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold capitalize', statusClass(order.status)]">
                  {{ statusLabel(order.status) }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <!-- Next action button based on current status -->
                  <button
                    v-if="nextAction(order.status)"
                    @click="updateStatus(order, nextAction(order.status).status)"
                    :disabled="updatingId === order.id"
                    :class="[
                      'text-xs font-semibold px-3 py-1.5 rounded-full transition-colors disabled:opacity-50',
                      nextAction(order.status).class
                    ]"
                  >
                    {{ updatingId === order.id ? '...' : nextAction(order.status).label }}
                  </button>
                  <!-- Cancel button for pending/confirmed only -->
                  <button
                    v-if="['pending', 'confirmed'].includes(order.status)"
                    @click="updateStatus(order, 'cancelled')"
                    :disabled="updatingId === order.id"
                    class="text-xs font-semibold px-3 py-1.5 rounded-full bg-red-100 hover:bg-red-200 text-red-600 transition-colors disabled:opacity-50"
                  >
                    Cancel
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile Cards -->
      <div class="md:hidden p-4 flex flex-col gap-3">
        <div
          v-for="order in filteredOrders"
          :key="order.id"
          class="border border-gray-100 rounded-xl p-4"
        >
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-sm font-black text-gray-800">#{{ order.id }}</p>
              <p class="text-xs text-gray-400">{{ formatDate(order.created_at) }}</p>
            </div>
            <span :class="['text-xs font-bold px-2.5 py-1 rounded-full capitalize', statusClass(order.status)]">
              {{ statusLabel(order.status) }}
            </span>
          </div>
          <p class="text-sm text-gray-700 mb-1">{{ order.customer?.name || 'Customer' }}</p>
          <p class="text-sm font-bold text-green-600 mb-3">₱{{ Number(order.total).toFixed(2) }}</p>
          <div class="flex gap-2">
            <button
              v-if="nextAction(order.status)"
              @click="updateStatus(order, nextAction(order.status).status)"
              :disabled="updatingId === order.id"
              :class="['flex-1 text-xs font-semibold py-2 rounded-xl transition-colors', nextAction(order.status).class]"
            >
              {{ updatingId === order.id ? '...' : nextAction(order.status).label }}
            </button>
            <button
              v-if="['pending', 'confirmed'].includes(order.status)"
              @click="updateStatus(order, 'cancelled')"
              class="text-xs font-semibold px-4 py-2 rounded-xl bg-red-100 text-red-600"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'

const { get, put } = useApi()
const loading = ref(true)
const orders = ref<any[]>([])
const activeFilter = ref('all')
const search = ref('')
const updatingId = ref<number | null>(null)

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

const orderStats = computed(() => [
  { icon: '📦', label: 'All Orders',  value: orders.value.length,                                                    filter: 'all'       },
  { icon: '🕐', label: 'Pending',     value: orders.value.filter(o => o.status === 'pending').length,                filter: 'pending'   },
  { icon: '🍳', label: 'Preparing',   value: orders.value.filter(o => ['confirmed','preparing','ready'].includes(o.status)).length, filter: 'preparing' },
  { icon: '🚀', label: 'In Transit',  value: orders.value.filter(o => o.status === 'in_transit').length,             filter: 'in_transit'},
  { icon: '✅', label: 'Delivered',   value: orders.value.filter(o => o.status === 'delivered').length,              filter: 'delivered' },
])

const filteredOrders = computed(() => {
  let result = [...orders.value]
  if (activeFilter.value !== 'all') {
    if (activeFilter.value === 'preparing') {
      result = result.filter(o => ['confirmed', 'preparing', 'ready'].includes(o.status))
    } else {
      result = result.filter(o => o.status === activeFilter.value)
    }
  }
  if (search.value) {
    result = result.filter(o =>
      String(o.id).includes(search.value) ||
      o.customer?.name?.toLowerCase().includes(search.value.toLowerCase())
    )
  }
  return result
})

// Define the next logical action for each status
const nextAction = (status: string) => {
  const map: Record<string, { label: string; status: string; class: string }> = {
    pending:    { label: 'Confirm',     status: 'confirmed', class: 'bg-blue-100 hover:bg-blue-200 text-blue-700' },
    confirmed:  { label: 'Prepare',     status: 'preparing', class: 'bg-orange-100 hover:bg-orange-200 text-orange-700' },
    preparing:  { label: 'Mark Ready',  status: 'ready',     class: 'bg-purple-100 hover:bg-purple-200 text-purple-700' },
    ready:      { label: 'Hand to Driver', status: 'in_transit', class: 'bg-blue-100 hover:bg-blue-200 text-blue-700' },
  }
  return map[status] || null
}

const updateStatus = async (order: any, status: string) => {
  if (!confirm(`Change order #${order.id} to "${statusLabel(status)}"?`)) return
  updatingId.value = order.id
  try {
    await put(`/orders/${order.id}/status`, { status })
    order.status = status
  } catch (e: any) {
    alert(e?.data?.message || 'Failed to update order status.')
  } finally {
    updatingId.value = null
  }
}

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', {
    month: 'short', day: 'numeric', year: 'numeric',
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
    in_transit: 'bg-indigo-100 text-indigo-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
  }
  return map[s] ?? 'bg-gray-100 text-gray-700'
}
</script>