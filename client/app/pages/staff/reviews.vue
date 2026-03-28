<template>
  <StaffLayout>

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-xl font-semibold text-[#0f2d1f]">Reviews</h1>
      <p class="text-sm text-gray-400 mt-0.5">Moderate customer product reviews</p>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-xl p-5">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Customer</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Product</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Rating</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Review</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Date</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in reviews" :key="r.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3 text-sm text-gray-700">{{ r.customer }}</td>
            <td class="py-3 text-sm text-gray-600">{{ r.product }}</td>
            <td class="py-3 text-sm text-yellow-500 font-semibold">★ {{ r.rating }}</td>
            <td class="py-3 text-sm text-gray-400 max-w-[200px] truncate">{{ r.text }}</td>
            <td class="py-3 text-sm text-gray-400">{{ r.date }}</td>
            <td class="py-3">
              <button class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-red-50 text-red-600">Remove</button>
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

const reviews = ref([
  { id: 1, customer: 'Maria Santos',   product: 'Fresh Tomatoes',   rating: 5, text: 'Very fresh and affordable!',        date: 'Mar 27' },
  { id: 2, customer: 'Juan dela Cruz', product: 'Bangus',           rating: 2, text: 'Arrived late and slightly smelly.', date: 'Mar 26' },
  { id: 3, customer: 'Ana Reyes',      product: 'Organic Eggplant', rating: 4, text: 'Good quality, will order again.',   date: 'Mar 25' },
  { id: 4, customer: 'Pedro Lim',      product: 'Green Chili',      rating: 1, text: 'Wrong item was delivered to me.',   date: 'Mar 24' },
])
</script>