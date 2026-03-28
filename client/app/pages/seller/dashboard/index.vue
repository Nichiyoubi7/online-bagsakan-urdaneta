<template>
  <SellerLayout title="Dashboard" subtitle="Welcome back, Mang Bert!">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <StatsCard
        v-for="stat in stats"
        :key="stat.label"
        :icon="stat.icon"
        :label="stat.label"
        :value="stat.value"
        :change="stat.change"
        :bg-color="stat.bgColor"
      />
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <NuxtLink
        v-for="action in quickActions"
        :key="action.label"
        :to="action.to"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col items-center gap-2 hover:border-green-400 hover:shadow-md transition-all duration-200 group"
      >
        <span class="text-3xl">{{ action.icon }}</span>
        <span class="text-sm font-semibold text-gray-700 group-hover:text-green-600 transition-colors">
          {{ action.label }}
        </span>
      </NuxtLink>
    </div>

    <!-- Recent Orders + Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Recent Orders — takes 2 columns -->
      <div class="lg:col-span-2">
        <RecentOrders />
      </div>

      <!-- Top Products -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Top Products</h3>
            <NuxtLink to="/seller/products" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
          </div>

          <div class="p-4 flex flex-col gap-3">
            <div
              v-for="(product, index) in topProducts"
              :key="product.name"
              class="flex items-center gap-3"
            >
              <!-- Rank -->
              <span
                :class="[
                  'w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0',
                  index === 0 ? 'bg-yellow-400 text-white' :
                  index === 1 ? 'bg-gray-300 text-white' :
                  index === 2 ? 'bg-orange-400 text-white' :
                  'bg-gray-100 text-gray-500'
                ]"
              >
                {{ index + 1 }}
              </span>

              <!-- Product Info -->
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ product.name }}</p>
                <p class="text-xs text-gray-400">{{ product.sold }} sold</p>
              </div>

              <!-- Revenue -->
              <p class="text-sm font-bold text-green-600 shrink-0">
                ₱{{ product.revenue.toFixed(2) }}
              </p>
            </div>
          </div>

        </div>

        <!-- Store Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mt-4">
          <h3 class="text-base font-black text-gray-800 mb-4">Store Status</h3>

          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div
                :class="[
                  'w-3 h-3 rounded-full',
                  isOpen ? 'bg-green-500 animate-pulse' : 'bg-gray-300'
                ]"
              ></div>
              <span class="text-sm font-semibold text-gray-700">
                {{ isOpen ? 'Store is Open' : 'Store is Closed' }}
              </span>
            </div>
            <button
              @click="isOpen = !isOpen"
              :class="[
                'relative w-12 h-6 rounded-full transition-colors duration-200',
                isOpen ? 'bg-green-500' : 'bg-gray-200'
              ]"
            >
              <span
                :class="[
                  'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200',
                  isOpen ? 'translate-x-6' : 'translate-x-0'
                ]"
              />
            </button>
          </div>

          <div class="flex flex-col gap-2 text-sm text-gray-500">
            <div class="flex items-center justify-between">
              <span>Today's Orders</span>
              <span class="font-semibold text-gray-800">24</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Pending</span>
              <span class="font-semibold text-yellow-600">5</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Completed</span>
              <span class="font-semibold text-green-600">19</span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'
import StatsCard from '../../../components/seller/dashboard/StatsCard.vue'
import RecentOrders from '../../../components/seller/dashboard/RecentOrders.vue'

const isOpen = ref(true)

const stats = [
  { icon: '💰', label: "Today's Revenue",  value: '₱2,450',  change: 12.5,  bgColor: '#dcfce7' },
  { icon: '📦', label: 'Total Orders',     value: '24',       change: 8.3,   bgColor: '#dbeafe' },
  { icon: '🛍️', label: 'Products Listed',  value: '48',       change: 4.0,   bgColor: '#fef9c3' },
  { icon: '⭐', label: 'Avg. Rating',      value: '4.8',      change: 2.1,   bgColor: '#fce7f3' },
]

const quickActions = [
  { icon: '➕', label: 'Add Product',   to: '/seller/products/create' },
  { icon: '📋', label: 'View Orders',   to: '/seller/orders' },
  { icon: '📊', label: 'Revenue',       to: '/seller/revenue' },
  { icon: '👤', label: 'Edit Profile',  to: '/seller/profile' },
]

const topProducts = [
  { name: 'Chinese Cabbage', sold: 145, revenue: 2175.00 },
  { name: 'Green Apple',     sold: 98,  revenue: 1470.00 },
  { name: 'Red Tomatoes',    sold: 87,  revenue: 1305.00 },
  { name: 'Green Lettuce',   sold: 76,  revenue: 1140.00 },
  { name: 'Eggplant',        sold: 65,  revenue: 975.00  },
]
</script>