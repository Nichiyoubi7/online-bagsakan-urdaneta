<template>
  <aside class="flex flex-col gap-4">

    <!-- Categories -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <h3 class="text-sm font-black text-gray-800 mb-3">All Categories</h3>
      <ul class="flex flex-col gap-1">
        <li v-for="cat in categories" :key="cat.name">
          <button
            @click="$emit('selectCategory', cat.name === 'All Categories' ? '' : cat.name)"
            :class="[
              'w-full flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-colors',
              (cat.name === 'All Categories' && !selectedCategory) || selectedCategory === cat.name
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
        min="0"
        max="500"
        v-model="priceMax"
        @input="$emit('priceChange', Number(priceMax))"
        class="w-full accent-green-500"
      />
      <div class="flex items-center justify-between mt-1">
        <span class="text-xs text-gray-400">₱0</span>
        <span class="text-xs font-semibold text-gray-600">₱{{ priceMax }}</span>
      </div>
    </div>

    <!-- Rating -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <h3 class="text-sm font-black text-gray-800 mb-3">Rating</h3>
      <ul class="flex flex-col gap-1.5">
        <li v-for="rating in ratings" :key="rating.value">
          <button
            @click="$emit('selectRating', rating.value)"
            :class="[
              'w-full flex items-center gap-2 px-2 py-1.5 rounded-xl text-sm transition-colors',
              selectedRating === rating.value ? 'bg-green-50 text-green-600' : 'hover:bg-gray-50 text-gray-600'
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
            <span class="text-xs">{{ rating.label }}</span>
          </button>
        </li>
      </ul>
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
            'text-xs px-3 py-1.5 rounded-full border transition-colors',
            selectedTag === tag
              ? 'bg-green-500 text-white border-green-500'
              : 'border-gray-200 text-gray-600 hover:border-green-400 hover:text-green-600'
          ]"
        >
          {{ tag }}
        </button>
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
  { name: 'All Categories', count: 42 },
  { name: 'Vegetables',     count: 21 },
  { name: 'Fruits',         count: 15 },
  { name: 'Meat & Fish',    count: 6  },
]

const ratings = [
  { value: 5, label: '5 stars' },
  { value: 4, label: '4 stars & up' },
  { value: 3, label: '3 stars & up' },
  { value: 2, label: '2 stars & up' },
  { value: 1, label: '1 star & up' },
]

const popularTags = [
  'Organic', 'Vegetarian', 'Fresh', 'Local', 'Spicy', 'Kid Friendly',
]
</script>