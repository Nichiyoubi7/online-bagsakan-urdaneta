<template>
  <StaffLayout>

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-xl font-semibold text-[#0f2d1f]">Reports</h1>
      <p class="text-sm text-gray-400 mt-0.5">Sales analytics and platform insights</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-5">
      <div v-for="stat in stats" :key="stat.label" class="bg-white border border-gray-100 rounded-xl p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" :style="{ background: stat.bg }">
          <span v-html="stat.icon"></span>
        </div>
        <div>
          <div class="text-lg font-semibold text-[#0f2d1f]">{{ stat.value }}</div>
          <div class="text-xs text-gray-400">{{ stat.label }}</div>
        </div>
      </div>
    </div>

    <!-- Revenue per Seller -->
    <div class="bg-white border border-gray-100 rounded-xl p-5">
      <div class="font-semibold text-sm text-[#0f2d1f] mb-4">Revenue per Seller</div>
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Seller</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Orders</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Revenue</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Rating</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="s in sellerRevenue" :key="s.name" class="border-b border-gray-50 last:border-0">
            <td class="py-3 text-sm text-gray-700">{{ s.name }}</td>
            <td class="py-3 text-sm text-gray-600">{{ s.orders }}</td>
            <td class="py-3 text-sm text-gray-700">₱{{ s.revenue }}</td>
            <td class="py-3 text-sm text-yellow-500 font-semibold">★ {{ s.rating }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup>
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'

const stats = [
  { label: 'Total Revenue (Month)', value: '₱182,430', bg: '#e6f4f1', icon: '<svg width="18" height="18" fill="none" stroke="#1D9E75" stroke-width="1.8" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 1 0 0 7h5a3.5 3.5 0 1 1 0 7H6"/></svg>' },
  { label: 'Orders This Month',    value: '4,821',    bg: '#e8f0fb', icon: '<svg width="18" height="18" fill="none" stroke="#185FA5" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>' },
  { label: 'Active Sellers',       value: '38',       bg: '#fef3e6', icon: '<svg width="18" height="18" fill="none" stroke="#BA7517" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>' },
  { label: 'Avg. Rating',          value: '4.7 ★',   bg: '#fdeaea', icon: '<svg width="18" height="18" fill="none" stroke="#A32D2D" stroke-width="1.8" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>' },
]

const sellerRevenue = [
  { name: "Aling Nena's", orders: 142, revenue: '58,400', rating: 4.8 },
  { name: 'Fresh Harvest', orders: 98,  revenue: '41,200', rating: 4.6 },
  { name: 'Green Market',  orders: 87,  revenue: '36,700', rating: 4.7 },
  { name: 'Bagsak Goods',  orders: 63,  revenue: '24,130', rating: 4.3 },
]
</script>