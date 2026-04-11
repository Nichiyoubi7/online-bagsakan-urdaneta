<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">

      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
          <span class="text-xl">🏪</span>
          <h2 class="text-2xl font-black text-gray-900">Featured Shops</h2>
        </div>
        <NuxtLink to="/sellers" class="text-green-500 hover:text-green-600 text-sm font-medium flex items-center gap-1 transition-colors">
          See All
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </NuxtLink>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <NuxtLink
          v-for="shop in shops"
          :key="shop.id"
          :to="shop.link"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden group"
        >
          <div class="relative h-40 overflow-hidden">
            <img :src="shop.image" :alt="shop.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
            <div class="absolute inset-0 bg-black/30" />
            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">Open</span>
            <div class="absolute bottom-3 left-3">
              <p class="text-white font-black text-sm drop-shadow">{{ shop.name }}</p>
            </div>
          </div>
          <div class="p-4">
            <div class="flex items-center justify-between mb-1">
              <p class="text-xs text-gray-500 truncate">{{ shop.description }}</p>
              <div class="flex items-center gap-1 shrink-0 ml-2">
                <span class="text-yellow-400 text-xs">★</span>
                <span class="text-xs font-semibold text-gray-700">{{ shop.rating }}</span>
              </div>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-400 mt-2">
              <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ shop.deliveryTime }}</span>
              </div>
              <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
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
const shops = [
  {
    id: 1,
    name: "Zandradee Poultry",
    description: 'Dressed chicken & retailer — Stall #38',
    rating: 4.8,
    deliveryTime: '25-40 min',
    deliveryFee: 50,
    image: "/images/shops/Zandradee Poultry.jpg",
    link: '/customer?seller_id=16',
  },
  {
    id: 2,
    name: "Ali's Store",
    description: 'Fresh eggs & market goods daily',
    rating: 4.7,
    deliveryTime: '20-35 min',
    deliveryFee: 40,
    image: "/images/shops/Ali Store.jpg",
    link: '/customer?seller_id=17',
  },
  {
    id: 3,
    name: "Ate Janice's Gulayan",
    description: 'Fresh vegetables from the farm',
    rating: 4.9,
    deliveryTime: '15-30 min',
    deliveryFee: 35,
    image: "/images/shops/Ate Janice's Gulayan.jpeg",
    link: '/customer?seller_id=18',
  },
  {
    id: 4,
    name: "Kuya Bert's Juicy Meat",
    description: 'Premium cuts of pork & beef',
    rating: 4.8,
    deliveryTime: '25-40 min',
    deliveryFee: 50,
    image: "/images/shops/Kuya Bert's Juicy Meat.jpg",
    link: '/customer?seller_id=19',
  },
  {
    id: 5,
    name: "Lalaine & Noy Banana",
    description: 'Wholesale & retail banana dealer',
    rating: 4.6,
    deliveryTime: '20-35 min',
    deliveryFee: 30,
    image: "/images/shops/Lalaine and Noy Banana wholesale and retail.jpg",
    link: '/customer?seller_id=20',
  },
  {
    id: 6,
    name: "Nick & Raquel Fruits",
    description: 'Fresh fruits wholesale & retail',
    rating: 4.9,
    deliveryTime: '20-40 min',
    deliveryFee: 40,
    image: "/images/shops/Nick and Raquel Fruits.jpg",
    link: '/customer?seller_id=21',
  },
]
</script>