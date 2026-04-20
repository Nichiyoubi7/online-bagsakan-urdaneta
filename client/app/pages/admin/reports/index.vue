<template>
  <AdminLayout title="Reports" subtitle="Monthly sales and platform analytics">

    <!-- Month/Year Filter -->
    <div class="flex items-center gap-3 mb-6">
      <select v-model="selectedYear" class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white">
        <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
      </select>
      <select v-model="selectedMonth" class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white">
        <option value="all">All Months</option>
        <option v-for="(m, i) in months" :key="i" :value="i + 1">{{ m }}</option>
      </select>
      <span class="text-sm text-gray-400">{{ filteredOrders.length }} orders found</span>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div v-for="stat in stats" :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-400 mb-1">{{ stat.label }}</p>
        <p class="text-xl font-black text-gray-800">{{ stat.value }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

      <!-- Monthly Revenue Chart -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-black text-gray-800 mb-4">Monthly Revenue</h3>
        <div v-if="loading" class="h-48 bg-gray-100 rounded-xl animate-pulse" />
        <div v-else class="flex items-end gap-2 h-48">
          <div
            v-for="m in monthlyData"
            :key="m.label"
            class="flex-1 flex flex-col items-center gap-1"
          >
            <span class="text-[10px] text-gray-500 font-semibold">₱{{ m.revenue > 999 ? (m.revenue/1000).toFixed(1) + 'k' : m.revenue }}</span>
            <div
              class="w-full bg-green-500 rounded-t-lg transition-all"
              :style="{ height: m.revenue > 0 ? Math.max(8, (m.revenue / maxRevenue) * 160) + 'px' : '4px', opacity: m.revenue > 0 ? 1 : 0.2 }"
            />
            <span class="text-[10px] text-gray-400">{{ m.label }}</span>
          </div>
        </div>
      </div>

      <!-- Monthly Orders Chart -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-black text-gray-800 mb-4">Monthly Orders</h3>
        <div v-if="loading" class="h-48 bg-gray-100 rounded-xl animate-pulse" />
        <div v-else class="flex items-end gap-2 h-48">
          <div
            v-for="m in monthlyData"
            :key="m.label"
            class="flex-1 flex flex-col items-center gap-1"
          >
            <span class="text-[10px] text-gray-500 font-semibold">{{ m.orders }}</span>
            <div
              class="w-full bg-blue-500 rounded-t-lg transition-all"
              :style="{ height: m.orders > 0 ? Math.max(8, (m.orders / maxOrders) * 160) + 'px' : '4px', opacity: m.orders > 0 ? 1 : 0.2 }"
            />
            <span class="text-[10px] text-gray-400">{{ m.label }}</span>
          </div>
        </div>
      </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

      <!-- Payment Methods -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-black text-gray-800 mb-4">Payment Methods</h3>
        <div class="flex flex-col gap-4">
          <div v-for="method in paymentBreakdown" :key="method.label">
            <div class="flex items-center justify-between mb-1">
              <div class="flex items-center gap-2">
                <span class="text-lg">{{ method.icon }}</span>
                <span class="text-sm font-semibold text-gray-700">{{ method.label }}</span>
              </div>
              <div class="text-right">
                <span class="text-sm font-bold text-gray-800">{{ method.count }} orders</span>
                <span class="text-xs text-gray-400 ml-2">₱{{ method.revenue.toLocaleString() }}</span>
              </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
              <div class="h-2 rounded-full transition-all" :style="{ width: method.percent + '%', backgroundColor: method.color }" />
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-black text-gray-800 mb-4">Top Products</h3>
        <div class="flex flex-col gap-3">
          <div v-if="topProducts.length === 0" class="text-sm text-gray-400 text-center py-4">No data yet</div>
          <div v-for="(p, i) in topProducts" :key="p.name" class="flex items-center gap-3">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
              :class="i === 0 ? 'bg-yellow-400' : i === 1 ? 'bg-gray-400' : i === 2 ? 'bg-orange-400' : 'bg-gray-200 text-gray-600'">
              {{ i + 1 }}
            </span>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ p.name }}</p>
              <p class="text-xs text-gray-400">{{ p.sold }} units sold</p>
            </div>
            <p class="text-sm font-bold text-green-600 shrink-0">₱{{ p.revenue.toLocaleString() }}</p>
          </div>
        </div>
      </div>

    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-black text-gray-800">Order Details</h3>
        <span class="text-xs text-gray-400">{{ filteredOrders.length }} orders</span>
      </div>
      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 5" :key="n" class="h-12 bg-gray-100 rounded-xl animate-pulse" />
      </div>
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Order</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Customer</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Seller</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Payment</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Total</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="filteredOrders.length === 0">
              <td colspan="7" class="text-center py-10 text-sm text-gray-400">No orders found</td>
            </tr>
            <tr v-for="order in filteredOrders.slice(0, 50)" :key="order.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-3 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
              <td class="px-5 py-3 text-sm text-gray-600">{{ order.customer?.name || '—' }}</td>
              <td class="px-5 py-3 text-sm text-gray-600">{{ order.seller?.name || '—' }}</td>
              <td class="px-5 py-3">
                <span :class="['text-xs font-semibold px-2 py-1 rounded-full uppercase',
                  order.payment_method === 'gcash' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700']">
                  {{ order.payment_method }}
                </span>
              </td>
              <td class="px-5 py-3 text-sm font-bold text-green-600">₱{{ Number(order.total).toLocaleString() }}</td>
              <td class="px-5 py-3">
                <span :class="['text-xs font-semibold px-2 py-1 rounded-full capitalize', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-5 py-3 text-xs text-gray-500">{{ formatDate(order.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const { get } = useApi()

const loading      = ref(true)
const allOrders    = ref<any[]>([])
const selectedYear  = ref(new Date().getFullYear())
const selectedMonth = ref<number | 'all'>('all')

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
const years  = computed(() => {
  const y = new Date().getFullYear()
  return [y, y - 1, y - 2]
})

onMounted(async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 100 })
    allOrders.value = res.data || []
  } catch (e) {
    console.error('Failed to load orders', e)
  } finally {
    loading.value = false
  }
})

