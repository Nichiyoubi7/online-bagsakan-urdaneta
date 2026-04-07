<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
      <h3 class="text-base font-black text-gray-800">Recent Orders</h3>
      <NuxtLink to="/seller/orders" class="text-sm text-green-500 hover:text-green-600 font-semibold">View All →</NuxtLink>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="p-4 flex flex-col gap-3">
      <div v-for="n in 4" :key="n" class="h-14 bg-gray-100 rounded-xl animate-pulse" />
    </div>

    <!-- Empty -->
    <div v-else-if="orders.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
      <span class="text-5xl mb-3">📦</span>
      <p class="text-sm font-bold text-gray-700">No orders yet</p>
      <p class="text-xs text-gray-400">Orders will appear here when customers place them</p>
    </div>

    <!-- Content -->
    <template v-else>

      <!-- Table (desktop) -->
      <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Order</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Customer</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Total</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs shrink-0">
                    {{ order.customer?.name?.charAt(0) || 'C' }}
                  </div>
                  <span class="text-sm text-gray-700">{{ order.customer?.name || 'Customer' }}</span>
                </div>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-800">₱{{ Number(order.total).toFixed(2) }}</td>
              <td class="px-5 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <template v-if="order.status === 'pending'">
                    <button @click="$emit('updateStatus', order.id, 'confirmed')"
                      class="text-xs bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Accept
                    </button>
                    <button @click="$emit('updateStatus', order.id, 'cancelled')"
                      class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Reject
                    </button>
                  </template>
                  <template v-else-if="order.status === 'confirmed'">
                    <button @click="$emit('updateStatus', order.id, 'preparing')"
                      class="text-xs bg-orange-500 hover:bg-orange-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Start Preparing
                    </button>
                  </template>
                  <template v-else-if="order.status === 'preparing'">
                    <button @click="$emit('updateStatus', order.id, 'ready')"
                      class="text-xs bg-purple-500 hover:bg-purple-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors">
                      Mark Ready
                    </button>
                  </template>
                  <NuxtLink to="/seller/orders" class="text-xs text-green-500 hover:text-green-600 font-semibold">
                    View
                  </NuxtLink>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile Cards -->
      <div class="md:hidden flex flex-col divide-y divide-gray-100">
        <div v-for="order in orders" :key="order.id" class="px-4 py-4 flex items-center justify-between gap-3">
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-gray-800">#{{ order.id }} · {{ order.customer?.name || 'Customer' }}</p>
            <p class="text-xs text-gray-400">₱{{ Number(order.total).toFixed(2) }}</p>
          </div>
          <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shrink-0', statusClass(order.status)]">
            {{ order.status }}
          </span>
        </div>
      </div>

    </template>

  </div>
</template>

<script setup lang="ts">
defineProps<{
  orders: any[]
  loading: boolean
}>()

defineEmits<{
  (e: 'updateStatus', id: number, status: string): void
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