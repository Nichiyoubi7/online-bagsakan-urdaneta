<template>
  <div class="flex flex-col gap-4">

    <!-- Stock Badge -->
    <div>
      <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
        In Stock
      </span>
    </div>

    <!-- Name -->
    <h1 class="text-3xl font-black text-gray-900">{{ product.name }}</h1>

    <!-- Rating + Reviews + SKU -->
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-1">
        <svg
          v-for="star in 5"
          :key="star"
          xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4"
          :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-200'"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        <span class="text-sm text-gray-500 ml-1">({{ product.reviews.length }} reviews)</span>
      </div>
      <span class="text-gray-300">|</span>
      <span class="text-sm text-gray-400">SKU: {{ product.sku }}</span>
    </div>

    <!-- Price -->
    <div class="flex items-center gap-3">
      <span v-if="product.originalPrice" class="text-gray-400 line-through text-lg">
        ₱{{ product.originalPrice.toFixed(2) }}
      </span>
      <span class="text-green-600 font-black text-3xl">
        ₱{{ product.price.toFixed(2) }}
      </span>
      <span v-if="product.discount" class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">
        {{ product.discount }}% OFF
      </span>
    </div>

    <!-- Brand -->
    <div class="flex items-center gap-2 text-sm text-gray-500">
      <span class="font-medium">Brand:</span>
      <span class="text-gray-700 font-semibold">{{ product.brand }}</span>
    </div>

    <!-- Description -->
    <p class="text-sm text-gray-500 leading-relaxed border-t border-gray-100 pt-4">
      {{ product.description }}
    </p>

    <!-- Quantity + Add to Cart -->
    <div class="flex items-center gap-3 pt-2">
      <div class="flex items-center border border-gray-200 rounded-full overflow-hidden">
        <button
          @click="quantity > 1 ? quantity-- : null"
          class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold"
        >-</button>
        <span class="px-4 py-2.5 text-sm font-semibold text-gray-800 min-w-[40px] text-center">{{ quantity }}</span>
        <button
          @click="quantity++"
          class="px-4 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold"
        >+</button>
      </div>

      <button
        @click="handleAddToCart"
        class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 px-6 rounded-full flex items-center justify-center gap-2 transition-colors shadow-md"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
        </svg>
        {{ added ? 'Added! ✓' : 'Add to Cart' }}
      </button>

      <button
        @click="wishlisted = !wishlisted"
        :class="['w-11 h-11 rounded-full border flex items-center justify-center transition-all', wishlisted ? 'bg-red-50 border-red-300 text-red-500' : 'border-gray-200 text-gray-400 hover:border-red-300 hover:text-red-400']"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :fill="wishlisted ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
      </button>
    </div>

    <!-- Added to cart confirmation -->
    <Transition name="fade">
      <div v-if="added" class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-2 rounded-xl flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Added {{ quantity }} item(s) to cart!
        <NuxtLink to="/customer/cart" class="ml-auto text-green-600 font-semibold underline">View Cart</NuxtLink>
      </div>
    </Transition>

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
    image?: string
    images?: string[]
  }
}>()

const cartStore = useCartStore()
const authStore = useAuthStore()

const quantity = ref(1)
const wishlisted = ref(false)
const added = ref(false)

const handleAddToCart = () => {
  if (!authStore.isLoggedIn) return

  for (let i = 0; i < quantity.value; i++) {
    cartStore.addItem({
      productId: props.product.id ?? 0,
      name: props.product.name,
      price: props.product.price,
      image: props.product.image || props.product.images?.[0] || '',
      category: props.product.category,
      sellerId: 3,
      sellerName: 'Bagsakan Seller',
    })
  }

  added.value = true
  setTimeout(() => { added.value = false }, 3000)
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>