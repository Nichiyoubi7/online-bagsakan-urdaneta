<template>
  <StaffLayout>

    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">All Orders</h1>
        <p class="text-sm text-gray-400 mt-0.5">Monitor and manage customer orders</p>
      </div>
      <select v-model="orderFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-600">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="preparing">Preparing</option>
        <option value="in_transit">In Transit</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex flex-col gap-3">
      <div v-for="n in 5" :key="n" class="bg-white border border-gray-100 rounded-xl h-12 animate-pulse" />
    </div>

    <!-- Table -->
    <div v-else class="bg-white border border-gray-100 rounded-xl p-5 overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Order ID</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Customer</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Seller</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Amount</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Status</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in filteredOrders" :key="order.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3 text-xs text-gray-400 font-mono">#{{ order.id }}</td>
            <td class="py-3 text-sm text-gray-700">{{ order.customer?.name || 'N/A' }}</td>
            <td class="py-3 text-sm text-gray-600">{{ order.seller?.name || 'N/A' }}</td>
            <td class="py-3 text-sm text-gray-700">₱{{ Number(order.total).toFixed(2) }}</td>
            <td class="py-3">
              <span :class="['text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize', statusClass(order.status)]">
                {{ order.status }}
              </span>
            </td>
            <td class="py-3 text-xs text-gray-400">{{ formatDate(order.created_at) }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="filteredOrders.length === 0" class="text-center py-10 text-sm text-gray-400">No orders found</div>
    </div>

  </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'

const { get } = useApi()
const orderFilter = ref('all')
const loading = ref(true)
const orders = ref([])

onMounted(async () => {
  try {
    const res = await get('/orders')
    orders.value = res.data || []
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

const filteredOrders = computed(() =>
  orderFilter.value === 'all'
    ? orders.value
    : orders.value.filter(o => o.status === orderFilter.value)
)

const statusClass = (status) => {
  const map = {
    pending:   'bg-yellow-50 text-yellow-700',
    confirmed: 'bg-blue-50 text-blue-700',
    preparing: 'bg-orange-50 text-orange-700',
    ready:     'bg-purple-50 text-purple-700',
    in_transit:'bg-blue-50 text-blue-700',
    delivered: 'bg-green-50 text-green-700',
    cancelled: 'bg-red-50 text-red-700',
  }
  return map[status] ?? 'bg-gray-50 text-gray-700'
}

const formatDate = (dateStr) => {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('en-PH', {
    month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
  })
}
</script>