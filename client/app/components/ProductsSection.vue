<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Header -->
      <h2 class="text-2xl font-black text-gray-900 text-center mb-6">
        Introducing Our Products
      </h2>

      <!-- Filter Tabs -->
      <div class="flex items-center justify-center gap-2 mb-8 flex-wrap">
        <button
          v-for="tab in tabs"
          :key="tab"
          @click="activeTab = tab"
          :class="[
            'px-5 py-1.5 rounded-full text-sm font-medium border transition-colors duration-200',
            activeTab === tab
              ? 'bg-white border-green-500 text-green-600'
              : 'bg-transparent border-gray-200 text-gray-500 hover:border-green-400 hover:text-green-500'
          ]"
        >
          {{ tab }}
        </button>

        <!-- View All link -->
        <NuxtLink
          to="/customer"
          class="px-5 py-1.5 rounded-full text-sm font-medium border border-gray-200 text-gray-500 hover:border-green-400 hover:text-green-500 transition-colors"
        >
          View All
        </NuxtLink>
      </div>

      <!-- Product Grid -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <ProductCard
          v-for="product in filteredProducts"
          :key="product.id"
          :product="product"
        />
      </div>

      <!-- Empty state — shows when no products match the filter -->
      <div
        v-if="filteredProducts.length === 0"
        class="text-center py-16 text-gray-400 text-sm"
      >
        No products found in this category.
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import ProductCard from './customer/products/ProductCard.vue'

// Active filter tab
const activeTab = ref('All')

// Filter tab options
const tabs = ['All', 'Vegetable', 'Fruit', 'Meat & Fish']

// Products list
// Each product has a "category" field that matches the tab names
// Replace with API data later
const products = [
  {
    id: 1,
    name: 'Green Apple',
    price: 15.00,
    image: '/images/products/green-apple.png',
    rating: 4,
    badge: 'Sale 50%',
    category: 'Fruit',
  },
  {
    id: 2,
    name: 'Guijapur Mango',
    price: 15.00,
    image: '/images/products/mango.png',
    rating: 4,
    category: 'Fruit',
  },
  {
    id: 3,
    name: 'Red Tomatoes',
    price: 15.00,
    image: '/images/products/tomatoes.png',
    rating: 4,
    category: 'Vegetable',
  },
  {
    id: 4,
    name: 'Fresh Cauliflower',
    price: 15.00,
    image: '/images/products/cauliflower.png',
    rating: 4,
    category: 'Vegetable',
  },
  {
    id: 5,
    name: 'Green Lettuce',
    price: 15.00,
    image: '/images/products/lettuce.png',
    rating: 4,
    category: 'Vegetable',
  },
  {
    id: 6,
    name: 'Eggplant',
    price: 15.00,
    image: '/images/products/eggplant.png',
    rating: 4,
    category: 'Vegetable',
  },
  {
    id: 7,
    name: 'Green Chili',
    price: 15.00,
    image: '/images/products/green-chili.png',
    rating: 4,
    category: 'Vegetable',
  },
  {
    id: 8,
    name: 'Bangus',
    price: 85.00,
    image: '/images/products/bangus.png',
    rating: 4,
    category: 'Meat & Fish',
  },
  {
    id: 9,
    name: 'Pork Liempo',
    price: 220.00,
    image: '/images/products/pork.png',
    rating: 4,
    category: 'Meat & Fish',
  },
  {
    id: 10,
    name: 'Tilapia',
    price: 95.00,
    image: '/images/products/tilapia.png',
    rating: 4,
    category: 'Meat & Fish',
  },
]

// Filter logic
// If tab is "All" → show everything
// Otherwise → only show products matching the selected category
const filteredProducts = computed(() => {
  if (activeTab.value === 'All') return products
  return products.filter(p => p.category === activeTab.value)
})
</script>