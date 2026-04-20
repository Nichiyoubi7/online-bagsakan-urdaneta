<template>
  <div class="flex flex-col gap-5">

    <!-- Rating -->
    <div class="flex items-center gap-2">
      <div class="flex items-center gap-0.5">
        <svg v-for="star in 5" :key="star" xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4" :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-200'"
          viewBox="0 0 20 20" fill="currentColor">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
      </div>
      <span class="text-sm text-gray-500">({{ product.reviews?.length || 0 }} reviews)</span>
      <span class="text-xs text-gray-300">|</span>
      <span class="text-xs text-gray-500">SKU: {{ product.sku }}</span>
    </div>

    <!-- Name -->
    <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">{{ product.name }}</h1>

    <!-- Price -->
    <div class="flex items-center gap-3">
      <span class="text-2xl md:text-3xl font-black text-green-600">₱{{ product.price.toFixed(2) }}</span>
      <span v-if="product.originalPrice" class="text-lg text-gray-400 line-through">₱{{ product.originalPrice.toFixed(2) }}</span>
      <span v-if="product.discount" class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">-{{ product.discount }}%</span>
    </div>

    <!-- Description -->
    <p class="text-sm text-gray-500 leading-relaxed">{{ product.description }}</p>

    <!-- Stock -->
    <div class="flex items-center gap-2 text-sm">
      <span class="text-gray-500">Availability:</span>
      <span :class="product.stock > 0 ? 'text-green-600 font-semibold' : 'text-red-500 font-semibold'">
        {{ product.stock > 0 ? `In Stock (${product.stock} available)` : 'Out of Stock' }}
      </span>
    </div>

<!-- Quantity + Buttons -->
<div class="flex flex-col gap-3">
  <div class="flex items-center gap-3">
    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
      <button @click="quantity > 1 ? quantity-- : null"
        class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg">−</button>
      <span class="px-4 py-2.5 text-sm font-semibold text-gray-800 min-w-[40px] text-center">{{ quantity }}</span>
      <button @click="quantity++"
        class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg">+</button>
    </div>

    <button
      @click="handleAddToCart"
      :disabled="product.stock === 0"
      class="flex-1 bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-semibold py-2.5 px-6 rounded-full flex items-center justify-center gap-2 transition-colors shadow-md"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
      </svg>
      Add to Cart
    </button>

    <button
      @click="wishlisted = !wishlisted"
      :class="['w-11 h-11 rounded-full border flex items-center justify-center transition-all shrink-0', wishlisted ? 'bg-red-50 border-red-300 text-red-500' : 'border-gray-200 text-gray-400 hover:border-red-300 hover:text-red-400']"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :fill="wishlisted ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
      </svg>
    </button>
  </div>

  <!-- Buy Now -->
  <button
    @click="handleBuyNow"
    :disabled="product.stock === 0"
    class="w-full bg-[#0f2d1f] hover:bg-[#1a4a30] disabled:bg-gray-300 text-white font-bold py-2.5 px-6 rounded-full flex items-center justify-center gap-2 transition-colors shadow-md"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
    </svg>
    Buy Now
  </button>
</div>

<!-- Login Prompt Modal -->
<Transition name="fade">
  <div v-if="showLoginPrompt" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="showLoginPrompt = false">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
      <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </div>
      <h3 class="text-lg font-black text-gray-900 mb-2">Sign in to continue</h3>
      <p class="text-sm text-gray-400 mb-6">
        You need an account to {{ pendingAction === 'buy' ? 'buy products' : 'add items to your cart' }}. Join OBRA today!
      </p>
      <div class="flex flex-col gap-3">
        <button @click="goToLogin" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-colors">
          Sign In
        </button>
        <button @click="goToRegister" class="w-full border-2 border-green-500 text-green-600 font-semibold py-3 rounded-xl hover:bg-green-50 transition-colors">
          Create Account
        </button>
        <button @click="showLoginPrompt = false" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
          Maybe later
        </button>
      </div>
    </div>
  </div>
</Transition>

    <!-- Category + Tags -->
    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 text-sm">
      <div class="flex items-center gap-2">
        <span class="text-gray-500 font-medium">Category:</span>
        <NuxtLink
          :to="`/customer?category=${encodeURIComponent(product.category)}`"
          class="text-green-600 hover:underline"
        >{{ product.category }}</NuxtLink>
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-gray-500 font-medium">Tags:</span>
        <span v-for="tag in product.tags" :key="tag"
          class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ tag }}</span>
      </div>
    </div>

    <!-- Share -->
    <div class="flex items-center gap-3 pt-2">
      <span class="text-sm text-gray-500 font-medium">Share:</span>
      <div class="flex items-center gap-2">
        <button class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-blue-700 transition-colors">f</button>
        <button class="w-8 h-8 bg-sky-500 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-sky-600 transition-colors">t</button>
        <button class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-red-600 transition-colors">p</button>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
  product: {
    id?: number
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
    stock?: number
    sellerId?: number
    sellerName?: string
  }
}>()

const emit = defineEmits<{
  (e: 'add-to-cart', quantity: number): void
}>()

const { requireAuth } = useAuthGate()
const quantity = ref(1)
const wishlisted = ref(false)

const handleAddToCart = () => {
  requireAuth(() => emit('add-to-cart', quantity.value))
}

const handleBuyNow = () => {
  requireAuth(() => {
    emit('add-to-cart', quantity.value)
    navigateTo('/customer/cart')
  })
}
</script>