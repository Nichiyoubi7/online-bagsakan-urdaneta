<template>
  <AccountLayout page-title="Order History">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-black text-gray-800">Order History</h3>
      </div>

      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 5" :key="n" class="h-12 bg-gray-100 rounded-xl animate-pulse" />
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
            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm font-semibold text-gray-800">#{{ order.id }}</td>
              <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(order.created_at) }}</td>
              <td class="px-6 py-4 text-sm font-bold text-gray-800">
                ₱{{ Number(order.total).toFixed(2) }}
                <span class="text-xs text-gray-400 font-normal">({{ order.items?.length || 0 }} Products)</span>
              </td>
              <td class="px-6 py-4">
                <span :class="['inline-flex px-2.5 py-1 rounded-full text-xs font-semibold capitalize', statusClass(order.status)]">
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
            <tr v-if="orders.length === 0">
              <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">No orders yet</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="flex items-center justify-center gap-2 px-6 py-4 border-t border-gray-100">
        <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
          class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-green-500 disabled:opacity-40">
          ‹
        </button>
        <button v-for="p in totalPages" :key="p" @click="changePage(p)"
          :class="['w-8 h-8 rounded-full text-sm font-semibold transition-colors',
            p === currentPage ? 'bg-green-500 text-white' : 'border border-gray-200 text-gray-600 hover:border-green-500']">
          {{ p }}
        </button>
        <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
          class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-green-500 disabled:opacity-40">
          ›
        </button>
      </div>

    </div>
  </AccountLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AccountLayout from '../../../../components/customer/account/AccountLayout.vue'

const { get } = useApi()
const loading = ref(true)
const orders = ref<any[]>([])
const currentPage = ref(1)
const totalPages = ref(1)
const perPage = 10

onMounted(() => loadOrders())

const loadOrders = async () => {
  loading.value = true
  try {
    const res: any = await get('/orders', { page: currentPage.value, per_page: perPage })
    orders.value = res.data || []
    totalPages.value = res.last_page || 1
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

const changePage = (p: number) => {
  if (p < 1 || p > totalPages.value) return
  currentPage.value = p
  loadOrders()
}

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })
}

const statusClass = (s: string) => {
  const map: Record<string, string> = {
    pending:   'bg-yellow-100 text-yellow-700',
    confirmed: 'bg-blue-100 text-blue-700',
    preparing: 'bg-orange-100 text-orange-700',
    in_transit:'bg-blue-100 text-blue-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
  }
  return map[s] ?? 'bg-gray-100 text-gray-700'
}
</script>