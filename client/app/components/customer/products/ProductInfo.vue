<template>
  <div class="flex flex-col gap-4">

    <!-- Product Name -->
    <div>
      <h1 class="text-2xl font-black text-gray-900 mb-1">{{ product.name }}</h1>
      <div class="flex items-center gap-2">
        <div class="flex items-center gap-0.5">
          <svg
            v-for="star in 5"
            :key="star"
            class="w-4 h-4"
            :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-200'"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
        </div>
        <span class="text-sm text-gray-400">({{ product.reviews.length }} reviews)</span>
      </div>
    </div>

    <!-- Price -->
    <div class="flex items-center gap-3">
      <span class="text-3xl font-black text-green-600">₱{{ product.price.toFixed(2) }}</span>
      <span v-if="product.originalPrice" class="text-lg text-gray-400 line-through">
        ₱{{ product.originalPrice.toFixed(2) }}
      </span>
      <span v-if="product.discount" class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">
        -{{ product.discount }}%
      </span>
    </div>

    <!-- Description -->
    <p class="text-sm text-gray-500 leading-relaxed">{{ product.description }}</p>

    <!-- SKU / Brand -->
    <div class="flex flex-col gap-1 text-sm text-gray-500">
      <span><span class="font-semibold text-gray-700">SKU:</span> {{ product.sku }}</span>
      <span><span class="font-semibold text-gray-700">Brand:</span> {{ product.brand }}</span>
    </div>

    <!-- Quantity + Buttons -->
    <div class="flex items-center gap-3 mt-2">
      <div class="flex items-center border border-gray-200 rounded-full overflow-hidden">
        <button @click="quantity > 1 ? quantity-- : null" class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold">-</button>
        <span class="px-4 py-2.5 text-sm font-semibold text-gray-800 min-w-[40px] text-center">{{ quantity }}</span>
        <button @click="quantity++" class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold">+</button>
      </div>

      <!-- Add to Cart -->
      <button
        @click="handleAddToCart"
        class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 px-6 rounded-full flex items-center justify-center gap-2 transition-colors shadow-md"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
        </svg>
        Add to Cart
      </button>

      <!-- Buy Now -->
      <button
        @click="handleBuyNow"
        class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 px-6 rounded-full flex items-center justify-center gap-2 transition-colors shadow-md"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Buy Now
      </button>

      <!-- Wishlist -->
      <button
        @click="handleWishlist"
        :class="['w-11 h-11 rounded-full border flex items-center justify-center transition-all', wishlisted ? 'bg-red-50 border-red-300 text-red-500' : 'border-gray-200 text-gray-400 hover:border-red-300 hover:text-red-400']"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :fill="wishlisted ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
      </button>
    </div>

    <!-- Category + Tags -->
    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 text-sm">
      <div class="flex items-center gap-2">
        <span class="text-gray-500 font-medium">Category:</span>
        <NuxtLink :to="`/customer?category=${encodeURIComponent(product.category)}`" class="text-green-600 hover:underline">
          {{ product.category }}
        </NuxtLink>
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-gray-500 font-medium">Tags:</span>
        <span v-for="tag in product.tags" :key="tag" class="text-green-600 hover:underline cursor-pointer">
          {{ tag }}
        </span>
      </div>
    </div>

    <!-- Share -->
    <div class="flex items-center gap-3 pt-2">
      <span class="text-sm text-gray-500 font-medium">Share Item:</span>
      <div class="flex items-center gap-2">
        <button class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-blue-700 transition-colors">f</button>
        <button class="w-8 h-8 bg-sky-500 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-sky-600 transition-colors">t</button>
        <button class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-red-600 transition-colors">p</button>
        <button class="w-8 h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-pink-700 transition-colors">i</button>
      </div>
    </div>

    <AuthModal v-model="showAuthModal" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { navigateTo } from '#app'
import AuthModal from '~/components/auth/AuthModal.vue'
import { useAuthGate } from '~/composables/useAuthGate'
import { useToast } from '~/composables/useToast'

defineProps<{
  product: {
    name: string
    rating: number
    reviews: { id: number; name: string; rating: number; comment: string; date: string }[]
    sku: string
    price: number
    originalPrice?: number
    discount?: number
    brand: string
    description: string
    category: string
    tags: string[]
  }
}>()

const quantity = ref(1)
const wishlisted = ref(false)

const { requireAuth, showAuthModal } = useAuthGate()
const { success } = useToast()

const handleAddToCart = () => {
  requireAuth(() => {
    success(`Added ${quantity.value} item(s) to cart!`)
  })
}

const handleBuyNow = () => {
  requireAuth(() => {
    navigateTo('/customer/checkout')
  })
}

const handleWishlist = () => {
  requireAuth(() => {
    wishlisted.value = !wishlisted.value
    success(wishlisted.value ? 'Added to wishlist!' : 'Removed from wishlist!')
  })
}
</script>