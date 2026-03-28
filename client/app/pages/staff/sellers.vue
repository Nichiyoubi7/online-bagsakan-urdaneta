<template>
  <StaffLayout>

    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">Sellers & Drivers</h1>
        <p class="text-sm text-gray-400 mt-0.5">Approve accounts and flag issues</p>
      </div>
      <!-- Toggle -->
      <div class="flex gap-1 bg-white border border-gray-200 rounded-xl p-1">
        <button @click="view = 'sellers'" :class="view === 'sellers' ? 'bg-[#0f2d1f] text-white' : 'text-gray-400'" class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all">Sellers</button>
        <button @click="view = 'drivers'" :class="view === 'drivers' ? 'bg-[#0f2d1f] text-white' : 'text-gray-400'" class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all">Drivers</button>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-xl p-5">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Name</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">{{ view === 'sellers' ? 'Shop' : 'Vehicle' }}</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Status</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Joined</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="s in (view === 'sellers' ? sellers : drivers)" :key="s.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-green-50 text-green-700 rounded-full flex items-center justify-center text-xs font-semibold">{{ s.name[0] }}</div>
                <span class="text-sm text-gray-700">{{ s.name }}</span>
              </div>
            </td>
            <td class="py-3 text-sm text-gray-600">{{ s.detail }}</td>
            <td class="py-3">
              <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize"
                :class="s.status === 'active' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700'">{{ s.status }}</span>
            </td>
            <td class="py-3 text-sm text-gray-400">{{ s.joined }}</td>
            <td class="py-3">
              <button v-if="s.status === 'pending'" class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-green-50 text-green-700">Approve</button>
              <button v-else class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700">Flag</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup>
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'
import { ref } from 'vue'

const view = ref('sellers')

const sellers = ref([
  { id: 1, name: 'Nena Alcantara', detail: "Aling Nena's Store", status: 'active',  joined: 'Jan 2025' },
  { id: 2, name: 'Ben Soriano',    detail: 'Fresh Harvest',       status: 'pending', joined: 'Mar 2025' },
  { id: 3, name: 'Lita Cruz',      detail: 'Green Market',        status: 'active',  joined: 'Feb 2025' },
  { id: 4, name: 'Ramon Dela O',   detail: 'Bagsak Goods',        status: 'pending', joined: 'Mar 2025' },
])

const drivers = ref([
  { id: 1, name: 'Rodel Mendoza', detail: 'Motorcycle', status: 'active',  joined: 'Jan 2025' },
  { id: 2, name: 'Danny Reyes',   detail: 'Bicycle',    status: 'active',  joined: 'Feb 2025' },
  { id: 3, name: 'Jose Pascual',  detail: 'Motorcycle', status: 'pending', joined: 'Mar 2025' },
])
</script>