const filteredOrders = computed(() => {
  return allOrders.value.filter(o => {
    const d = new Date(o.created_at)
    const yearMatch  = d.getFullYear() === selectedYear.value
    const monthMatch = selectedMonth.value === 'all' || d.getMonth() + 1 === selectedMonth.value
    return yearMatch && monthMatch
  })
})

const deliveredOrders = computed(() => filteredOrders.value.filter(o => o.status === 'delivered'))

const stats = computed(() => [
  { label: 'Total Revenue',    value: '₱' + deliveredOrders.value.reduce((s, o) => s + Number(o.total), 0).toLocaleString() },
  { label: 'Total Orders',     value: filteredOrders.value.length },
  { label: 'Delivered',        value: deliveredOrders.value.length },
  { label: 'Cancelled',        value: filteredOrders.value.filter(o => o.status === 'cancelled').length },
])

const monthlyData = computed(() => {
  return months.map((label, i) => {
    const month = i + 1
    const monthOrders = allOrders.value.filter(o => {
      const d = new Date(o.created_at)
      return d.getFullYear() === selectedYear.value && d.getMonth() + 1 === month
    })
    const revenue = monthOrders
      .filter(o => o.status === 'delivered')
      .reduce((s, o) => s + Number(o.total), 0)
    return { label, orders: monthOrders.length, revenue }
  })
})

const maxRevenue = computed(() => Math.max(...monthlyData.value.map(m => m.revenue), 1))
const maxOrders  = computed(() => Math.max(...monthlyData.value.map(m => m.orders), 1))

const paymentBreakdown = computed(() => {
  const total = filteredOrders.value.length || 1
  const gcash = filteredOrders.value.filter(o => o.payment_method === 'gcash')
  const cod   = filteredOrders.value.filter(o => o.payment_method === 'cod')
  return [
    {
      label: 'GCash', icon: '📱', color: '#3b82f6',
      count: gcash.length,
      percent: Math.round(gcash.length / total * 100),
      revenue: gcash.filter(o => o.status === 'delivered').reduce((s, o) => s + Number(o.total), 0),
    },
    {
      label: 'Cash on Delivery', icon: '💵', color: '#22c55e',
      count: cod.length,
      percent: Math.round(cod.length / total * 100),
      revenue: cod.filter(o => o.status === 'delivered').reduce((s, o) => s + Number(o.total), 0),
    },
  ]
})

const topProducts = computed(() => {
  const map: Record<string, { name: string; sold: number; revenue: number }> = {}
  filteredOrders.value.forEach((order: any) => {
    order.items?.forEach((item: any) => {
      if (!map[item.product_name]) map[item.product_name] = { name: item.product_name, sold: 0, revenue: 0 }
      if (map[item.product_name]) {
        map[item.product_name].sold    += item.quantity
        map[item.product_name].revenue += Number(item.subtotal)
      }
    })
  })
  return Object.values(map).sort((a, b) => b.revenue - a.revenue).slice(0, 5)
})

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' })
}

const statusClass = (s: string) => {
  const map: Record<string, string> = {
    pending:    'bg-yellow-100 text-yellow-700',
    confirmed:  'bg-blue-100 text-blue-700',
    preparing:  'bg-orange-100 text-orange-700',
    ready:      'bg-purple-100 text-purple-700',
    in_transit: 'bg-blue-100 text-blue-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
  }
  return map[s] ?? 'bg-gray-100 text-gray-700'
}
</script>