<template>
  <SellerLayout title="Reviews" subtitle="See what customers say about your products">

    <!-- Loading -->
    <div v-if="loading" class="flex flex-col gap-3">
      <div v-for="n in 4" :key="n" class="h-20 bg-gray-100 rounded-xl animate-pulse" />
    </div>

    <div v-else>

      <!-- Summary Stats -->
      <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
          <p class="text-3xl font-black text-green-600">{{ avgRating }}</p>
          <div class="flex justify-center gap-0.5 my-1">
            <span v-for="star in 5" :key="star" :class="star <= Math.round(avgRating) ? 'text-yellow-400' : 'text-gray-200'" class="text-sm">★</span>
          </div>
          <p class="text-xs text-gray-400">Average Rating</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
          <p class="text-3xl font-black text-gray-800">{{ reviews.length }}</p>
          <p class="text-xs text-gray-400 mt-2">Total Reviews</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
          <p class="text-3xl font-black text-yellow-500">{{ fiveStarCount }}</p>
          <p class="text-xs text-gray-400 mt-2">5 Star Reviews</p>
        </div>
      </div>

      <!-- Reviews List -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Customer Reviews</h3>
        </div>

        <!-- Empty -->
        <div v-if="reviews.length === 0" class="text-center py-16 text-gray-400">
          <p class="text-4xl mb-3">⭐</p>
          <p class="text-sm">No reviews yet for your products.</p>
        </div>

        <!-- Review Cards -->
        <div v-else class="divide-y divide-gray-50">
          <div v-for="r in reviews" :key="r.id" class="p-5 flex gap-4">

            <!-- Avatar -->
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
              {{ r.customer?.name?.charAt(0) ?? '?' }}
            </div>

            <div class="flex-1">
              <div class="flex items-start justify-between mb-1">
                <div>
                  <p class="text-sm font-semibold text-gray-800">{{ r.customer?.name ?? 'Customer' }}</p>
                  <p class="text-xs text-gray-400">{{ r.product?.name ?? '—' }}</p>
                </div>
                <p class="text-xs text-gray-400">
                  {{ new Date(r.created_at).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                </p>
              </div>

              <!-- Stars -->
              <div class="flex gap-0.5 mb-2">
                <span v-for="star in 5" :key="star" :class="star <= r.rating ? 'text-yellow-400' : 'text-gray-200'" class="text-sm">★</span>
              </div>

              <p class="text-sm text-gray-600">{{ r.comment || 'No comment.' }}</p>
            </div>

          </div>
        </div>
      </div>

    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import SellerLayout from '../../components/seller/layout/SellerLayout.vue'

const { get } = useApi()
const authStore = useAuthStore()

const reviews = ref<any[]>([])
const loading = ref(true)

const avgRating = computed(() => {
  if (reviews.value.length === 0) return 0
  const sum = reviews.value.reduce((s, r) => s + r.rating, 0)
  return (sum / reviews.value.length).toFixed(1)
})

const fiveStarCount = computed(() =>
  reviews.value.filter(r => r.rating === 5).length
)

onMounted(async () => {
  loading.value = true
  try {
    // Load reviews filtered by this seller's ID
    const res: any = await get('/reviews', {
      seller_id: authStore.user?.id,
      per_page: 100,
    })
    reviews.value = res.data ?? []
  } catch (e) {
    console.error('Failed to load reviews', e)
  } finally {
    loading.value = false
  }
})
</script>