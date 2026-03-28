<template>
  <StaffLayout>

    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">Overview</h1>
        <p class="text-sm text-gray-400 mt-0.5">Summary of platform activity</p>
      </div>
      <div class="flex items-center gap-3">
        <button class="relative w-9 h-9 bg-white border border-gray-200 rounded-lg flex items-center justify-center text-gray-400">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full border border-white"></span>
        </button>
        <span class="text-xs text-gray-400">{{ today }}</span>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-5">
      <div v-for="stat in stats" :key="stat.label" class="bg-white border border-gray-100 rounded-xl p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" :style="{ background: stat.bg }">
          <span v-html="stat.icon"></span>
        </div>
        <div class="flex-1">
          <div class="text-lg font-semibold text-[#0f2d1f]">{{ stat.value }}</div>
          <div class="text-xs text-gray-400">{{ stat.label }}</div>
        </div>
        <span class="text-xs font-medium px-2 py-1 rounded-full self-start" :class="stat.trend === 'up' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600'">{{ stat.change }}</span>
      </div>
    </div>

    <!-- Two Col -->
    <div class="grid grid-cols-2 gap-4">

      <!-- Recent Orders -->
      <div class="bg-white border border-gray-100 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
          <span class="font-semibold text-sm text-[#0f2d1f]">Recent Orders</span>
          <button class="text-xs text-green-600 font-medium">View all</button>
        </div>
        <table class="w-full">
          <thead>
            <tr>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Order ID</th>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Customer</th>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Amount</th>
              <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-2 border-b border-gray-50">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in recentOrders" :key="order.id" class="border-b border-gray-50 last:border-0">
              <td class="py-2.5 text-xs text-gray-400 font-mono">#{{ order.id }}</td>
              <td class="py-2.5 text-sm text-gray-600">{{ order.customer }}</td>
              <td class="py-2.5 text-sm text-gray-600">₱{{ order.amount }}</td>
              <td class="py-2.5">
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize"
                  :class="{
                    'bg-green-50 text-green-700': order.status === 'delivered',
                    'bg-yellow-50 text-yellow-700': order.status === 'preparing',
                    'bg-blue-50 text-blue-700': order.status === 'pending',
                    'bg-red-50 text-red-600': order.status === 'cancelled',
                  }">{{ order.status }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Bug Summary -->
      <div class="bg-white border border-gray-100 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
          <span class="font-semibold text-sm text-[#0f2d1f]">Bug Tracker</span>
          <button class="text-xs text-green-600 font-medium">View all</button>
        </div>
        <div class="flex gap-6 pb-4 mb-4 border-b border-gray-50">
          <div v-for="b in bugSummary" :key="b.label">
            <div class="text-xl font-bold" :style="{ color: b.color }">{{ b.count }}</div>
            <div class="text-[11px] text-gray-400 mt-0.5">{{ b.label }}</div>
          </div>
        </div>
        <div class="flex flex-col gap-2.5">
          <div v-for="bug in recentBugs" :key="bug.id" class="flex items-center gap-2.5">
            <span class="w-2 h-2 rounded-full shrink-0"
              :class="{ 'bg-red-500': bug.priority === 'high', 'bg-yellow-400': bug.priority === 'medium', 'bg-gray-300': bug.priority === 'low' }">
            </span>
            <div class="flex-1">
              <div class="text-xs text-gray-700">{{ bug.title }}</div>
              <div class="text-[11px] text-gray-400">{{ bug.page }} · {{ bug.date }}</div>
            </div>
            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize"
              :class="{
                'bg-red-50 text-red-600': bug.status === 'open',
                'bg-yellow-50 text-yellow-700': bug.status === 'in-progress',
                'bg-green-50 text-green-700': bug.status === 'resolved',
              }">{{ bug.status }}</span>
          </div>
        </div>
      </div>

    </div>

  </StaffLayout>
</template>

<script setup>
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'

const today = new Date().toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })

const stats = [
  { label: 'Total Orders Today', value: '248',   change: '+12%', trend: 'up',   bg: '#e6f4f1', icon: '<svg width="18" height="18" fill="none" stroke="#1D9E75" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>' },
  { label: 'Active Customers',   value: '1,042', change: '+5%',  trend: 'up',   bg: '#e8f0fb', icon: '<svg width="18" height="18" fill="none" stroke="#185FA5" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>' },
  { label: 'Open Bug Reports',   value: '5',     change: '-2',   trend: 'down', bg: '#fef3e6', icon: '<svg width="18" height="18" fill="none" stroke="#BA7517" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>' },
  { label: 'Pending Approvals',  value: '3',     change: '+3',   trend: 'up',   bg: '#fdeaea', icon: '<svg width="18" height="18" fill="none" stroke="#A32D2D" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/><line x1="20" y1="8" x2="20" y2="14"/></svg>' },
]

const recentOrders = [
  { id: '10042', customer: 'Maria Santos',   amount: '350.00', status: 'delivered' },
  { id: '10041', customer: 'Juan dela Cruz', amount: '120.50', status: 'preparing' },
  { id: '10040', customer: 'Ana Reyes',      amount: '575.00', status: 'pending'   },
  { id: '10039', customer: 'Pedro Lim',      amount: '89.00',  status: 'cancelled' },
  { id: '10038', customer: 'Rosa Garcia',    amount: '210.00', status: 'delivered' },
]

const bugSummary = [
  { label: 'Open',        count: 5,  color: '#E24B4A' },
  { label: 'In Progress', count: 3,  color: '#EF9F27' },
  { label: 'Resolved',    count: 12, color: '#1D9E75' },
]

const recentBugs = [
  { id: 1, title: 'Checkout fails on mobile', page: 'Cart',         priority: 'high',   status: 'open',        date: 'Mar 27' },
  { id: 2, title: 'Image not loading',        page: 'Product Page', priority: 'medium', status: 'in-progress', date: 'Mar 26' },
  { id: 3, title: 'OTP delay on register',    page: 'Auth Modal',   priority: 'high',   status: 'open',        date: 'Mar 25' },
]
</script>