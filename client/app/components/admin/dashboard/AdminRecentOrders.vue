<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
      <h3 class="text-base font-black text-gray-800">Recent Orders</h3>
      <NuxtLink to="/admin/orders" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="p-4 flex flex-col gap-3">
      <div v-for="n in 5" :key="n" class="h-12 bg-gray-100 rounded-xl animate-pulse" />
    </div>

    <!-- Empty -->
    <div v-else-if="orders.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
      <span class="text-4xl mb-3">📦</span>
      <p class="text-sm text-gray-400">No orders yet</p>
    </div>

    <!-- Table -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Order</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Customer</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Seller</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Total</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-5 py-4 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
            <td class="px-5 py-4 text-sm text-gray-700">{{ order.customer?.name || 'N/A' }}</td>
            <td class="px-5 py-4 text-sm text-gray-600">{{ order.seller?.name || 'N/A' }}</td>
            <td class="px-5 py-4 text-sm font-bold text-gray-800">₱{{ Number(order.total).toFixed(2) }}</td>
            <td class="px-5 py-4">
              <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">
                {{ order.status }}
              </span>
            </td>
            <td class="px-5 py-4">
              <NuxtLink to="/admin/orders" class="text-xs text-green-500 hover:text-green-600 font-semibold">View</NuxtLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup lang="ts">
defineProps<{
  orders: any[]
  loading: boolean
}>()

const statusClass = (status: string) => {
  const map: Record<string, string> = {
    'pending':   'bg-yellow-100 text-yellow-700',
    'confirmed': 'bg-blue-100 text-blue-700',
    'preparing': 'bg-orange-100 text-orange-700',
    'ready':     'bg-purple-100 text-purple-700',
    'in_transit':'bg-blue-100 text-blue-700',
    'delivered': 'bg-green-100 text-green-700',
    'cancelled': 'bg-red-100 text-red-700',
  }
  return map[status] ?? 'bg-gray-100 text-gray-700'
}
</script>