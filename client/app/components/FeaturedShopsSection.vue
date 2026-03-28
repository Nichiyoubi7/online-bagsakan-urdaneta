<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Header -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
          <span class="text-xl">🏪</span>
          <h2 class="text-2xl font-black text-gray-900">Featured Shops</h2>
        </div>
        <NuxtLink
          to="/customer/shops"
          class="text-green-500 hover:text-green-600 text-sm font-medium flex items-center gap-1 transition-colors"
        >
          See All
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </NuxtLink>
      </div>

      <!-- Shops Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <NuxtLink
          v-for="shop in shops"
          :key="shop.id"
          :to="`/customer/shops/${shop.id}`"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden group"
        >

          <!-- Shop Banner -->
          <div
            class="relative h-36 flex items-center justify-center"
            :style="{ backgroundColor: shop.color }"
          >
            <!-- Open Badge -->
            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
              Open
            </span>

            <!-- Shop Icon/Image -->
            <span class="text-6xl">{{ shop.emoji }}</span>
          </div>

          <!-- Shop Info -->
          <div class="p-4">

            <!-- Name + Rating -->
            <div class="flex items-center justify-between mb-1">
              <h3 class="text-sm font-bold text-gray-800 group-hover:text-green-600 transition-colors">
                {{ shop.name }}
              </h3>
              <div class="flex items-center gap-1 text-sm">
                <span class="font-semibold text-gray-800">{{ shop.rating }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </div>
            </div>

            <!-- Description -->
            <p class="text-xs text-gray-400 mb-3">{{ shop.description }}</p>

            <!-- Delivery Info -->
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <!-- Time -->
              <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ shop.deliveryTime }}</span>
              </div>
              <!-- Delivery Fee -->
              <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                <span>₱{{ shop.deliveryFee }} delivery</span>
              </div>
            </div>

          </div>
        </NuxtLink>
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
// FeaturedShopsSection.vue
// Shows featured sellers on the landing page
// Each card links to that seller's shop page
// Replace dummy data with API data later

const shops = [
  {
    id: 1,
    name: "Mang Bert's Wet Market",
    description: 'Fresh daily catch & cuts',
    rating: 4.8,
    deliveryTime: '25 - 40 min',
    deliveryFee: 50,
    emoji: '🥩',
    color: '#f0fdf4',
  },
  {
    id: 2,
    name: "Aling Nena's Fish Hub",
    description: 'Sariwang isda mula sa dagat',
    rating: 4.9,
    deliveryTime: '30 - 45 min',
    deliveryFee: 40,
    emoji: '🐟',
    color: '#eff6ff',
  },
  {
    id: 3,
    name: "Ate Lita's Poultry Corner",
    description: 'Manok at itlog, sarwa araw-araw',
    rating: 4.7,
    deliveryTime: '20 - 35 min',
    deliveryFee: 45,
    emoji: '🐔',
    color: '#fefce8',
  },
  {
    id: 4,
    name: "Kuya Jun's Vegetable Stand",
    description: 'Sariwang gulay galing bukid',
    rating: 4.6,
    deliveryTime: '15 - 30 min',
    deliveryFee: 35,
    emoji: '🥬',
    color: '#f0fdf4',
  },
  {
    id: 5,
    name: "Lola Caring's Kakanin",
    description: 'Homemade rice cakes daily',
    rating: 4.9,
    deliveryTime: '20 - 40 min',
    deliveryFee: 30,
    emoji: '🍡',
    color: '#fdf4ff',
  },
  {
    id: 6,
    name: "Tatay Ben's Fruit Stand",
    description: 'Seasonal fruits at best price',
    rating: 4.5,
    deliveryTime: '20 - 35 min',
    deliveryFee: 40,
    emoji: '🍎',
    color: '#fff7ed',
  },
]
</script>