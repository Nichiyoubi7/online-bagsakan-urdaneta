<template>
  <GuestLayout>

    <div class="bg-[#0f2d1f] py-16">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-green-400 font-semibold text-xs tracking-widest uppercase mb-2">Our Community</p>
        <h1 class="text-3xl md:text-4xl font-black text-white mb-4">Meet Our Sellers</h1>
        <p class="text-green-200 text-sm max-w-xl mx-auto">
          Local farmers and vendors from Urdaneta City bringing you the freshest products straight from their farms and stores.
        </p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">

      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5 w-72 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
          </svg>
          <input v-model="search" type="text" placeholder="Search sellers..." class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 flex-1" />
        </div>
        <p class="text-sm text-gray-400">{{ filteredShops.length }} sellers found</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <NuxtLink
          v-for="shop in filteredShops"
          :key="shop.id"
          :to="shop.link"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group"
        >
          <div class="relative h-44 overflow-hidden">
            <img :src="shop.image" :alt="shop.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
            <div class="absolute inset-0 bg-black/30" />
            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">Open</span>
            <div class="absolute bottom-3 left-3">
              <p class="text-white font-black text-base drop-shadow">{{ shop.name }}</p>
              <p class="text-green-200 text-xs">Urdaneta City</p>
            </div>
          </div>
          <div class="p-5">
            <p class="text-sm text-gray-500 mb-3">{{ shop.description }}</p>
            <div class="flex items-center justify-between text-xs text-gray-400 mb-4">
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
              <div class="flex items-center gap-1">
                <span class="text-yellow-400">★</span>
                <span class="font-semibold text-gray-700">{{ shop.rating }}</span>
              </div>
            </div>
            <div class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2.5 rounded-xl text-center transition-colors">
              Visit Shop →
            </div>
          </div>
        </NuxtLink>
      </div>

    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import GuestLayout from '~/components/layout/GuestLayout.vue'

const search = ref('')

const shops = [
  {
    id: 1,
    name: "Zandradee Poultry",
    description: 'Dressed chicken & retailer. Fresh poultry products daily at Stall #38.',
    rating: 4.8,
    deliveryTime: '25-40 min',
    deliveryFee: 50,
    image: "/images/shops/Zandradee Poultry.jpg",
    link: '/customer?seller_id=16',
  },
  {
    id: 2,
    name: "Ali's Store",
    description: 'Fresh eggs and market goods. Your one-stop shop for daily essentials.',
    rating: 4.7,
    deliveryTime: '20-35 min',
    deliveryFee: 40,
    image: "/images/shops/Ali Store.jpg",
    link: '/customer?seller_id=17',
  },
  {
    id: 3,
    name: "Ate Janice's Gulayan",
    description: 'Fresh vegetables sourced directly from local farms every morning.',
    rating: 4.9,
    deliveryTime: '15-30 min',
    deliveryFee: 35,
    image: "/images/shops/Ate Janice's Gulayan.jpeg",
    link: '/customer?seller_id=18',
  },
  {
    id: 4,
    name: "Kuya Bert's Juicy Meat",
    description: 'Premium cuts of pork and beef. Fresh meat products at the best prices.',
    rating: 4.8,
    deliveryTime: '25-40 min',
    deliveryFee: 50,
    image: "/images/shops/Kuya Bert's Juicy Meat.jpg",
    link: '/customer?seller_id=19',
  },
  {
    id: 5,
    name: "Lalaine & Noy Banana",
    description: 'Wholesale and retail banana dealer. Various banana varieties available.',
    rating: 4.6,
    deliveryTime: '20-35 min',
    deliveryFee: 30,
    image: "/images/shops/Lalaine and Noy Banana wholesale and retail.jpg",
    link: '/customer?seller_id=20',
  },
  {
    id: 6,
    name: "Nick & Raquel Fruits",
    description: 'Fresh fruits wholesale and retail. Wide variety of seasonal fruits.',
    rating: 4.9,
    deliveryTime: '20-40 min',
    deliveryFee: 40,
    image: "/images/shops/Nick and Raquel Fruits.jpg",
    link: '/customer?seller_id=21',
  },
]

const filteredShops = computed(() => {
  if (!search.value) return shops
  return shops.filter(s =>
    s.name.toLowerCase().includes(search.value.toLowerCase()) ||
    s.description.toLowerCase().includes(search.value.toLowerCase())
  )
})
</script>