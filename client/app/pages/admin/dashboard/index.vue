<template>
  <AdminLayout title="Dashboard" subtitle="Welcome back, Admin!">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <AdminStatsCard
        v-for="stat in stats"
        :key="stat.label"
        :icon="stat.icon"
        :label="stat.label"
        :value="stat.value"
        :change="stat.change"
        :bg-color="stat.bgColor"
      />
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

      <!-- Platform Overview -->
      <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-base font-black text-gray-800 mb-4">Platform Overview</h3>
        <div class="flex flex-col gap-3">
          <div v-for="item in platformStats" :key="item.label">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs text-gray-500">{{ item.label }}</span>
              <span class="text-xs font-semibold text-gray-800">{{ item.value }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
              <div
                class="h-2 rounded-full transition-all duration-500"
                :style="{ width: item.percent + '%', backgroundColor: item.color }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-base font-black text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <NuxtLink
            v-for="action in quickActions"
            :key="action.label"
            :to="action.to"
            class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 hover:border-green-400 hover:shadow-md transition-all duration-200 group"
          >
            <span class="text-3xl">{{ action.icon }}</span>
            <span class="text-xs font-semibold text-gray-600 group-hover:text-green-600 text-center transition-colors">
              {{ action.label }}
            </span>
            <span
              v-if="action.badge"
              class="text-[10px] bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full"
            >
              {{ action.badge }} pending
            </span>
          </NuxtLink>
        </div>
      </div>

    </div>

    <!-- Recent Orders -->
    <div class="mb-8">
      <AdminRecentOrders />
    </div>

    <!-- Bottom Row: Top Sellers + Top Drivers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- Top Sellers -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Top Sellers</h3>
          <NuxtLink to="/admin/sellers" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
        </div>
        <div class="p-4 flex flex-col gap-3">
          <div
            v-for="(seller, index) in topSellers"
            :key="seller.name"
            class="flex items-center gap-3"
          >
            <span
              :class="[
                'w-7 h-7 rounded-full flex items-center justify-center text-xs font-black shrink-0 text-white',
                index === 0 ? 'bg-yellow-400' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-orange-400' : 'bg-gray-200'
              ]"
            >
              {{ index + 1 }}
            </span>
            <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
              {{ seller.name.charAt(0) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ seller.name }}</p>
              <p class="text-xs text-gray-400">{{ seller.orders }} orders</p>
            </div>
            <p class="text-sm font-bold text-green-600 shrink-0">₱{{ seller.revenue.toLocaleString() }}</p>
          </div>
        </div>
      </div>

      <!-- Top Drivers -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Top Drivers</h3>
          <NuxtLink to="/admin/drivers" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
        </div>
        <div class="p-4 flex flex-col gap-3">
          <div
            v-for="driver in topDrivers"
            :key="driver.name"
            class="flex items-center gap-3"
          >
            <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold text-sm shrink-0">
              {{ driver.name.charAt(0) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ driver.name }}</p>
              <p class="text-xs text-gray-400">{{ driver.deliveries }} deliveries</p>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <span class="text-yellow-400 text-xs">⭐</span>
              <span class="text-sm font-bold text-gray-700">{{ driver.rating }}</span>
            </div>
          </div>
        </div>
      </div>

    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'
import AdminStatsCard from '../../../components/admin/dashboard/AdminStatsCard.vue'
import AdminRecentOrders from '../../../components/admin/dashboard/AdminRecentOrders.vue'

const stats = [
  { icon: '💰', label: 'Total Revenue',   value: '₱124,500', change: 15.2,  bgColor: '#dcfce7' },
  { icon: '📦', label: 'Total Orders',    value: '1,284',    change: 8.4,   bgColor: '#dbeafe' },
  { icon: '🏪', label: 'Active Sellers',  value: '48',       change: 12.0,  bgColor: '#fef9c3' },
  { icon: '🛵', label: 'Active Drivers',  value: '23',       change: 4.5,   bgColor: '#fce7f3' },
]

const platformStats = [
  { label: 'Orders Completed',  value: '1,142', percent: 89, color: '#22c55e' },
  { label: 'Orders Pending',    value: '89',    percent: 7,  color: '#f59e0b' },
  { label: 'Orders Cancelled',  value: '53',    percent: 4,  color: '#ef4444' },
  { label: 'Sellers Active',    value: '48',    percent: 75, color: '#3b82f6' },
  { label: 'Drivers Online',    value: '23',    percent: 60, color: '#8b5cf6' },
]

const quickActions = [
  { icon: '👥', label: 'Manage Users',    to: '/admin/users',      badge: null },
  { icon: '🏪', label: 'Manage Sellers',  to: '/admin/sellers',    badge: '3'  },
  { icon: '🛵', label: 'Manage Drivers',  to: '/admin/drivers',    badge: null },
  { icon: '📦', label: 'View Orders',     to: '/admin/orders',     badge: '12' },
]

const topSellers = [
  { name: "Mang Bert's Wet Market",   orders: 284, revenue: 42600 },
  { name: "Aling Nena's Fish Hub",    orders: 198, revenue: 31200 },
  { name: "Kuya Jun's Vegetable",     orders: 165, revenue: 24750 },
  { name: "Lola Caring's Kakanin",    orders: 142, revenue: 21300 },
  { name: "Tatay Ben's Fruit Stand",  orders: 118, revenue: 17700 },
]

const topDrivers = [
  { name: 'Ramon Dela Cruz', deliveries: 124, rating: 4.9 },
  { name: 'Jose Mendoza',    deliveries: 98,  rating: 4.8 },
  { name: 'Carlos Reyes',    deliveries: 87,  rating: 4.7 },
  { name: 'Pedro Santos',    deliveries: 76,  rating: 4.6 },
  { name: 'Mario Cruz',      deliveries: 65,  rating: 4.5 },
]
</script>