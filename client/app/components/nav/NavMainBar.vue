<template>
  <div class="bg-white border-b border-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-6 flex items-center gap-4">

      <!-- Logo -->
      <NuxtLink to="/" class="flex items-center shrink-0">
        <span class="text-2xl font-black text-gray-800 tracking-tight">OBRA</span>
      </NuxtLink>

      <!-- Search Bar -->
      <div class="flex-1 flex flex-col max-w-2xl relative">
        <div class="flex items-center border border-gray-200 rounded-md overflow-hidden">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search products, sellers..."
            class="flex-1 px-4 py-2.5 text-sm text-gray-700 outline-none placeholder:text-gray-400"
            @keyup.enter="handleSearch"
            @input="handleInput"
            @focus="showSuggestions = true"
            @blur="hideSuggestions"
          />
          <button
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 text-sm font-medium transition-colors"
            @click="handleSearch"
          >
            Search
          </button>
        </div>

        <!-- Suggestions Dropdown -->
        <div
          v-if="showSuggestions && filteredSuggestions.length > 0"
          class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 z-50 max-h-72 overflow-y-auto"
        >
          <NuxtLink
            v-for="product in filteredSuggestions"
            :key="product.id"
            :to="`/customer/products/${product.id}`"
            class="flex items-center gap-3 px-4 py-3 hover:bg-green-50 transition-colors cursor-pointer"
            @click="selectSuggestion(product.name)"
          >
            <img
              :src="product.image"
              :alt="product.name"
              class="w-10 h-10 object-contain rounded-lg bg-gray-50"
            />
            <div>
              <p class="text-sm font-medium text-gray-800">{{ product.name }}</p>
              <p class="text-xs text-gray-400">{{ product.category }} · ₱{{ product.price.toFixed(2) }}</p>
            </div>
          </NuxtLink>
        </div>
      </div>

      <!-- Right Icons -->
      <div class="flex items-center gap-3 ml-auto shrink-0">

        <!-- Wishlist -->
        <NuxtLink to="/customer/wishlist" class="relative text-gray-600 hover:text-green-500 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
          <span v-if="wishlistCount > 0" class="absolute -top-1.5 -right-1.5 bg-green-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
            {{ wishlistCount }}
          </span>
        </NuxtLink>

        <!-- Cart -->
        <NuxtLink to="/customer/cart" class="flex items-center gap-2 text-gray-700 hover:text-green-500 transition-colors">
          <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
            </svg>
            <span v-if="cartCount > 0" class="absolute -top-1.5 -right-1.5 bg-green-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
              {{ cartCount }}
            </span>
          </div>
          <div class="flex flex-col leading-tight">
            <span class="text-[10px] text-gray-400">Shopping Cart</span>
            <span class="text-sm font-semibold text-gray-800">₱{{ cartTotal.toFixed(2) }}</span>
          </div>
        </NuxtLink>

        <!-- Divider -->
        <div class="w-px h-8 bg-gray-200" />

        <!-- Log In Button -->
        <button
          @click="showAuthModal = true"
          class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-full hover:border-green-500 hover:text-green-600 transition-all duration-200"
        >
          Log In
        </button>

        <!-- Sign Up Button -->
        <button
          @click="showAuthModal = true"
          class="px-4 py-2 text-sm font-semibold text-white bg-green-500 hover:bg-green-600 rounded-full transition-all duration-200 shadow-sm"
        >
          Sign Up
        </button>

      </div>
    </div>
  </div>

  <!-- Auth Modal -->
  <AuthModal v-model="showAuthModal" />
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { navigateTo } from '#app'
import AuthModal from '../auth/AuthModal.vue'

const searchQuery = ref('')
const wishlistCount = ref(0)
const cartCount = ref(2)
const cartTotal = ref(570.00)
const showAuthModal = ref(false)
const showSuggestions = ref(false)

