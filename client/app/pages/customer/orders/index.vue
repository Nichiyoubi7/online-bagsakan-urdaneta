<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">My Orders</span>
        </div>
        <h1 class="text-white text-2xl font-black mt-1">My Orders</h1>
      </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">

      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div
          v-for="stat in stats"
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

      <!-- Filter Tabs -->
      <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="activeFilter = tab.value"
          :class="[
            'px-4 py-2 rounded-full text-sm font-semibold transition-colors whitespace-nowrap',
            activeFilter === tab.value
              ? 'bg-green-500 text-white'
              : 'bg-white border border-gray-200 text-gray-500 hover:border-green-400'
          ]"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Orders List -->
      <div class="flex flex-col gap-4">

        <!-- Empty State -->
        <div
          v-if="filteredOrders.length === 0"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-20 text-center"
        >
          <span class="text-5xl mb-4">📦</span>
          <h3 class="text-lg font-bold text-gray-700 mb-2">No orders found</h3>
          <p class="text-sm text-gray-400 mb-6">You have no {{ activeFilter !== 'all' ? activeFilter.toLowerCase() : '' }} orders yet</p>
          <NuxtLink
            to="/customer"
            class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full transition-colors"
          >
            Start Shopping
          </NuxtLink>
        </div>

        <!-- Order Cards -->
        <div
          v-for="order in filteredOrders"
          :key="order.id"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >

          <!-- Order Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-4">
              <div>
                <p class="text-sm font-black text-gray-800">Order #{{ order.id }}</p>
                <p class="text-xs text-gray-400">{{ order.date }}</p>
              </div>
            </div>
            <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-bold', statusClass(order.status)]">
              {{ order.status }}
            </span>
          </div>

          <!-- Seller Info -->
          <div class="px-6 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="text-lg">🏪</span>
            <span class="text-sm font-semibold text-gray-700">{{ order.seller }}</span>
          </div>

          <!-- Order Items -->
          <div class="px-6 py-4">
            <div class="flex flex-col gap-3">
              <div
                v-for="item in order.items"
                :key="item.name"
                class="flex items-center gap-3"
              >
                <!-- Item Image -->
                <div class="w-14 h-14 bg-gray-50 rounded-xl overflow-hidden shrink-0">
                  <img
                    :src="item.image"
                    :alt="item.name"
                    class="w-full h-full object-contain p-1"
                  />
                </div>

                <!-- Item Info -->
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-gray-800 truncate">{{ item.name }}</p>
                  <p class="text-xs text-gray-400">x{{ item.quantity }}</p>
                </div>

                <!-- Item Price -->
                <p class="text-sm font-bold text-gray-800 shrink-0">
                  ₱{{ (item.price * item.quantity).toFixed(2) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Order Footer -->
          <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">

            <!-- Total -->
            <div>
              <p class="text-xs text-gray-400">Total Amount</p>
              <p class="text-lg font-black text-green-600">₱{{ order.total.toFixed(2) }}</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3">

              <!-- Track Order -->
              <button
                v-if="['Confirmed', 'Preparing', 'Ready', 'In Transit'].includes(order.status)"
                @click="trackOrder(order)"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                🛵 Track Order
              </button>

              <!-- Reorder -->
              <button
                v-if="order.status === 'Delivered'"
                @click="reorder(order)"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                🔄 Reorder
              </button>

              <!-- Rate -->
              <button
                v-if="order.status === 'Delivered' && !order.rated"
                @click="rateOrder(order)"
                class="flex items-center gap-2 border border-green-500 text-green-600 hover:bg-green-50 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                ⭐ Rate
              </button>

              <!-- Cancel -->
              <button
                v-if="order.status === 'Pending'"
                @click="cancelOrder(order.id)"
                class="flex items-center gap-2 border border-red-300 text-red-500 hover:bg-red-50 text-sm font-semibold px-4 py-2 rounded-full transition-colors"
              >
                Cancel
              </button>

              <!-- View Details -->
              <button
                @click="viewOrder(order)"
                class="text-sm text-gray-500 hover:text-green-600 font-semibold transition-colors"
              >
                View Details
              </button>

            </div>
          </div>

          <!-- Tracking Bar (for active orders) -->
          <div
            v-if="['Confirmed', 'Preparing', 'Ready', 'In Transit'].includes(order.status)"
            class="px-6 py-4 bg-green-50 border-t border-green-100"
          >
            <div class="flex items-center justify-between mb-2">
              <p class="text-xs font-semibold text-green-700">Order Progress</p>
              <p class="text-xs text-green-600">Est. {{ order.eta }}</p>
            </div>
            <div class="flex items-center gap-1">
              <div
                v-for="(step, index) in trackingSteps"
                :key="step"
                class="flex items-center flex-1"
              >
                <div
                  :class="[
                    'w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0 transition-colors',
                    isStepCompleted(order.status, index)
                      ? 'bg-green-500 text-white'
                      : 'bg-gray-200 text-gray-400'
                  ]"
                >
                  {{ index + 1 }}
                </div>
                <div
                  v-if="index < trackingSteps.length - 1"
                  :class="[
                    'flex-1 h-1 mx-1 rounded transition-colors',
                    isStepCompleted(order.status, index + 1) ? 'bg-green-500' : 'bg-gray-200'
                  ]"
                />
              </div>
            </div>
            <div class="flex items-center justify-between mt-1">
              <span
                v-for="step in trackingSteps"
                :key="step"
                class="text-[10px] text-gray-500 text-center"
                style="flex: 1"
              >
                {{ step }}
              </span>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Order Detail Modal -->
    <Transition name="fade">
      <div
        v-if="selectedOrder"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
        @click.self="selectedOrder = null"
      >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">

          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-800">Order #{{ selectedOrder.id }}</h3>
            <button @click="selectedOrder = null" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center">
              <span class="text-gray-600 text-sm">✕</span>
            </button>
          </div>

          <div class="px-6 py-5 flex flex-col gap-4">

            <!-- Status -->
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Status</span>
              <span :class="['px-3 py-1 rounded-full text-xs font-bold', statusClass(selectedOrder.status)]">
                {{ selectedOrder.status }}
              </span>
            </div>

            <!-- Seller -->
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Seller</span>
              <span class="text-sm text-gray-600">{{ selectedOrder.seller }}</span>
            </div>

            <!-- Date -->
            <div class="flex items-center justify-between">
              <span class="text-sm font-semibold text-gray-700">Order Date</span>
              <span class="text-sm text-gray-600">{{ selectedOrder.date }}</span>
            </div>

            <!-- Delivery Address -->
            <div class="bg-gray-50 rounded-xl p-3">
              <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Delivery Address</p>
              <p class="text-sm text-gray-700">{{ selectedOrder.address }}</p>
            </div>

            <!-- Items -->
            <div>
              <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Items</p>
              <div class="flex flex-col gap-2">
                <div
                  v-for="item in selectedOrder.items"
                  :key="item.name"
                  class="flex items-center justify-between text-sm"
                >
                  <span class="text-gray-700">{{ item.quantity }}x {{ item.name }}</span>
                  <span class="font-semibold text-gray-800">₱{{ (item.price * item.quantity).toFixed(2) }}</span>
                </div>
              </div>
            </div>

            <!-- Total -->
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
              <span class="text-sm font-bold text-gray-800">Total</span>
              <span class="text-xl font-black text-green-600">₱{{ selectedOrder.total.toFixed(2) }}</span>
            </div>

          </div>
        </div>
      </div>
    </Transition>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import GuestLayout from '../../../components/layout/GuestLayout.vue'

