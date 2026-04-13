<template>
  <AccountLayout page-title="Dashboard">

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
      <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
        <div class="w-20 h-20 rounded-full bg-green-500 flex items-center justify-center text-white font-black text-3xl shrink-0 shadow-md">
          {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
        </div>
        <div class="flex-1">
          <h2 class="text-xl font-black text-gray-800">{{ authStore.user?.name }}</h2>
          <p class="text-sm text-gray-400">{{ authStore.user?.email }}</p>
          <NuxtLink to="/customer/account/settings"
            class="inline-block mt-2 text-xs text-green-600 font-semibold hover:underline">
            Edit Profile →
          </NuxtLink>
        </div>
        <div class="text-right">
          <p class="text-xs text-gray-400 mb-1">Billing Address</p>
          <p class="text-sm font-semibold text-gray-700">{{ authStore.user?.name }}</p>
          <p class="text-xs text-gray-400">Urdaneta City, Pangasinan</p>
          <p class="text-xs text-gray-400">{{ authStore.user?.email }}</p>
          <NuxtLink to="/customer/account/settings"
            class="inline-block mt-1 text-xs text-green-600 font-semibold hover:underline">
            Edit Address →
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- Recent Order History -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-black text-gray-800">Recent Order History</h3>
        <NuxtLink to="/customer/account/orders"
          class="text-sm text-green-500 font-semibold hover:underline">View All</NuxtLink>
      </div>

      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 4" :key="n" class="h-12 bg-gray-100 rounded-xl animate-pulse" />
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Order ID</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Date</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Total</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="order in recentOrders" :key="order.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
              <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(order.created_at) }}</td>
              <td class="px-6 py-4 text-sm font-bold text-gray-800">
                ₱{{ Number(order.total).toFixed(2) }}
                <span class="text-xs text-gray-400 font-normal">({{ order.items?.length || 0 }} Products)</span>
              </td>
              <td class="px-6 py-4">
                <span :class="['inline-flex px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-6 py-4">
                <NuxtLink :to="`/customer/account/orders/${order.id}`"
                  class="text-xs text-green-500 font-semibold hover:underline">
                  View Details
                </NuxtLink>
              </td>
            </tr>
            <tr v-if="recentOrders.length === 0">
              <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">No orders yet</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </AccountLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AccountLayout from '../../../components/customer/account/AccountLayout.vue'

const authStore = useAuthStore()
const { get } = useApi()

const loading = ref(true)
const recentOrders = ref<any[]>([])

onMounted(async () => {
  try {
    const res: any = await get('/orders')
    recentOrders.value = (res.data || []).slice(0, 5)
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' })
}

const statusClass = (s: string) => {
  const map: Record<string, string> = {
    pending:   'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-blue-100 text-blue-700',
    preparing: 'bg-orange-100 text-orange-700',
    ready:     'bg-purple-100 text-purple-700',
    in_transit:'bg-blue-100 text-blue-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
  }
  return map[s] ?? 'bg-gray-100 text-gray-700'
}
</script>