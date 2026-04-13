<template>
  <DriverLayout>
    <div class="p-4">

      <h2 class="text-lg font-black text-gray-800 mb-4">My Earnings</h2>

      <!-- Period Filter -->
      <div class="flex gap-2 mb-5 overflow-x-auto pb-1">
        <button
          v-for="p in periods"
          :key="p.key"
          @click="selectedPeriod = p.key"
          :class="[
            'px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap shrink-0 transition-colors',
            selectedPeriod === p.key ? 'bg-green-500 text-white' : 'bg-white border border-gray-200 text-gray-600'
          ]"
        >
          {{ p.label }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col gap-4">
        <div v-for="n in 3" :key="n" class="h-24 bg-gray-100 rounded-2xl animate-pulse" />
      </div>

      <div v-else>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 gap-3 mb-5">
          <div class="bg-green-500 rounded-2xl p-4 text-white">
            <p class="text-xs font-semibold text-green-100 mb-1">{{ periodLabel }} Earnings</p>
            <p class="text-2xl font-black">₱{{ totalEarnings.toLocaleString() }}</p>
          </div>
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-400 mb-1">Deliveries</p>
            <p class="text-2xl font-black text-gray-800">{{ filteredDeliveries.length }}</p>
          </div>
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-400 mb-1">Avg Per Delivery</p>
            <p class="text-2xl font-black text-gray-800">₱{{ avgEarnings }}</p>
          </div>
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-400 mb-1">Best Day</p>
            <p class="text-2xl font-black text-gray-800">₱{{ bestDay }}</p>
          </div>
        </div>

        <!-- Bar Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
          <h3 class="text-sm font-black text-gray-800 mb-4">Earnings Overview</h3>
          <div v-if="chartData.length === 0" class="text-center py-8 text-sm text-gray-400">
            No earnings data yet
          </div>
          <div v-else class="flex items-end gap-2 h-32">
            <div v-for="bar in chartData" :key="bar.label" class="flex-1 flex flex-col items-center gap-1">
              <p class="text-[9px] text-gray-500">{{ bar.value > 0 ? '₱' + bar.value : '' }}</p>
              <div
                class="w-full bg-green-500 rounded-t-lg transition-all duration-500 min-h-[3px]"
                :style="{ height: `${maxBar > 0 ? (bar.value / maxBar) * 110 : 3}px` }"
              />
              <p class="text-[9px] text-gray-400">{{ bar.label }}</p>
            </div>
          </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-black text-gray-800">Recent Transactions</h3>
          </div>

          <div v-if="filteredDeliveries.length === 0" class="text-center py-10 text-sm text-gray-400">
            No deliveries in this period
          </div>

          <div v-else class="divide-y divide-gray-50">
            <div
              v-for="order in filteredDeliveries.slice(0, 15)"
              :key="order.id"
              class="px-4 py-3 flex items-center justify-between"
            >
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center text-green-700 text-sm font-bold shrink-0">
                  🛵
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">Order #{{ order.id }}</p>
                  <p class="text-xs text-gray-400">{{ order.customer?.name || 'Customer' }} · {{ formatDate(order.delivered_at || order.updated_at) }}</p>
                </div>
              </div>
              <p class="text-sm font-black text-green-600">+₱{{ Number(order.delivery_fee) || 50 }}</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </DriverLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DriverLayout from '~/components/driver/layout/DriverLayout.vue'

const { get } = useApi()
const loading = ref(true)
const allOrders = ref<any[]>([])
const selectedPeriod = ref('this_week')

const periods = [
  { key: 'today',      label: 'Today'      },
  { key: 'this_week',  label: 'This Week'  },
  { key: 'this_month', label: 'This Month' },
  { key: 'all_time',   label: 'All Time'   },
]

const periodLabel = computed(() => periods.find(p => p.key === selectedPeriod.value)?.label ?? '')

const deliveredOrders = computed(() => allOrders.value.filter(o => o.status === 'delivered'))

const filteredDeliveries = computed(() => {
  const now = new Date()
  return deliveredOrders.value.filter(o => {
    const date = new Date(o.delivered_at || o.updated_at || o.created_at)
    if (selectedPeriod.value === 'today')      return date.toDateString() === now.toDateString()
    if (selectedPeriod.value === 'this_week')  { const w = new Date(now); w.setDate(now.getDate() - 7); return date >= w }
    if (selectedPeriod.value === 'this_month') return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear()
    return true
  })
})

const totalEarnings = computed(() =>
  filteredDeliveries.value.reduce((s, o) => s + (Number(o.delivery_fee) || 50), 0)
)

const avgEarnings = computed(() => {
  if (filteredDeliveries.value.length === 0) return '0'
  return (totalEarnings.value / filteredDeliveries.value.length).toFixed(0)
})

const bestDay = computed(() => {
  const map: Record<string, number> = {}
  filteredDeliveries.value.forEach(o => {
    const day = new Date(o.delivered_at || o.updated_at || o.created_at).toDateString()
    map[day] = (map[day] || 0) + (Number(o.delivery_fee) || 50)
  })
  return Math.max(...Object.values(map), 0)
})

const chartData = computed(() => {
  const now = new Date()
  if (selectedPeriod.value === 'today') {
    const hours = Array.from({ length: 8 }, (_, i) => ({ label: `${i * 3}h`, value: 0 }))
    filteredDeliveries.value.forEach(o => {
      const h = new Date(o.delivered_at || o.created_at).getHours()
      hours[Math.floor(h / 3)].value += Number(o.delivery_fee) || 50
    })
    return hours
  } else if (selectedPeriod.value === 'this_week') {
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    const data = days.map(d => ({ label: d, value: 0 }))
    filteredDeliveries.value.forEach(o => {
      const day = new Date(o.delivered_at || o.created_at).getDay()
      data[day].value += Number(o.delivery_fee) || 50
    })
    return data
  } else {
    const weeks = ['Wk1', 'Wk2', 'Wk3', 'Wk4']
    const data = weeks.map(w => ({ label: w, value: 0 }))
    filteredDeliveries.value.forEach(o => {
      const day = new Date(o.delivered_at || o.created_at).getDate()
      data[Math.min(Math.floor((day - 1) / 7), 3)].value += Number(o.delivery_fee) || 50
    })
    return data
  }
})

const maxBar = computed(() => Math.max(...chartData.value.map(b => b.value), 1))

const formatDate = (dateStr: string) => {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' })
}

onMounted(async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 100 })
    allOrders.value = res.data || []
  } catch (e) {
    console.error('Failed to load earnings', e)
  } finally {
    loading.value = false
  }
})
</script>