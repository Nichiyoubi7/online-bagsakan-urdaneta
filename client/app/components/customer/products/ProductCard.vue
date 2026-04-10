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
    <button class="absolute top-3 right-3 z-10 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-gray-400 hover:text-red-400 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
      </svg>
    </button>

    <!-- Product Image -->
    <NuxtLink :to="`/customer/products/${product.id}`">
      <div class="p-4 flex items-center justify-center h-40 md:h-44 bg-gray-50">
        <img
          :src="product.image || (product.images && product.images[0])"
          :alt="product.name"
          class="h-32 md:h-36 w-full object-contain group-hover:scale-105 transition-transform duration-300"
        />
      </div>
    </NuxtLink>

    <!-- Product Info -->
    <div class="p-3 md:p-4 pt-2">
      <NuxtLink :to="`/customer/products/${product.id}`">
        <h3 class="text-xs md:text-sm font-semibold text-gray-800 hover:text-green-600 transition-colors mb-1 truncate">
          {{ product.name }}
        </h3>
      </NuxtLink>

      <div class="flex items-center gap-2 mb-1">
        <p class="text-green-600 font-bold text-sm">₱{{ product.price.toFixed(2) }}</p>
        <p v-if="product.originalPrice" class="text-gray-400 text-xs line-through">₱{{ product.originalPrice.toFixed(2) }}</p>
      </div>

      <!-- Stars -->
      <div class="flex items-center gap-0.5 mb-3">
        <svg
          v-for="star in 5"
          :key="star"
          xmlns="http://www.w3.org/2000/svg"
          class="w-3 h-3 md:w-3.5 md:h-3.5"
          :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-200'"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
      </div>

      <!-- Add to Cart Button -->
      <button
        @click.prevent="handleAddToCart"
        :disabled="added"
        :class="[
          'w-full text-xs font-semibold py-2 rounded-xl transition-all flex items-center justify-center gap-1',
          added
            ? 'bg-green-100 text-green-600 cursor-default'
            : 'bg-green-500 hover:bg-green-600 text-white'
        ]"
      >
        <svg v-if="!added" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ added ? 'Added!' : 'Add to Cart' }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
  product: {
    id: number
    name: string
    price: number
    originalPrice?: number
    image?: string
    images?: string[]
    rating: number
    badge?: string
    category?: string
    sellerId?: number
    sellerName?: string
  }
}>()

const cartStore = useCartStore()
const authStore = useAuthStore()
const added = ref(false)

const handleAddToCart = () => {
  if (!authStore.isLoggedIn) {
    navigateTo('/?login=true')
    return
  }

  cartStore.addItem({
    productId: props.product.id,
    name: props.product.name,
    image: props.product.image || (props.product.images?.[0]) || '/images/products/placeholder.png',
    price: props.product.price,
    originalPrice: props.product.originalPrice,
    quantity: 1,
    category: props.product.category || '',
    sellerId: props.product.sellerId ?? 1,
    sellerName: props.product.sellerName || 'OBRA Store',
  })

  added.value = true
  setTimeout(() => { added.value = false }, 2000)
}
</script>