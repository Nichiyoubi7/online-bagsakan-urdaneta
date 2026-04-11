<template>
  <div>

    <!-- Tab Headers -->
    <div class="flex items-center border-b border-gray-200 mb-6">
      <button
        v-for="tab in tabs"
        :key="tab.key"
        @click="activeTab = tab.key"
        :class="[
          'px-6 py-3 text-sm font-medium transition-colors border-b-2 -mb-px',
          activeTab === tab.key
            ? 'border-green-500 text-green-600'
            : 'border-transparent text-gray-500 hover:text-gray-700'
        ]"
      >
        {{ tab.label }}
      </button>
    </div>

    <!-- Description Tab -->
    <div v-if="activeTab === 'description'" class="text-sm text-gray-600 leading-relaxed">
      <p class="mb-4">{{ product.description }}</p>
      <ul class="flex flex-col gap-2">
        <li v-for="point in product.highlights" :key="point" class="flex items-start gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 mt-0.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
          </svg>
          <span>{{ point }}</span>
        </li>
      </ul>
    </div>

    <!-- Additional Information Tab -->
    <div v-else-if="activeTab === 'additional'" class="text-sm">
      <table class="w-full">
        <tbody>
          <tr v-for="info in product.additionalInfo" :key="info.label" class="border-b border-gray-100">
            <td class="py-3 pr-6 font-medium text-gray-700 w-32">{{ info.label }}</td>
            <td class="py-3 text-gray-600">{{ info.value }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Customer Feedback Tab -->
    <div v-else-if="activeTab === 'feedback'">

      <!-- Average Rating Summary -->
      <div v-if="totalReviews > 0" class="flex items-center gap-4 mb-6 p-4 bg-green-50 rounded-2xl">
        <div class="text-center">
          <p class="text-4xl font-black text-green-600">{{ avgRating }}</p>
          <div class="flex items-center gap-0.5 justify-center mt-1">
            <svg
              v-for="star in 5"
              :key="star"
              class="w-4 h-4"
              :class="star <= Math.round(avgRating) ? 'text-yellow-400' : 'text-gray-200'"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </div>
          <p class="text-xs text-gray-400 mt-1">{{ totalReviews }} review{{ totalReviews !== 1 ? 's' : '' }}</p>
        </div>
      </div>

      <!-- Loading Skeleton -->
      <div v-if="loadingReviews" class="flex flex-col gap-4">
        <div v-for="n in 3" :key="n" class="h-20 bg-gray-100 rounded-xl animate-pulse" />
      </div>

      <!-- Review List -->
      <div v-else-if="reviews.length > 0" class="flex flex-col gap-4">
        <div
          v-for="review in reviews"
          :key="review.id"
          class="flex gap-3 pb-4 border-b border-gray-100"
        >
          <!-- Avatar -->
          <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
            {{ review.customer?.name?.charAt(0) ?? '?' }}
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <p class="text-sm font-semibold text-gray-800">{{ review.customer?.name ?? 'Customer' }}</p>
              <p class="text-xs text-gray-400">
                {{ new Date(review.created_at).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' }) }}
              </p>
            </div>
            <!-- Stars -->
            <div class="flex items-center gap-0.5 mb-1">
              <svg
                v-for="star in 5"
                :key="star"
                class="w-3.5 h-3.5"
                :class="star <= review.rating ? 'text-yellow-400' : 'text-gray-200'"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
            <p class="text-sm text-gray-600">{{ review.comment }}</p>
          </div>
        </div>
      </div>

      <!-- No Reviews Yet -->
      <div v-else class="text-center py-10 text-gray-400">
        <p class="text-3xl mb-2">⭐</p>
        <p class="text-sm">No reviews yet. Be the first to review!</p>
      </div>

      <!-- Submit Review Form (only for customers who bought and received this product) -->
      <div v-if="canReview" class="mt-6 p-4 bg-white border border-gray-200 rounded-2xl">
        <h4 class="text-sm font-bold text-gray-800 mb-3">Leave a Review</h4>

        <!-- Star Picker -->
        <div class="flex items-center gap-1 mb-3">
          <button
            v-for="star in 5"
            :key="star"
            @click="newRating = star"
            :class="star <= newRating ? 'text-yellow-400' : 'text-gray-300'"
            class="text-2xl transition-colors hover:text-yellow-400"
          >★</button>
        </div>

        <textarea
          v-model="newComment"
          rows="3"
          placeholder="Share your experience with this product..."
          class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-green-400"
        />

        <button
          @click="submitReview"
          :disabled="submitting || newRating === 0"
          class="mt-3 px-5 py-2 bg-green-500 hover:bg-green-600 disabled:opacity-50 text-white text-sm font-semibold rounded-xl transition-colors"
        >
          {{ submitting ? 'Submitting...' : 'Submit Review' }}
        </button>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'

const props = defineProps<{
  product: {
    id?: number
    description: string
    highlights: string[]
    additionalInfo: { label: string; value: string }[]
    reviews: any[]
  }
}>()

const activeTab = ref('description')
const tabs = [
  { key: 'description', label: 'Descriptions' },
  { key: 'additional',  label: 'Additional Information' },
  { key: 'feedback',    label: 'Customer Feedback' },
]

const { get, post } = useApi()
const authStore = useAuthStore()

const reviews        = ref<any[]>([])
const avgRating      = ref(0)
const totalReviews   = ref(0)
const loadingReviews = ref(false)

const canReview     = ref(false)
const reviewOrderId = ref<number | null>(null)
const newRating     = ref(0)
const newComment    = ref('')
const submitting    = ref(false)

// Load reviews from the real API
const loadReviews = async () => {
  if (!props.product.id) return
  loadingReviews.value = true
  try {
    const res: any = await get('/reviews', { product_id: props.product.id })
    reviews.value    = res.data ?? []
    avgRating.value  = res.avg_rating ?? 0
    totalReviews.value = res.total ?? 0
  } catch (e) {
    console.error('Failed to load reviews', e)
  } finally {
    loadingReviews.value = false
  }
}

// Check if the logged in customer bought this product and it was delivered
const checkCanReview = async () => {
  if (!authStore.isLoggedIn || !props.product.id) return
  try {
    const res: any = await get('/orders', { per_page: 100 })
    const delivered = (res.data ?? []).filter((o: any) => o.status === 'delivered')
    for (const order of delivered) {
      const hasItem = order.items?.some((i: any) => i.product_id === props.product.id)
      const alreadyReviewed = reviews.value.some((r: any) => r.order_id === order.id)
      if (hasItem && !alreadyReviewed) {
        canReview.value = true
        reviewOrderId.value = order.id
        break
      }
    }
  } catch (e) {
    // Not logged in or no orders — that's fine, just hide the form
  }
}

// Submit the review
const submitReview = async () => {
  if (!newRating.value || !reviewOrderId.value) return
  submitting.value = true
  try {
    await post('/reviews', {
      product_id: props.product.id,
      order_id:   reviewOrderId.value,
      rating:     newRating.value,
      comment:    newComment.value,
    })
    newRating.value  = 0
    newComment.value = ''
    canReview.value  = false
    await loadReviews()
  } catch (e) {
    console.error('Failed to submit review', e)
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  await loadReviews()
  await checkCanReview()
})

// Reload when switching to feedback tab
watch(activeTab, (tab) => {
  if (tab === 'feedback') loadReviews()
})
</script>