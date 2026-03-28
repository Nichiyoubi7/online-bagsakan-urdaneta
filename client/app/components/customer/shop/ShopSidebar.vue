<template>
  <aside class="w-64 shrink-0 flex flex-col gap-6">

    <!-- All Categories -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <h3 class="text-sm font-black text-gray-800 mb-3">All Categories</h3>
      <ul class="flex flex-col gap-1">
        <li v-for="cat in categories" :key="cat.name">
          <button
            @click="$emit('selectCategory', cat.name)"
            :class="[
              'w-full flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-colors',
              selectedCategory === cat.name
                ? 'bg-green-50 text-green-600 font-semibold'
                : 'text-gray-600 hover:bg-gray-50'
            ]"
          >
            <span>{{ cat.name }}</span>
            <span class="text-xs text-gray-400">({{ cat.count }})</span>
          </button>
        </li>
      </ul>
    </div>

    <!-- Price Range -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <h3 class="text-sm font-black text-gray-800 mb-3">Price</h3>
      <input
        type="range"
        v-model="priceMax"
        min="0"
        max="500"
        class="w-full accent-green-500"
        @input="$emit('priceChange', priceMax)"
      />
      <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
        <span>₱0</span>
        <span>₱{{ priceMax }}</span>
      </div>
    </div>

    <!-- Rating Filter -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <h3 class="text-sm font-black text-gray-800 mb-3">Rating</h3>
      <div class="flex flex-col gap-2">
        <button
          v-for="rating in ratings"
          :key="rating.value"
          @click="$emit('selectRating', rating.value)"
          :class="[
            'flex items-center gap-2 px-2 py-1.5 rounded-xl text-sm transition-colors',
            selectedRating === rating.value
              ? 'bg-green-50'
              : 'hover:bg-gray-50'
          ]"
        >
          <div class="flex items-center gap-0.5">
            <svg
              v-for="star in 5"
              :key="star"
              xmlns="http://www.w3.org/2000/svg"
              class="w-3.5 h-3.5"
              :class="star <= rating.value ? 'text-yellow-400' : 'text-gray-200'"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </div>
          <span class="text-xs text-gray-500">{{ rating.label }}</span>
        </button>
      </div>
    </div>

    <!-- Popular Tags -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <h3 class="text-sm font-black text-gray-800 mb-3">Popular Tags</h3>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="tag in popularTags"
          :key="tag"
          @click="$emit('selectTag', tag)"
          :class="[
            'px-3 py-1 rounded-full text-xs font-medium border transition-colors',
            selectedTag === tag
              ? 'bg-green-500 text-white border-green-500'
              : 'border-gray-200 text-gray-600 hover:border-green-400 hover:text-green-600'
          ]"
        >
          {{ tag }}
        </button>
      </div>
    </div>

    <!-- Discount Banner -->
    <div class="bg-green-500 rounded-2xl p-4 text-white">
      <p class="text-xs font-semibold uppercase tracking-widest mb-1 text-green-100">
        Special Offer
      </p>
      <p class="text-3xl font-black mb-1">75%</p>
      <p class="text-sm font-semibold mb-3">Discount on your first order</p>
      <NuxtLink
        to="/customer"
        class="inline-block bg-white text-green-600 text-xs font-bold px-4 py-2 rounded-full hover:bg-green-50 transition-colors"
      >
        Shop Now
      </NuxtLink>
    </div>

    <!-- Sale Products -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <h3 class="text-sm font-black text-gray-800 mb-3">Sale Products</h3>
      <div class="flex flex-col gap-3">
        <div
          v-for="product in saleProducts"
          :key="product.id"
          class="flex items-center gap-3 cursor-pointer group"
        >
          <div class="w-14 h-14 bg-gray-50 rounded-xl overflow-hidden shrink-0">
            <img
              :src="product.image"
              :alt="product.name"
              class="w-full h-full object-contain group-hover:scale-105 transition-transform"
            />
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-800 group-hover:text-green-600 transition-colors">
              {{ product.name }}
            </p>
            <div class="flex items-center gap-0.5 my-0.5">
              <svg v-for="star in 5" :key="star" xmlns="http://www.w3.org/2000/svg"
                class="w-3 h-3" :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-200'"
                viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
            <p class="text-xs text-green-600 font-bold">₱{{ product.price.toFixed(2) }}</p>
          </div>
        </div>
      </div>
    </div>

  </aside>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineProps<{
  selectedCategory: string
  selectedRating: number
  selectedTag: string
}>()

defineEmits<{
  (e: 'selectCategory', category: string): void
  (e: 'priceChange', value: number): void
  (e: 'selectRating', rating: number): void
  (e: 'selectTag', tag: string): void
}>()

const priceMax = ref(500)

const categories = [
  { name: 'All Categories',  count: 56 },
  { name: 'Fresh Fruit',     count: 12 },
  { name: 'Vegetables',      count: 15 },
  { name: 'Cooking',         count: 8  },
  { name: 'Snacks',          count: 6  },
  { name: 'Beverages',       count: 10 },
  { name: 'Beauty & Health', count: 5  },
]

const ratings = [
  { value: 5, label: '5 stars' },
  { value: 4, label: '4 stars & up' },
  { value: 3, label: '3 stars & up' },
  { value: 2, label: '2 stars & up' },
  { value: 1, label: '1 star & up' },
]

const popularTags = [
  'Seeding', 'Organic', 'Vegetarian',
  'Kid Friendly', 'Fresh', 'Spicy',
  'Frozen', 'Wheat', 'Local',
]

const saleProducts = [
  { id: 1, name: 'Chinese Cabbage', price: 15.00, rating: 4, image: '/images/products/chinese-cabbage.png' },
  { id: 2, name: 'Green Capsicum',  price: 15.00, rating: 4, image: '/images/products/capsicum.png' },
  { id: 3, name: 'Green Chili',     price: 15.00, rating: 4, image: '/images/products/green-chili.png' },
]
</script>