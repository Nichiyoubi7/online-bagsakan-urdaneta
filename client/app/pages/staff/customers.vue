<template>
  <StaffLayout>

    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">Customers</h1>
        <p class="text-sm text-gray-400 mt-0.5">View and manage customer accounts</p>
      </div>
      <input v-model="search" placeholder="Search customer..." class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-600 w-56 outline-none focus:border-green-500" />
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-xl p-5">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Name</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Email</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Orders</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Total Spent</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Joined</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in filteredCustomers" :key="c.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-green-50 text-green-700 rounded-full flex items-center justify-center text-xs font-semibold">{{ c.name[0] }}</div>
                <span class="text-sm text-gray-700">{{ c.name }}</span>
              </div>
            </td>
            <td class="py-3 text-sm text-gray-400">{{ c.email }}</td>
            <td class="py-3 text-sm text-gray-600">{{ c.orders }}</td>
            <td class="py-3 text-sm text-gray-600">₱{{ c.spent }}</td>
            <td class="py-3 text-sm text-gray-400">{{ c.joined }}</td>
            <td class="py-3">
              <button class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700">Suspend</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup>
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'
import { ref, computed } from 'vue'

const search = ref('')

const customers = ref([
  { id: 1, name: 'Maria Santos',   email: 'maria@email.com',  orders: 14, spent: '4,320', joined: 'Jan 2025' },
  { id: 2, name: 'Juan dela Cruz', email: 'juan@email.com',   orders: 7,  spent: '1,890', joined: 'Feb 2025' },
  { id: 3, name: 'Ana Reyes',      email: 'ana@email.com',    orders: 22, spent: '7,100', joined: 'Dec 2024' },
  { id: 4, name: 'Pedro Lim',      email: 'pedro@email.com',  orders: 3,  spent: '540',   joined: 'Mar 2025' },
  { id: 5, name: 'Rosa Garcia',    email: 'rosa@email.com',   orders: 18, spent: '5,430', joined: 'Nov 2024' },
])

const filteredCustomers = computed(() =>
  customers.value.filter(c => c.name.toLowerCase().includes(search.value.toLowerCase()))
)
</script>