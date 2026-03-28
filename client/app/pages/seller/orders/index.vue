<template>
  <SellerLayout title="Orders" subtitle="Manage your incoming orders">

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
      <div
        v-for="stat in orderStats"
        :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 cursor-pointer hover:border-green-400 transition-colors"
        @click="activeFilter = stat.filter"
        :class="activeFilter === stat.filter ? 'border-green-500 bg-green-50' : ''"
      >
        <span class="text-2xl">{{ stat.icon }}</span>
        <div>
          <p class="text-xl font-black text-gray-800">{{ stat.value }}</p>
          <p class="text-xs text-gray-400">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-black text-gray-800">
          {{ activeFilter === 'all' ? 'All Orders' : activeFilter + ' Orders' }}
        </h3>
        <div class="flex items-center gap-3">
          <!-- Search -->
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input
              v-model="search"
              type="text"
              placeholder="Search orders..."
              class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-36"
            />
          </div>
        </div>
      </div>

      <!-- Filter Tabs -->
      <div class="flex items-center gap-1 px-6 py-3 border-b border-gray-100 overflow-x-auto">
        <button
          v-for="tab in filterTabs"
          :key="tab.value"
          @click="activeFilter = tab.value"
          :class="[
            'px-4 py-1.5 rounded-full text-xs font-semibold transition-colors whitespace-nowrap',
            activeFilter === tab.value
              ? 'bg-green-500 text-white'
              : 'bg-gray-100 text-gray-500 hover:bg-gray-200'
          ]"
        >
          {{ tab.label }}
          <span v-if="tab.count > 0" class="ml-1 bg-white/30 px-1.5 rounded-full">
            {{ tab.count }}
          </span>
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Order ID</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Customer</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Items</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Total</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Date</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr
              v-for="order in filteredOrders"
              :key="order.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <!-- Order ID -->
              <td class="px-6 py-4 text-sm font-bold text-gray-800">#{{ order.id }}</td>

              <!-- Customer -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-xs shrink-0">
                    {{ order.customer.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">{{ order.customer }}</p>
                    <p class="text-xs text-gray-400">{{ order.phone }}</p>
                  </div>
                </div>
              </td>

              <!-- Items -->
              <td class="px-6 py-4">
                <div class="flex flex-col gap-0.5">
                  <p
                    v-for="item in order.items.slice(0, 2)"
                    :key="item"
                    class="text-xs text-gray-600"
                  >
                    {{ item }}
                  </p>
                  <p v-if="order.items.length > 2" class="text-xs text-gray-400">
                    +{{ order.items.length - 2 }} more
                  </p>
                </div>
              </td>

              <!-- Total -->
              <td class="px-6 py-4 text-sm font-bold text-gray-800">
                ₱{{ order.total.toFixed(2) }}
              </td>

              <!-- Date -->
              <td class="px-6 py-4 text-xs text-gray-500">
                {{ order.date }}
              </td>

              <!-- Status -->
              <td class="px-6 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', statusClass(order.status)]">
                  {{ order.status }}
                </span>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <!-- Pending Actions -->
                  <template v-if="order.status === 'Pending'">
                    <button
                      @click="updateStatus(order.id, 'Confirmed')"
                      class="text-xs bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors"
                    >
                      Accept
                    </button>
                    <button
                      @click="updateStatus(order.id, 'Cancelled')"
                      class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                    >
                      Reject
                    </button>
                  </template>

                  <!-- Confirmed Actions -->
                  <template v-else-if="order.status === 'Confirmed'">
                    <button
                      @click="updateStatus(order.id, 'Preparing')"
                      class="text-xs bg-orange-500 hover:bg-orange-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors"
                    >
                      Start Preparing
                    </button>
                  </template>

                  <!-- Preparing Actions -->
                  <template v-else-if="order.status === 'Preparing'">
                    <button
                      @click="updateStatus(order.id, 'Ready')"
                      class="text-xs bg-purple-500 hover:bg-purple-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors"
                    >
                      Mark Ready
                    </button>
                  </template>

                  <!-- View for other statuses -->
                  <button
                    @click="viewOrder(order)"
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

      <!-- Empty State -->
      <div
        v-if="filteredOrders.length === 0"
        class="flex flex-col items-center justify-center py-16 text-center"
      >
        <span class="text-5xl mb-3">📦</span>
        <h3 class="text-base font-bold text-gray-700 mb-1">No orders found</h3>
        <p class="text-sm text-gray-400">Orders will appear here when customers place them</p>
      </div>

    </div>

    <!-- Order Detail Modal -->
    <Transition name="fade">
      <div
        v-if="selectedOrder"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
        @click.self="selectedOrder = null"
      >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">

          <!-- Modal Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-800">Order #{{ selectedOrder.id }}</h3>
            <button
              @click="selectedOrder = null"
              class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center"
            >
              <span class="text-gray-600 text-sm">✕</span>
            </button>
          </div>

          <!-- Modal Content -->
          <div class="px-6 py-5 flex flex-col gap-4">

            <!-- Customer Info -->
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Customer</p>
              <p class="text-sm font-bold text-gray-800">{{ selectedOrder.customer }}</p>
              <p class="text-xs text-gray-500">{{ selectedOrder.phone }}</p>
              <p class="text-xs text-gray-500">{{ selectedOrder.address }}</p>
            </div>

            <!-- Order Items -->
            <div>
              <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Items Ordered</p>
              <div class="flex flex-col gap-2">
                <div
                  v-for="item in selectedOrder.items"
                  :key="item"
                  class="flex items-center justify-between text-sm"
                >
                  <span class="text-gray-700">{{ item }}</span>
                </div>
              </div>
            </div>

            <!-- Total -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
              <span class="text-sm font-semibold text-gray-700">Total Amount</span>
              <span class="text-lg font-black text-green-600">₱{{ selectedOrder.total.toFixed(2) }}</span>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Status</span>
              <span :class="['px-3 py-1 rounded-full text-xs font-bold', statusClass(selectedOrder.status)]">
                {{ selectedOrder.status }}
              </span>
            </div>

          </div>
        </div>
      </div>
    </Transition>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'

const search = ref('')
const activeFilter = ref('all')
const selectedOrder = ref<any>(null)

const orders = ref([
  { id: '2401', customer: 'Maria Santos',   phone: '+63 912 345 6789', address: 'Psu Executive Vlg, Urdaneta', items: ['2x Green Apple', '1x Red Tomatoes'],         total: 45.00,  date: 'Today, 10:30 AM',  status: 'Pending'   },
  { id: '2402', customer: 'Juan dela Cruz', phone: '+63 923 456 7890', address: 'Nancamaliran West, Urdaneta', items: ['3x Chinese Cabbage', '2x Green Chili'],       total: 75.00,  date: 'Today, 10:15 AM',  status: 'Pending'   },
  { id: '2403', customer: 'Ana Reyes',      phone: '+63 934 567 8901', address: 'San Vicente, Urdaneta',       items: ['1x Bangus', '2x Green Lettuce', '1x Mango'],  total: 130.00, date: 'Today, 9:45 AM',   status: 'Confirmed' },
  { id: '2404', customer: 'Pedro Cruz',     phone: '+63 945 678 9012', address: 'Pinmaludpod, Urdaneta',       items: ['5x Red Tomatoes'],                            total: 75.00,  date: 'Today, 9:30 AM',   status: 'Preparing' },
  { id: '2405', customer: 'Rosa Mendoza',   phone: '+63 956 789 0123', address: 'Camanang, Urdaneta',         items: ['2x Green Apple', '3x Eggplant'],               total: 75.00,  date: 'Today, 9:00 AM',   status: 'Ready'     },
  { id: '2406', customer: 'Carlos Reyes',   phone: '+63 967 890 1234', address: 'Nancamaliran East, Urdaneta',items: ['1x Bangus', '1x Tilapia'],                    total: 180.00, date: 'Yesterday, 5:00 PM',status: 'Delivered' },
  { id: '2407', customer: 'Lorna Santos',   phone: '+63 978 901 2345', address: 'Poblacion, Urdaneta',        items: ['4x Guijapur Mango'],                          total: 60.00,  date: 'Yesterday, 4:30 PM',status: 'Cancelled' },
])

const filterTabs = computed(() => [
  { label: 'All',       value: 'all',       count: orders.value.length },
  { label: 'Pending',   value: 'Pending',   count: orders.value.filter(o => o.status === 'Pending').length   },
  { label: 'Confirmed', value: 'Confirmed', count: orders.value.filter(o => o.status === 'Confirmed').length },
  { label: 'Preparing', value: 'Preparing', count: orders.value.filter(o => o.status === 'Preparing').length },
  { label: 'Ready',     value: 'Ready',     count: orders.value.filter(o => o.status === 'Ready').length     },
  { label: 'Delivered', value: 'Delivered', count: orders.value.filter(o => o.status === 'Delivered').length },
  { label: 'Cancelled', value: 'Cancelled', count: orders.value.filter(o => o.status === 'Cancelled').length },
])

const orderStats = computed(() => [
  { icon: '🆕', label: 'New Orders',   value: orders.value.filter(o => o.status === 'Pending').length,   filter: 'Pending'   },
  { icon: '✅', label: 'Confirmed',    value: orders.value.filter(o => o.status === 'Confirmed').length, filter: 'Confirmed' },
  { icon: '👨‍🍳', label: 'Preparing',  value: orders.value.filter(o => o.status === 'Preparing').length, filter: 'Preparing' },
  { icon: '📦', label: 'Ready',        value: orders.value.filter(o => o.status === 'Ready').length,     filter: 'Ready'     },
  { icon: '🚀', label: 'Delivered',    value: orders.value.filter(o => o.status === 'Delivered').length, filter: 'Delivered' },
])

const filteredOrders = computed(() => {
  let result = [...orders.value]
  if (activeFilter.value !== 'all') {
    result = result.filter(o => o.status === activeFilter.value)
  }
  if (search.value) {
    result = result.filter(o =>
      o.customer.toLowerCase().includes(search.value.toLowerCase()) ||
      o.id.includes(search.value)
    )
  }
  return result
})

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

const updateStatus = (id: string, status: string) => {
  const order = orders.value.find(o => o.id === id)
  if (order) order.status = status
}

const viewOrder = (order: any) => {
  selectedOrder.value = order
}
</script>