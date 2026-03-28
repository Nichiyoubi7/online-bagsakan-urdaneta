<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
      <h3 class="text-base font-black text-gray-800">Recent Orders</h3>
      <NuxtLink to="/admin/orders" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
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
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ order.customer }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ order.seller }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ order.driver }}</td>
            <td class="px-6 py-4 text-sm font-bold text-gray-800">₱{{ order.total.toFixed(2) }}</td>
            <td class="px-6 py-4">
              <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">
                {{ order.status }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button class="text-xs text-green-500 hover:text-green-600 font-semibold">View</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup lang="ts">
const orders = [
  { id: '3001', customer: 'Maria Santos',   seller: "Mang Bert's",    driver: 'Ramon D.',  total: 145.00, status: 'Delivered'  },
  { id: '3002', customer: 'Juan dela Cruz', seller: "Aling Nena's",   driver: 'Jose M.',   total: 85.00,  status: 'In Transit' },
  { id: '3003', customer: 'Ana Reyes',      seller: "Kuya Jun's",     driver: 'Pending',   total: 320.00, status: 'Preparing'  },
  { id: '3004', customer: 'Pedro Cruz',     seller: "Lola Caring's",  driver: 'Pending',   total: 15.00,  status: 'Pending'    },
  { id: '3005', customer: 'Rosa Mendoza',   seller: "Tatay Ben's",    driver: 'Carlos R.', total: 210.00, status: 'Cancelled'  },
]

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
</script>