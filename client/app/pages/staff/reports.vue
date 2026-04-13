<template>
  <StaffLayout>

    <div class="mb-6">
      <h1 class="text-xl font-semibold text-[#0f2d1f]">Reports</h1>
      <p class="text-sm text-gray-400 mt-0.5">Sales analytics and platform insights</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
      <div v-for="n in 4" :key="n" class="bg-white border border-gray-100 rounded-xl h-20 animate-pulse" />
    </div>

    <!-- Stats -->
    <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
      <div v-for="stat in stats" :key="stat.label"
        class="bg-white border border-gray-100 rounded-xl p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" :style="{ background: stat.bg }">
          <span class="text-lg">{{ stat.emoji }}</span>
        </div>
        <div>
          <div class="text-lg font-black text-gray-800">{{ stat.value }}</div>
          <div class="text-xs text-gray-400">{{ stat.label }}</div>
        </div>
      </div>
    </div>

    <!-- Orders by Status -->
    <div class="bg-white border border-gray-100 rounded-xl p-5 mb-4">
      <div class="font-semibold text-sm text-[#0f2d1f] mb-4">Orders by Status</div>
      <div class="flex flex-col gap-3">
        <div v-for="item in ordersByStatus" :key="item.label" class="flex items-center gap-3">
          <span class="text-xs text-gray-500 w-24 shrink-0">{{ item.label }}</span>
          <div class="flex-1 bg-gray-100 rounded-full h-2">
            <div class="h-2 rounded-full transition-all" :style="{ width: item.percent + '%', backgroundColor: item.color }" />
          </div>
          <span class="text-xs font-semibold text-gray-700 w-8 text-right">{{ item.count }}</span>
        </div>
      </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white border border-gray-100 rounded-xl p-5">
      <div class="font-semibold text-sm text-[#0f2d1f] mb-4">Recent Orders</div>
      <div v-if="loading" class="text-sm text-gray-400 text-center py-4">Loading...</div>
      <table v-else class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Order</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Customer</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Total</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in recentOrders" :key="order.id" class="border-b border-gray-50 last:border-0">
            <td class="py-2.5 text-xs text-gray-400 font-mono">#{{ order.id }}</td>
            <td class="py-2.5 text-sm text-gray-700">{{ order.customer?.name || 'N/A' }}</td>
            <td class="py-2.5 text-sm text-gray-700">₱{{ Number(order.total).toFixed(2) }}</td>
            <td class="py-2.5">
              <span :class="['text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize', statusClass(order.status)]">
                {{ order.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'

const { get } = useApi()
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

const totalRevenue = computed(() =>
  orders.value.filter(o => o.status === 'delivered')
    .reduce((s, o) => s + Number(o.total), 0)
)

const stats = computed(() => [
  { label: 'Total Revenue',   value: '₱' + totalRevenue.value.toLocaleString(), bg: '#e6f4f1', emoji: '💰' },
  { label: 'Total Orders',    value: orders.value.length,                        bg: '#e8f0fb', emoji: '📦' },
  { label: 'Delivered',       value: orders.value.filter(o => o.status === 'delivered').length,  bg: '#e6f4f1', emoji: '✅' },
  { label: 'Cancelled',       value: orders.value.filter(o => o.status === 'cancelled').length,  bg: '#fdeaea', emoji: '❌' },
])

const ordersByStatus = computed(() => {
  const total = orders.value.length || 1
  const statuses = [
    { label: 'Delivered',  color: '#22c55e', key: 'delivered'  },
    { label: 'Pending',    color: '#f59e0b', key: 'pending'    },
    { label: 'Preparing',  color: '#f97316', key: 'preparing'  },
    { label: 'In Transit', color: '#3b82f6', key: 'in_transit' },
    { label: 'Cancelled',  color: '#ef4444', key: 'cancelled'  },
  ]
  return statuses.map(s => ({
    ...s,
    count: orders.value.filter(o => o.status === s.key).length,
    percent: Math.round(orders.value.filter(o => o.status === s.key).length / total * 100),
  }))
})

const recentOrders = computed(() => orders.value.slice(0, 8))

const statusClass = (status) => {
  const map = {
    pending:   'bg-yellow-50 text-yellow-700',
    confirmed: 'bg-blue-50 text-blue-700',
    preparing: 'bg-orange-50 text-orange-700',
    in_transit:'bg-blue-50 text-blue-700',
    delivered: 'bg-green-50 text-green-700',
    cancelled: 'bg-red-50 text-red-700',
  }
  return map[status] ?? 'bg-gray-50 text-gray-700'
}
</script>