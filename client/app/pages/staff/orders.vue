<template>
  <StaffLayout>

    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">All Orders</h1>
        <p class="text-sm text-gray-400 mt-0.5">Monitor and manage customer orders</p>
      </div>
      <select v-model="orderFilter" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-600">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="preparing">Preparing</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-xl p-5">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Order ID</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Customer</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Seller</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Driver</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Amount</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Status</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in filteredOrders" :key="order.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3 text-xs text-gray-400 font-mono">#{{ order.id }}</td>
            <td class="py-3 text-sm text-gray-700">{{ order.customer }}</td>
            <td class="py-3 text-sm text-gray-600">{{ order.seller }}</td>
            <td class="py-3 text-sm text-gray-600">{{ order.driver || '—' }}</td>
            <td class="py-3 text-sm text-gray-700">₱{{ order.amount }}</td>
            <td class="py-3">
              <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize"
                :class="{
                  'bg-green-50 text-green-700': order.status === 'delivered',
                  'bg-yellow-50 text-yellow-700': order.status === 'preparing',
                  'bg-blue-50 text-blue-700': order.status === 'pending',
                  'bg-red-50 text-red-600': order.status === 'cancelled',
                }">{{ order.status }}</span>
            </td>
            <td class="py-3 text-sm text-gray-400">{{ order.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup>
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'
import { ref, computed } from 'vue'

const orderFilter = ref('all')

const orders = ref([
  { id: '10042', customer: 'Maria Santos',   seller: "Aling Nena's", driver: 'Rodel M.',  amount: '350.00', status: 'delivered', date: 'Mar 27' },
  { id: '10041', customer: 'Juan dela Cruz', seller: 'Fresh Harvest', driver: null,        amount: '120.50', status: 'preparing', date: 'Mar 27' },
  { id: '10040', customer: 'Ana Reyes',      seller: 'Bagsak Goods',  driver: 'Danny R.', amount: '575.00', status: 'pending',   date: 'Mar 27' },
  { id: '10039', customer: 'Pedro Lim',      seller: "Aling Nena's", driver: null,         amount: '89.00',  status: 'cancelled', date: 'Mar 26' },
  { id: '10038', customer: 'Rosa Garcia',    seller: 'Green Market',  driver: 'Jose P.',  amount: '210.00', status: 'delivered', date: 'Mar 26' },
  { id: '10037', customer: 'Carlo Torres',   seller: 'Fresh Harvest', driver: 'Rodel M.', amount: '445.00', status: 'delivered', date: 'Mar 26' },
])

const filteredOrders = computed(() =>
  orderFilter.value === 'all' ? orders.value : orders.value.filter(o => o.status === orderFilter.value)
)
</script>