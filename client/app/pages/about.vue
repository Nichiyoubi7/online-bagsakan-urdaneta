<template>
  <GuestLayout>

    <!-- Hero -->
    <div class="bg-[#0f2d1f] text-white py-16">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-2">About Us</p>
        <h1 class="text-4xl font-black mb-4">Online Bagsakan Urdaneta</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Connecting local farmers, sellers, and customers in Urdaneta City, Pangasinan.</p>
      </div>
    </div>

    <!-- Mission & Vision -->
    <div class="max-w-7xl mx-auto px-6 py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
        <div class="bg-green-50 rounded-2xl p-8">
          <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <h2 class="text-xl font-black text-gray-900 mb-3">Our Mission</h2>
          <p class="text-gray-600 text-sm leading-relaxed">To provide a modern digital marketplace that empowers local farmers and sellers in Urdaneta City by connecting them directly to customers — delivering fresh, affordable, and locally-sourced products right at their doorstep.</p>
        </div>
        <div class="bg-[#0f2d1f] rounded-2xl p-8">
          <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
          </div>
          <h2 class="text-xl font-black text-white mb-3">Our Vision</h2>
          <p class="text-green-100 text-sm leading-relaxed">To become the leading online bagsakan platform in Pangasinan — where every Filipino household can access fresh, locally-grown produce with ease, speed, and trust.</p>
        </div>
      </div>

      <!-- Story -->
      <div class="text-center mb-12">
        <h2 class="text-2xl font-black text-gray-900 mb-4">Our Story</h2>
        <p class="text-gray-500 text-sm max-w-3xl mx-auto leading-relaxed">
          Urdaneta City has long been known as the "Bagsakan City" of Pangasinan — a major trading hub where fresh fruits, vegetables, and farm products from all over Northern Luzon converge. However, despite its rich agricultural heritage, local sellers and farmers still lacked a modern platform to reach more customers beyond the physical market.
          <br /><br />
          Online Bagsakan Urdaneta was born out of this need — a thesis project designed to bridge the gap between local sellers and the growing number of customers who prefer the convenience of online shopping. Our platform brings the bagsakan experience to your screen, making it easier than ever to support local farmers and enjoy fresh products delivered straight to your home.
        </p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
        <div v-for="stat in stats" :key="stat.label" class="text-center bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
          <div class="text-3xl font-black text-green-600 mb-1">{{ stat.value }}</div>
          <div class="text-xs text-gray-400 font-medium">{{ stat.label }}</div>
        </div>
      </div>

      <!-- Team -->
      <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-gray-900 mb-2">Meet the Team</h2>
        <p class="text-gray-400 text-sm">The students behind Online Bagsakan Urdaneta</p>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div v-for="member in team" :key="member.name" class="text-center bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <span class="text-green-700 font-black text-xl">{{ member.name[0] }}</span>
          </div>
          <p class="text-sm font-semibold text-gray-800">{{ member.name }}</p>
          <p class="text-xs text-gray-400 mt-1">{{ member.role }}</p>
        </div>
      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import GuestLayout from '~/components/layout/GuestLayout.vue'

const stats = [
  { value: '50+',  label: 'Local Sellers' },
  { value: '200+', label: 'Products Listed' },
  { value: '1K+',  label: 'Customers Served' },
  { value: '100%', label: 'Locally Sourced' },
]

const team = [
  { name: 'Sunday',   role: 'Lead Developer' },
  { name: 'Member 2', role: 'UI/UX Designer' },
  { name: 'Member 3', role: 'Backend Developer' },
  { name: 'Member 4', role: 'Project Manager' },
]
</script>