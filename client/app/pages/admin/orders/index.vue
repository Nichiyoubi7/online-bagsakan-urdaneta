<template>
  <AdminLayout title="All Orders" subtitle="Monitor all platform orders">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3"
      >
        <span class="text-2xl">{{ stat.icon }}</span>
        <div>
          <p class="text-xl font-black text-gray-800">{{ stat.value }}</p>
          <p class="text-xs text-gray-400">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-black text-gray-800">All Orders</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input v-model="search" type="text" placeholder="Search orders..." class="bg-transparent text-sm outline-none text-gray-700 w-36"/>
          </div>
          <select v-model="statusFilter" class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white">
            <option value="all">All Status</option>
            <option value="Pending">Pending</option>
            <option value="Confirmed">Confirmed</option>
            <option value="Preparing">Preparing</option>
            <option value="In Transit">In Transit</option>
            <option value="Delivered">Delivered</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>
      </div>

      <!-- Filter Tabs -->
      <div class="flex items-center gap-1 px-6 py-3 border-b border-gray-100 overflow-x-auto">
        <button
          v-for="tab in filterTabs"
          :key="tab.value"
          @click="statusFilter = tab.value"
          :class="[
            'px-4 py-1.5 rounded-full text-xs font-semibold transition-colors whitespace-nowrap',
            statusFilter === tab.value
              ? 'bg-green-500 text-white'
              : 'bg-gray-100 text-gray-500 hover:bg-gray-200'
          ]"
        >
          {{ tab.label }} {{ tab.count > 0 ? `(${tab.count})` : '' }}
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Order ID</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Customer</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Seller</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Driver</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Total</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Date</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="order in filteredOrders" :key="order.id" class="hover:bg-gray-50 transition-colors">

              <td class="px-6 py-4 text-sm font-bold text-gray-800">#{{ order.id }}</td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs shrink-0">
                    {{ order.customer.charAt(0) }}
                  </div>
                  <span class="text-sm text-gray-700">{{ order.customer }}</span>
                </div>
              </td>

              <td class="px-6 py-4 text-sm text-gray-600">{{ order.seller }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ order.driver }}</td>
              <td class="px-6 py-4 text-sm font-bold text-gray-800">₱{{ order.total.toFixed(2) }}</td>
              <td class="px-6 py-4 text-xs text-gray-500">{{ order.date }}</td>

              <td class="px-6 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <button class="text-xs text-green-500 hover:text-green-600 font-semibold">View</button>
                  <button
                    v-if="order.status !== 'Delivered' && order.status !== 'Cancelled'"
                    @click="cancelOrder(order.id)"
                    class="text-xs text-red-500 hover:text-red-600 font-semibold"
                  >
                    Cancel
                  </button>
                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="filteredOrders.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
        <span class="text-5xl mb-3">📦</span>
        <p class="text-base font-bold text-gray-700">No orders found</p>
      </div>

    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const search = ref('')
const statusFilter = ref('all')

const orders = ref([
  { id: '3001', customer: 'Maria Santos',   seller: "Mang Bert's",   driver: 'Ramon D.',  total: 145.00, date: 'Today, 10:30 AM',   status: 'Delivered'  },
  { id: '3002', customer: 'Juan dela Cruz', seller: "Aling Nena's",  driver: 'Jose M.',   total: 85.00,  date: 'Today, 10:15 AM',   status: 'In Transit' },
  { id: '3003', customer: 'Ana Reyes',      seller: "Kuya Jun's",    driver: 'Carlos R.', total: 320.00, date: 'Today, 9:45 AM',    status: 'Preparing'  },
  { id: '3004', customer: 'Pedro Cruz',     seller: "Lola Caring's", driver: 'Pending',   total: 15.00,  date: 'Today, 9:30 AM',    status: 'Confirmed'  },
  { id: '3005', customer: 'Rosa Mendoza',   seller: "Tatay Ben's",   driver: 'Pending',   total: 210.00, date: 'Today, 9:00 AM',    status: 'Pending'    },
  { id: '3006', customer: 'Carlos Reyes',   seller: "Mang Bert's",   driver: 'Pedro S.',  total: 95.00,  date: 'Yesterday, 5:30 PM',status: 'Delivered'  },
  { id: '3007', customer: 'Lorna Santos',   seller: "Aling Nena's",  driver: 'Mario C.',  total: 180.00, date: 'Yesterday, 5:00 PM',status: 'Cancelled'  },
])

const filterTabs = computed(() => [
  { label: 'All',        value: 'all',        count: orders.value.length },
  { label: 'Pending',    value: 'Pending',    count: orders.value.filter(o => o.status === 'Pending').length    },
  { label: 'Confirmed',  value: 'Confirmed',  count: orders.value.filter(o => o.status === 'Confirmed').length  },
  { label: 'Preparing',  value: 'Preparing',  count: orders.value.filter(o => o.status === 'Preparing').length  },
  { label: 'In Transit', value: 'In Transit', count: orders.value.filter(o => o.status === 'In Transit').length },
  { label: 'Delivered',  value: 'Delivered',  count: orders.value.filter(o => o.status === 'Delivered').length  },
  { label: 'Cancelled',  value: 'Cancelled',  count: orders.value.filter(o => o.status === 'Cancelled').length  },
])

const stats = computed(() => [
  { icon: '📦', label: 'Total Orders',   value: orders.value.length                                          },
  { icon: '🚀', label: 'In Transit',     value: orders.value.filter(o => o.status === 'In Transit').length   },
  { icon: '✅', label: 'Delivered Today', value: orders.value.filter(o => o.status === 'Delivered').length   },
  { icon: '❌', label: 'Cancelled',       value: orders.value.filter(o => o.status === 'Cancelled').length   },
])

const filteredOrders = computed(() => {
  let result = [...orders.value]
  if (statusFilter.value !== 'all') result = result.filter(o => o.status === statusFilter.value)
  if (search.value) result = result.filter(o =>
    o.customer.toLowerCase().includes(search.value.toLowerCase()) ||
    o.id.includes(search.value)
  )
  return result
})

const statusClass = (status: string) => {
  const classes: Record<string, string> = {
    'Pending':    'bg-yellow-100 text-yellow-700',
    'Confirmed':  'bg-blue-100 text-blue-700',
    'Preparing':  'bg-orange-100 text-orange-700',
    'In Transit': 'bg-purple-100 text-purple-700',
    'Delivered':  'bg-green-100 text-green-700',
    'Cancelled':  'bg-red-100 text-red-700',
  }
  return classes[status] ?? 'bg-gray-100 text-gray-700'
}

const cancelOrder = (id: string) => {
  const order = orders.value.find(o => o.id === id)
  if (order && confirm('Cancel this order?')) order.status = 'Cancelled'
}
</script>