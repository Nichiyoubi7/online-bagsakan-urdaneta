<template>
  <StaffLayout>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white border border-gray-100 rounded-xl p-4 flex items-center gap-3"
        :style="{ background: stat.bg }"
      >
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-white/60">
          <span class="text-xl">{{ stat.emoji }}</span>
        </div>
        <div>
          <div class="text-lg font-black text-gray-800">{{ stat.value }}</div>
          <div class="text-xs text-gray-500">{{ stat.label }}</div>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="n in 2" :key="n" class="bg-white border border-gray-100 rounded-xl h-64 animate-pulse" />
    </div>

    <!-- Two Col -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">

      <!-- Recent Orders -->
      <div class="bg-white border border-gray-100 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
          <span class="font-semibold text-sm text-[#0f2d1f]">Recent Orders</span>
          <NuxtLink to="/staff/orders" class="text-xs text-green-600 font-medium">View all</NuxtLink>
        </div>
        <div v-if="recentOrders.length === 0" class="text-center py-8 text-sm text-gray-400">No orders yet</div>
        <table v-else class="w-full">
          <thead>
            <tr>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Order</th>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Customer</th>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Amount</th>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in recentOrders" :key="order.id" class="border-b border-gray-50 last:border-0">
              <td class="py-2.5 text-xs text-gray-400 font-mono">#{{ order.id }}</td>
              <td class="py-2.5 text-sm text-gray-600">{{ order.customer?.name || 'N/A' }}</td>
              <td class="py-2.5 text-sm text-gray-600">₱{{ Number(order.total).toFixed(2) }}</td>
              <td class="py-2.5">
                <span :class="['text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Bug Summary + Recent Users -->
      <div class="flex flex-col gap-4">

        <!-- Bug Summary -->
        <div class="bg-white border border-gray-100 rounded-xl p-5">
          <div class="font-semibold text-sm text-[#0f2d1f] mb-3">Bug Summary</div>
          <div class="flex items-center gap-3">
            <div v-for="b in bugSummary" :key="b.label" class="flex-1 flex flex-col items-center p-3 rounded-xl" :style="{ background: b.color + '15' }">
              <span class="text-2xl font-black" :style="{ color: b.color }">{{ b.count }}</span>
              <span class="text-[10px] text-gray-500 mt-1">{{ b.label }}</span>
            </div>
          </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white border border-gray-100 rounded-xl p-5 flex-1">
          <div class="flex items-center justify-between mb-3">
            <span class="font-semibold text-sm text-[#0f2d1f]">Recent Users</span>
            <NuxtLink to="/staff/customers" class="text-xs text-green-600 font-medium">View all</NuxtLink>
          </div>
          <div class="flex flex-col gap-2">
            <div v-for="user in recentUsers" :key="user.id" class="flex items-center gap-3">
              <div class="w-7 h-7 bg-green-50 text-green-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                {{ user.name?.charAt(0) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-700 truncate">{{ user.name }}</p>
                <p class="text-[11px] text-gray-400 truncate">{{ user.email }}</p>
              </div>
              <span class="text-[10px] bg-blue-50 text-blue-600 font-semibold px-2 py-0.5 rounded-full capitalize shrink-0">
                {{ user.roles?.[0]?.name || 'user' }}
              </span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'

const { get } = useApi()

const loading = ref(true)
const orders = ref([])
const users = ref([])

const loadData = async () => {
  loading.value = true
  try {
    const [ordersRes, usersRes] = await Promise.all([
      get('/orders'),
      get('/users'),
    ])
    orders.value = ordersRes.data || []
    users.value = usersRes.data || []
  } catch (e) {
    console.error('Failed to load staff dashboard data', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadData())

const recentOrders = computed(() => orders.value.slice(0, 5))
const recentUsers = computed(() => users.value.slice(0, 5))

const stats = computed(() => [
  {
    label: 'Total Orders',
    value: orders.value.length,
    bg: '#e6f4f1',
    emoji: '📦',
  },
  {
    label: 'Customers',
    value: users.value.filter(u => u.roles?.[0]?.name === 'customer').length,
    bg: '#e8f0fb',
    emoji: '👥',
  },
  {
    label: 'Pending Orders',
    value: orders.value.filter(o => o.status === 'pending').length,
    bg: '#fef3e6',
    emoji: '⏳',
  },
  {
    label: 'Delivered',
    value: orders.value.filter(o => o.status === 'delivered').length,
    bg: '#e6f4f1',
    emoji: '✅',
  },
])

const bugSummary = [
  { label: 'Open',        count: 5,  color: '#E24B4A' },
  { label: 'In Progress', count: 3,  color: '#EF9F27' },
  { label: 'Resolved',    count: 12, color: '#1D9E75' },
]

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
</script>