<template>
  <div class="bg-white border-b border-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-6 flex items-center gap-4">

      <!-- Logo -->
      <NuxtLink to="/" class="flex items-center shrink-0">
        <span class="text-2xl font-black text-gray-800 tracking-tight">OBRA</span>
      </NuxtLink>

      <!-- Search Bar -->
      <div class="flex-1 flex items-center border border-gray-200 rounded-md overflow-hidden max-w-2xl">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search products, sellers..."
          class="flex-1 px-4 py-2.5 text-sm text-gray-700 outline-none placeholder:text-gray-400"
          @keyup.enter="handleSearch"
        />
        <button
          class="bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 text-sm font-medium transition-colors"
          @click="handleSearch"
        >
          Search
        </button>
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
import { ref } from 'vue'
import { navigateTo } from '#app'
import AuthModal from '../auth/AuthModal.vue'

const searchQuery = ref('')
const wishlistCount = ref(0)
const cartCount = ref(2)
const cartTotal = ref(570.00)
const showAuthModal = ref(false)

const handleSearch = () => {
  if (!searchQuery.value.trim()) return
  navigateTo(`/customer?search=${searchQuery.value}`)
}
</script>