<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
      <h3 class="text-base font-black text-gray-800">Recent Orders</h3>
      <NuxtLink
        to="/seller/orders"
        class="text-sm text-green-500 hover:text-green-600 font-semibold transition-colors"
      >
        View All →
      </NuxtLink>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Order ID</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Customer</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Items</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Total</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr
            v-for="order in orders"
            :key="order.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <!-- Order ID -->
            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
              #{{ order.id }}
            </td>

            <!-- Customer -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs shrink-0">
                  {{ order.customer.charAt(0) }}
                </div>
                <span class="text-sm text-gray-700">{{ order.customer }}</span>
              </div>
            </td>

            <!-- Items -->
            <td class="px-6 py-4 text-sm text-gray-500">
              {{ order.items }} item{{ order.items > 1 ? 's' : '' }}
            </td>

            <!-- Total -->
            <td class="px-6 py-4 text-sm font-bold text-gray-800">
              ₱{{ order.total.toFixed(2) }}
            </td>

            <!-- Status -->
            <td class="px-6 py-4">
              <span
                :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                  statusClass(order.status)
                ]"
              >
                {{ order.status }}
              </span>
            </td>

            <!-- Action -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  v-if="order.status === 'Pending'"
                  @click="acceptOrder(order.id)"
                  class="text-xs bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors"
                >
                  Accept
                </button>
                <button
                  v-if="order.status === 'Pending'"
                  @click="rejectOrder(order.id)"
                  class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                >
                  Reject
                </button>
                <button
                  v-if="order.status !== 'Pending'"
                  class="text-xs text-green-500 hover:text-green-600 font-semibold transition-colors"
                >
                  View
                </button>
              </div>
            </td>

          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const orders = ref([
  { id: '2401', customer: 'Maria Santos',   items: 3, total: 145.00, status: 'Pending'    },
  { id: '2402', customer: 'Juan dela Cruz', items: 2, total: 85.00,  status: 'Confirmed'  },
  { id: '2403', customer: 'Ana Reyes',      items: 5, total: 320.00, status: 'Preparing'  },
  { id: '2404', customer: 'Pedro Cruz',     items: 1, total: 15.00,  status: 'Ready'      },
  { id: '2405', customer: 'Rosa Mendoza',   items: 4, total: 210.00, status: 'Delivered'  },
  { id: '2406', customer: 'Carlos Reyes',   items: 2, total: 95.00,  status: 'Cancelled'  },
])

const statusClass = (status: string) => {
  const classes: Record<string, string> = {
    'Pending':   'bg-yellow-100 text-yellow-700',
    'Confirmed': 'bg-blue-100 text-blue-700',
    'Preparing': 'bg-orange-100 text-orange-700',
    'Ready':     'bg-purple-100 text-purple-700',
    'Delivered': 'bg-green-100 text-green-700',
    'Cancelled': 'bg-red-100 text-red-700',
  }
  return classes[status] ?? 'bg-gray-100 text-gray-700'
}

const acceptOrder = (id: string) => {
  const order = orders.value.find(o => o.id === id)
  if (order) order.status = 'Confirmed'
}

const rejectOrder = (id: string) => {
  const order = orders.value.find(o => o.id === id)
  if (order) order.status = 'Cancelled'
}
</script>