const allProducts = [
  { id: 1,  name: 'Tomato',       price: 20.00,  image: '/images/products/vegetables/Tomato.png',       category: 'Vegetables' },
  { id: 2,  name: 'Eggplant',     price: 15.00,  image: '/images/products/vegetables/eggplant.png',     category: 'Vegetables' },
  { id: 3,  name: 'Bitter Gourd', price: 18.00,  image: '/images/products/vegetables/bitter_gourd.png', category: 'Vegetables' },
  { id: 4,  name: 'Okra',         price: 12.00,  image: '/images/products/vegetables/okra.png',         category: 'Vegetables' },
  { id: 5,  name: 'Sitaw',        price: 15.00,  image: '/images/products/vegetables/sitaw.png',        category: 'Vegetables' },
  { id: 6,  name: 'Kangkong',     price: 10.00,  image: '/images/products/vegetables/kangkong.png',     category: 'Vegetables' },
  { id: 7,  name: 'Repolyo',      price: 25.00,  image: '/images/products/vegetables/repolyo.png',      category: 'Vegetables' },
  { id: 8,  name: 'Carrot',       price: 25.00,  image: '/images/products/vegetables/carrot.png',       category: 'Vegetables' },
  { id: 9,  name: 'Potato',       price: 30.00,  image: '/images/products/vegetables/potato.png',       category: 'Vegetables' },
  { id: 10, name: 'Sibuyas',      price: 60.00,  image: '/images/products/vegetables/sibuyas.png',      category: 'Vegetables' },
  { id: 11, name: 'Bawang',       price: 80.00,  image: '/images/products/vegetables/bawang.png',       category: 'Vegetables' },
  { id: 12, name: 'Mais',         price: 15.00,  image: '/images/products/vegetables/mais.png',         category: 'Vegetables' },
  { id: 13, name: 'Mango',        price: 50.00,  image: '/images/products/fruits/mango.png',            category: 'Fruits' },
  { id: 14, name: 'Saging',       price: 30.00,  image: '/images/products/fruits/saging.png',           category: 'Fruits' },
  { id: 15, name: 'Papaya',       price: 40.00,  image: '/images/products/fruits/papaya.png',           category: 'Fruits' },
  { id: 16, name: 'Pakwan',       price: 80.00,  image: '/images/products/fruits/pakwan.png',           category: 'Fruits' },
  { id: 17, name: 'Pineapple',    price: 45.00,  image: '/images/products/fruits/Pineapple.png',        category: 'Fruits' },
  { id: 18, name: 'Avocado',      price: 60.00,  image: '/images/products/fruits/Avocado.png',          category: 'Fruits' },
  { id: 19, name: 'Guava',        price: 25.00,  image: '/images/products/fruits/Guava.png',            category: 'Fruits' },
  { id: 20, name: 'Rambutan',     price: 35.00,  image: '/images/products/fruits/Rambutan.png',         category: 'Fruits' },
  { id: 21, name: 'Chicken',      price: 180.00, image: '/images/products/meat/Chicken.png',            category: 'Meat & Fish' },
  { id: 22, name: 'Pork Meat',    price: 220.00, image: '/images/products/meat/pork_meat.png',          category: 'Meat & Fish' },
  { id: 23, name: 'Egg',          price: 12.00,  image: '/images/products/meat/Egg.png',                category: 'Meat & Fish' },
  { id: 24, name: 'Rice',         price: 55.00,  image: '/images/products/meat/rice.png',               category: 'Meat & Fish' },
]

const filteredSuggestions = computed(() => {
  if (!searchQuery.value.trim()) return []
  return allProducts
    .filter(p => p.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
    .slice(0, 6)
})

const handleInput = () => {
  showSuggestions.value = true
}

const handleSearch = () => {
  if (!searchQuery.value.trim()) return
  showSuggestions.value = false
  navigateTo(`/customer?search=${searchQuery.value}`)
}

const selectSuggestion = (name: string) => {
  searchQuery.value = name
  showSuggestions.value = false
}

const hideSuggestions = () => {
  setTimeout(() => { showSuggestions.value = false }, 200)
}
</script>