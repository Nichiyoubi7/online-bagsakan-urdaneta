<template>
  <section class="py-16 bg-[#f4f9f0]">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Label -->
      <p class="text-green-500 font-semibold text-xs tracking-widest uppercase text-center mb-2">
        Team
      </p>

      <!-- Section Title -->
      <h2 class="text-2xl font-black text-gray-900 text-center mb-12">
        Our Sellers
      </h2>

      <!-- Sellers Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div
          v-for="seller in sellers"
          :key="seller.name"
          class="flex flex-col items-center text-center group"
        >

          <!-- Seller Photo -->
          <div class="w-full aspect-square rounded-2xl overflow-hidden mb-4 bg-gray-100 shadow-sm">
            <img
              :src="seller.photo"
              :alt="seller.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              @error="handleImageError"
            />
          </div>

          <!-- Seller Name -->
          <h3 class="text-sm font-bold text-gray-800 mb-0.5">
            {{ seller.name }}
          </h3>

          <!-- Seller Role -->
          <p class="text-xs text-gray-400">
            {{ seller.role }}
          </p>

        </div>
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
// OurSellersSection.vue
// Shows featured sellers on the landing page
// Replace photos with real seller images in /public/images/sellers/

const sellers = [
  {
    name: 'Uncle Roy',
    role: 'Ceo & Founder',
    photo: '/images/sellers/uncle-roy.jpg',
  },
  {
    name: 'Auntie Rowena',
    role: 'Market',
    photo: '/images/sellers/auntie-rowena.jpg',
  },
  {
    name: 'Tito Badang',
    role: 'Security Guard',
    photo: '/images/sellers/tito-badang.jpg',
  },
  {
    name: 'Aling Nena',
    role: 'Senior Farmer Manager',
    photo: '/images/sellers/aling-nena.jpg',
  },
]

// Handle broken images — show a placeholder instead
const handleImageError = (e: Event) => {
  const img = e.target as HTMLImageElement
  img.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(img.alt) + '&background=dcfce7&color=16a34a&size=200'
}
</script>