const activeFilter = ref('all')
const selectedOrder = ref<any>(null)

const trackingSteps = ['Confirmed', 'Preparing', 'Ready', 'Delivered']

const orders = ref([
  {
    id: '2401',
    seller: "Mang Bert's Wet Market",
    date: 'Today, 10:30 AM',
    eta: '20 mins',
    status: 'In Transit',
    address: 'Psu Executive Vlg, Urdaneta City',
    rated: false,
    total: 45.00,
    items: [
      { name: 'Green Apple',   price: 15.00, quantity: 2, image: '/images/products/green-apple.png'  },
      { name: 'Red Tomatoes',  price: 15.00, quantity: 1, image: '/images/products/tomatoes.png'     },
    ],
  },
  {
    id: '2398',
    seller: "Aling Nena's Fish Hub",
    date: 'Yesterday, 3:00 PM',
    eta: 'Delivered',
    status: 'Delivered',
    address: 'Psu Executive Vlg, Urdaneta City',
    rated: false,
    total: 180.00,
    items: [
      { name: 'Bangus',   price: 85.00, quantity: 1, image: '/images/products/bangus.png'  },
      { name: 'Tilapia',  price: 95.00, quantity: 1, image: '/images/products/tilapia.png' },
    ],
  },
  {
    id: '2385',
    seller: "Kuya Jun's Vegetable Stand",
    date: 'Mar 25, 9:00 AM',
    eta: 'Delivered',
    status: 'Delivered',
    address: 'Psu Executive Vlg, Urdaneta City',
    rated: true,
    total: 75.00,
    items: [
      { name: 'Green Lettuce',   price: 15.00, quantity: 3, image: '/images/products/lettuce.png'    },
      { name: 'Green Chili',     price: 15.00, quantity: 2, image: '/images/products/green-chili.png'},
    ],
  },
  {
    id: '2370',
    seller: "Mang Bert's Wet Market",
    date: 'Mar 24, 11:00 AM',
    eta: 'Cancelled',
    status: 'Cancelled',
    address: 'Psu Executive Vlg, Urdaneta City',
    rated: false,
    total: 30.00,
    items: [
      { name: 'Red Tomatoes', price: 15.00, quantity: 2, image: '/images/products/tomatoes.png' },
    ],
  },
  {
    id: '2410',
    seller: "Tatay Ben's Fruit Stand",
    date: 'Today, 11:00 AM',
    eta: '30 mins',
    status: 'Pending',
    address: 'Psu Executive Vlg, Urdaneta City',
    rated: false,
    total: 60.00,
    items: [
      { name: 'Guijapur Mango', price: 15.00, quantity: 4, image: '/images/products/mango.png' },
    ],
  },
])

