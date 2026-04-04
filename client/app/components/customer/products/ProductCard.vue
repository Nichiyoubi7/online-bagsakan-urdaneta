<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-200 group relative overflow-hidden">
    <!-- Sale Badge -->
    <span
      v-if="product.badge"
      class="absolute top-3 left-3 z-10 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"
    >
      {{ product.badge }}
    </span>

    <!-- Wishlist Button -->
    <button
      @click="handleWishlist"
      class="absolute top-3 right-3 z-10 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-gray-400 hover:text-red-400 transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
      </svg>
    </button>

    <!-- Product Image -->
    <NuxtLink :to="`/customer/products/${product.id}`">
      <div class="p-4 flex items-center justify-center h-44 bg-gray-50">
        <img
          :src="product.image || (product.images && product.images[0])"
          :alt="product.name"
          class="h-36 w-full object-contain group-hover:scale-105 transition-transform duration-300"
        />
      </div>
    </NuxtLink>

    <!-- Product Info -->
    <div class="p-4 pt-2">
      <NuxtLink :to="`/customer/products/${product.id}`">
        <h3 class="text-sm font-semibold text-gray-800 hover:text-green-600 transition-colors mb-1 truncate">
          {{ product.name }}
        </h3>
      </NuxtLink>
      <p class="text-green-600 font-bold text-sm mb-2">
        ₱{{ product.price.toFixed(2) }}
      </p>
      <div class="flex items-center gap-0.5 mb-3">
        <svg
          v-for="star in 5"
          :key="star"
          xmlns="http://www.w3.org/2000/svg"
          class="w-3.5 h-3.5"
          :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-200'"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
      </div>

      <!-- Add to Cart Button -->
      <button
        @click="handleAddToCart"
        class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center ml-auto transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
      </button>
    </div>

    <AuthModal v-model="showAuthModal" />
  </div>
</template>

<script setup lang="ts">
import AuthModal from '~/components/auth/AuthModal.vue'
import { useAuthGate } from '~/composables/useAuthGate'
import { useToast } from '~/composables/useToast'

defineProps<{
  product: {
    id: number
    name: string
    price: number
    image?: string
    images?: string[]
    rating: number
    badge?: string
  }
}>()

const { requireAuth, showAuthModal } = useAuthGate()
const { success } = useToast()

const handleAddToCart = () => {
  requireAuth(() => {
    success('Item added to cart!')
  })
}

const handleWishlist = () => {
  requireAuth(() => {
    success('Added to wishlist!')
  })
}
</script>