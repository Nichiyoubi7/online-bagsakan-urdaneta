<template>
  <SellerLayout title="Revenue" subtitle="Track your earnings and sales performance">

    <!-- Period Filter -->
    <div class="flex items-center gap-2 mb-6 flex-wrap">
      <button
        v-for="p in periods"
        :key="p.key"
        @click="selectedPeriod = p.key"
        :class="[
          'px-4 py-2 rounded-xl text-sm font-semibold transition-colors',
          selectedPeriod === p.key
            ? 'bg-green-500 text-white'
            : 'bg-white border border-gray-200 text-gray-600 hover:border-green-400'
        ]"
      >
        {{ p.label }}
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div v-for="n in 4" :key="n" class="h-28 bg-gray-100 rounded-2xl animate-pulse" />
    </div>

    <div v-else>

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div class="flex items-center justify-between mb-3">
            <span class="text-2xl">💰</span>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Revenue</span>
          </div>
          <p class="text-2xl font-black text-gray-800">₱{{ totalRevenue.toLocaleString() }}</p>
          <p class="text-xs text-gray-400 mt-1">{{ periodLabel }} earnings</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div class="flex items-center justify-between mb-3">
            <span class="text-2xl">📦</span>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Orders</span>
          </div>
          <p class="text-2xl font-black text-gray-800">{{ filteredOrders.length }}</p>
          <p class="text-xs text-gray-400 mt-1">{{ periodLabel }} orders</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div class="flex items-center justify-between mb-3">
            <span class="text-2xl">✅</span>
            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Delivered</span>
          </div>
          <p class="text-2xl font-black text-gray-800">{{ deliveredOrders.length }}</p>
          <p class="text-xs text-gray-400 mt-1">Completed orders</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div class="flex items-center justify-between mb-3">
            <span class="text-2xl">📊</span>
            <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">Avg</span>
          </div>
          <p class="text-2xl font-black text-gray-800">₱{{ avgOrderValue }}</p>
          <p class="text-xs text-gray-400 mt-1">Avg order value</p>
        </div>
      </div>

      <!-- Revenue Chart + Order Breakdown -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Revenue Bar Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h3 class="text-base font-black text-gray-800 mb-4">Revenue Overview</h3>

          <div v-if="chartData.length === 0" class="flex items-center justify-center h-40 text-gray-400 text-sm">
            No data for this period
          </div>

          <div v-else class="flex items-end gap-2 h-40">
            <div
              v-for="bar in chartData"
              :key="bar.label"
              class="flex-1 flex flex-col items-center gap-1"
            >
              <p class="text-[10px] text-gray-500 font-semibold">₱{{ bar.value > 999 ? (bar.value/1000).toFixed(1) + 'k' : bar.value }}</p>
              <div
                class="w-full bg-green-500 rounded-t-lg transition-all duration-500 min-h-[4px]"
                :style="{ height: `${maxBarValue > 0 ? (bar.value / maxBarValue) * 130 : 4}px` }"
              />
              <p class="text-[10px] text-gray-400">{{ bar.label }}</p>
            </div>
          </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h3 class="text-base font-black text-gray-800 mb-4">Order Status</h3>
          <div class="flex flex-col gap-3">
            <div v-for="s in orderStatusBreakdown" :key="s.label" class="flex flex-col gap-1">
              <div class="flex items-center justify-between text-xs">
                <span class="text-gray-600 font-medium">{{ s.label }}</span>
                <span class="font-bold text-gray-800">{{ s.count }}</span>
              </div>
              <div class="w-full bg-gray-100 rounded-full h-2">
                <div
                  class="h-2 rounded-full transition-all duration-500"
                  :style="{ width: `${s.percent}%`, backgroundColor: s.color }"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products + Recent Transactions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        <!-- Top Selling Products -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Top Selling Products</h3>
          </div>
          <div class="p-4 flex flex-col gap-3">
            <div v-if="topProducts.length === 0" class="text-center py-8 text-sm text-gray-400">No sales data yet</div>
            <div
              v-for="(product, index) in topProducts"
              :key="product.name"
              class="flex items-center gap-3 p-3 rounded-xl bg-gray-50"
            >
              <span :class="[
                'w-7 h-7 rounded-full flex items-center justify-center text-xs font-black shrink-0 text-white',
                index === 0 ? 'bg-yellow-400' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-orange-400' : 'bg-gray-200'
              ]">{{ index + 1 }}</span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ product.name }}</p>
                <p class="text-xs text-gray-400">{{ product.sold }} units sold</p>
              </div>
              <div class="text-right shrink-0">
                <p class="text-sm font-bold text-green-600">₱{{ Number(product.revenue).toLocaleString() }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Method Breakdown -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Payment Methods</h3>
          </div>
          <div class="p-5 flex flex-col gap-4">
            <div v-for="method in paymentBreakdown" :key="method.label" class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0" :style="{ backgroundColor: method.bg }">
                {{ method.icon }}
              </div>
              <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                  <p class="text-sm font-semibold text-gray-800">{{ method.label }}</p>
                  <p class="text-sm font-bold text-gray-800">{{ method.count }} orders</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                  <div class="h-2 rounded-full" :style="{ width: `${method.percent}%`, backgroundColor: method.color }" />
                </div>
                <p class="text-xs text-gray-400 mt-1">₱{{ method.revenue.toLocaleString() }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Transactions Table -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
          <h3 class="text-base font-black text-gray-800">Recent Transactions</h3>
          <NuxtLink to="/seller/orders" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Order</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Customer</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Items</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Payment</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Amount</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-if="recentTransactions.length === 0">
                <td colspan="7" class="text-center py-10 text-sm text-gray-400">No transactions yet</td>
              </tr>
              <tr v-for="order in recentTransactions" :key="order.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
                <td class="px-5 py-3 text-sm text-gray-600">{{ order.customer?.name ?? '—' }}</td>
                <td class="px-5 py-3 text-sm text-gray-600">{{ order.items?.length ?? 0 }} items</td>
                <td class="px-5 py-3">
                  <span class="text-xs font-semibold px-2 py-1 rounded-full uppercase"
                    :class="order.payment_method === 'gcash' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-600'">
                    {{ order.payment_method }}
                  </span>
                </td>
                <td class="px-5 py-3 text-sm font-bold text-green-600">₱{{ Number(order.total).toLocaleString() }}</td>
                <td class="px-5 py-3">
                  <span :class="statusClass(order.status)" class="text-xs font-semibold px-2 py-1 rounded-full capitalize">
                    {{ order.status.replace('_', ' ') }}
                  </span>
                </td>
                <td class="px-5 py-3 text-xs text-gray-400">
                  {{ new Date(order.created_at).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import SellerLayout from '../../components/seller/layout/SellerLayout.vue'

const { get } = useApi()
const loading = ref(true)
const allOrders = ref<any[]>([])
const selectedPeriod = ref('this_month')

const periods = [
  { key: 'today',      label: 'Today'      },
  { key: 'this_week',  label: 'This Week'  },
  { key: 'this_month', label: 'This Month' },
  { key: 'all_time',   label: 'All Time'   },
]

const periodLabel = computed(() => periods.find(p => p.key === selectedPeriod.value)?.label ?? '')

// Filter orders by selected period
const filteredOrders = computed(() => {
  const now = new Date()
  return allOrders.value.filter(o => {
    const date = new Date(o.created_at)
    if (selectedPeriod.value === 'today') {
      return date.toDateString() === now.toDateString()
    } else if (selectedPeriod.value === 'this_week') {
      const weekAgo = new Date(now)
      weekAgo.setDate(now.getDate() - 7)
      return date >= weekAgo
    } else if (selectedPeriod.value === 'this_month') {
      return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear()
    }
    return true // all_time
  })
})

const deliveredOrders = computed(() =>
  filteredOrders.value.filter(o => o.status === 'delivered')
)

const totalRevenue = computed(() =>
  deliveredOrders.value.reduce((s, o) => s + Number(o.total), 0)
)

const avgOrderValue = computed(() => {
  if (deliveredOrders.value.length === 0) return '0'
  return (totalRevenue.value / deliveredOrders.value.length).toFixed(0)
})

// Bar chart — last 7 days or last 4 weeks depending on period
const chartData = computed(() => {
  if (selectedPeriod.value === 'today') {
    // Hours 0–23
    const hours = Array.from({ length: 24 }, (_, i) => ({ label: `${i}h`, value: 0 }))
    deliveredOrders.value.forEach(o => {
      const h = new Date(o.created_at).getHours()
      hours[h].value += Number(o.total)
    })
    return hours.filter((_, i) => i % 3 === 0) // show every 3 hours
  } else if (selectedPeriod.value === 'this_week') {
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    const data = days.map(d => ({ label: d, value: 0 }))
    deliveredOrders.value.forEach(o => {
      const day = new Date(o.created_at).getDay()
      data[day].value += Number(o.total)
    })
    return data
  } else if (selectedPeriod.value === 'this_month') {
    // Weeks of the month
    const weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4']
    const data = weeks.map(w => ({ label: w, value: 0 }))
    deliveredOrders.value.forEach(o => {
      const day = new Date(o.created_at).getDate()
      const weekIndex = Math.min(Math.floor((day - 1) / 7), 3)
      data[weekIndex].value += Number(o.total)
    })
    return data
  } else {
    // All time — group by month
    const monthMap: Record<string, number> = {}
    deliveredOrders.value.forEach(o => {
      const d = new Date(o.created_at)
      const key = d.toLocaleDateString('en-PH', { month: 'short', year: '2-digit' })
      monthMap[key] = (monthMap[key] || 0) + Number(o.total)
    })
    return Object.entries(monthMap).slice(-6).map(([label, value]) => ({ label, value }))
  }
})

const maxBarValue = computed(() => Math.max(...chartData.value.map(b => b.value), 1))

const orderStatusBreakdown = computed(() => {
  const total = filteredOrders.value.length || 1
  const statuses = [
    { label: 'Delivered',  color: '#22c55e', key: 'delivered'  },
    { label: 'Pending',    color: '#f59e0b', key: 'pending'    },
    { label: 'Preparing',  color: '#f97316', key: 'preparing'  },
    { label: 'In Transit', color: '#3b82f6', key: 'in_transit' },
    { label: 'Cancelled',  color: '#ef4444', key: 'cancelled'  },
  ]
  return statuses.map(s => ({
    ...s,
    count: filteredOrders.value.filter(o => o.status === s.key).length,
    percent: Math.round(filteredOrders.value.filter(o => o.status === s.key).length / total * 100),
  }))
})

const topProducts = computed(() => {
  const map: Record<string, { name: string; sold: number; revenue: number }> = {}
  filteredOrders.value.forEach(o => {
    o.items?.forEach((item: any) => {
      if (!map[item.product_name]) map[item.product_name] = { name: item.product_name, sold: 0, revenue: 0 }
      map[item.product_name].sold += item.quantity
      map[item.product_name].revenue += Number(item.subtotal)
    })
  })
  return Object.values(map).sort((a, b) => b.revenue - a.revenue).slice(0, 5)
})

const paymentBreakdown = computed(() => {
  const total = filteredOrders.value.length || 1
  const gcash = filteredOrders.value.filter(o => o.payment_method === 'gcash')
  const cod   = filteredOrders.value.filter(o => o.payment_method === 'cod')
  return [
    {
      label:   'GCash',
      icon:    '📱',
      bg:      '#eff6ff',
      color:   '#3b82f6',
      count:   gcash.length,
      percent: Math.round(gcash.length / total * 100),
      revenue: gcash.filter(o => o.status === 'delivered').reduce((s, o) => s + Number(o.total), 0),
    },
    {
      label:   'Cash on Delivery',
      icon:    '💵',
      bg:      '#f0fdf4',
      color:   '#22c55e',
      count:   cod.length,
      percent: Math.round(cod.length / total * 100),
      revenue: cod.filter(o => o.status === 'delivered').reduce((s, o) => s + Number(o.total), 0),
    },
  ]
})

const recentTransactions = computed(() =>
  [...filteredOrders.value].sort((a, b) =>
    new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
  ).slice(0, 10)
)

const statusClass = (status: string) => {
  const map: Record<string, string> = {
    pending:    'bg-yellow-50 text-yellow-700',
    confirmed:  'bg-blue-50 text-blue-700',
    preparing:  'bg-orange-50 text-orange-700',
    ready:      'bg-purple-50 text-purple-700',
    in_transit: 'bg-blue-50 text-blue-700',
    delivered:  'bg-green-50 text-green-700',
    cancelled:  'bg-red-50 text-red-700',
  }
  return map[status] ?? 'bg-gray-50 text-gray-700'
}

onMounted(async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 100 })
    allOrders.value = res.data || []
  } catch (e) {
    console.error('Failed to load revenue data', e)
  } finally {
    loading.value = false
  }
})
</script>