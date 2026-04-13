<template>
  <StaffLayout>

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-xl font-semibold text-[#0f2d1f]">Reviews</h1>
      <p class="text-sm text-gray-400 mt-0.5">Moderate customer product reviews</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex flex-col gap-3">
      <div v-for="n in 4" :key="n" class="h-12 bg-gray-100 rounded-xl animate-pulse" />
    </div>

    <!-- Table -->
    <div v-else class="bg-white border border-gray-100 rounded-xl p-5">
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

          <!-- No reviews yet -->
          <tr v-if="reviews.length === 0">
            <td colspan="6" class="py-8 text-center text-sm text-gray-400">
              No reviews yet.
            </td>
          </tr>

          <!-- Real reviews from database -->
          <tr v-for="r in reviews" :key="r.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3 text-sm text-gray-700">{{ r.customer?.name ?? '—' }}</td>
            <td class="py-3 text-sm text-gray-600">{{ r.product?.name ?? '—' }}</td>
            <td class="py-3">
              <div class="flex items-center gap-0.5">
                <span
                  v-for="star in 5"
                  :key="star"
                  :class="star <= r.rating ? 'text-yellow-400' : 'text-gray-200'"
                  class="text-sm"
                >★</span>
              </div>
            </td>
            <td class="py-3 text-sm text-gray-400 max-w-[200px] truncate">{{ r.comment }}</td>
            <td class="py-3 text-sm text-gray-400">
              {{ new Date(r.created_at).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' }) }}
            </td>
            <td class="py-3">
              <button
                @click="removeReview(r.id)"
                class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
              >
                Remove
              </button>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup lang="ts">
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'
import { ref, onMounted } from 'vue'

const { get, destroy } = useApi()

const reviews = ref<any[]>([])
const loading = ref(true)

// Load real reviews from the API
const loadReviews = async () => {
  loading.value = true
  try {
    const res: any = await get('/reviews', { per_page: 100 })
    reviews.value = res.data ?? []
  } catch (e) {
    console.error('Failed to load reviews', e)
  } finally {
    loading.value = false
  }
}

// Remove a review
const removeReview = async (id: number) => {
  if (!confirm('Are you sure you want to remove this review?')) return
  try {
    await destroy(`/reviews/${id}`)
    reviews.value = reviews.value.filter(r => r.id !== id)
  } catch (e) {
    console.error('Failed to remove review', e)
  }
}

onMounted(loadReviews)
</script>