const tabs = [
  { label: 'All Orders', value: 'all'        },
  { label: 'Active',     value: 'active'      },
  { label: 'Delivered',  value: 'Delivered'   },
  { label: 'Cancelled',  value: 'Cancelled'   },
]

const stats = computed(() => [
  { icon: '📦', label: 'Total Orders', value: orders.value.length,                                                                                           filter: 'all'       },
  { icon: '🚀', label: 'Active',       value: orders.value.filter(o => ['Confirmed','Preparing','Ready','In Transit','Pending'].includes(o.status)).length,  filter: 'active'    },
  { icon: '✅', label: 'Delivered',    value: orders.value.filter(o => o.status === 'Delivered').length,                                                      filter: 'Delivered' },
  { icon: '❌', label: 'Cancelled',    value: orders.value.filter(o => o.status === 'Cancelled').length,                                                      filter: 'Cancelled' },
])

const filteredOrders = computed(() => {
  if (activeFilter.value === 'all')       return orders.value
  if (activeFilter.value === 'active')    return orders.value.filter(o => ['Confirmed','Preparing','Ready','In Transit','Pending'].includes(o.status))
  if (activeFilter.value === 'Delivered') return orders.value.filter(o => o.status === 'Delivered')
  if (activeFilter.value === 'Cancelled') return orders.value.filter(o => o.status === 'Cancelled')
  return orders.value
})

const statusClass = (status: string) => {
  const classes: Record<string, string> = {
    'Pending':    'bg-yellow-100 text-yellow-700',
    'Confirmed':  'bg-blue-100 text-blue-700',
    'Preparing':  'bg-orange-100 text-orange-700',
    'Ready':      'bg-purple-100 text-purple-700',
    'In Transit': 'bg-blue-100 text-blue-700',
    'Delivered':  'bg-green-100 text-green-700',
    'Cancelled':  'bg-red-100 text-red-700',
  }
  return classes[status] ?? 'bg-gray-100 text-gray-700'
}

const isStepCompleted = (status: string, stepIndex: number) => {
  const statusOrder = ['Confirmed', 'Preparing', 'Ready', 'In Transit', 'Delivered']
  const currentIndex = statusOrder.indexOf(status)
  return currentIndex >= stepIndex
}

const trackOrder  = (order: any) => { alert(`Tracking order #${order.id}...`) }
const reorder     = (order: any) => { navigateTo('/customer/cart') }
const rateOrder   = (order: any) => { alert(`Rating order #${order.id}...`) }
const viewOrder   = (order: any) => { selectedOrder.value = order }
const cancelOrder = (id: string) => {
  if (confirm('Cancel this order?')) {
    const order = orders.value.find(o => o.id === id)
    if (order) order.status = 'Cancelled'
  }
}
</script>