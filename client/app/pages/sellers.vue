<template>
  <GuestLayout>

    <!-- Hero -->
    <div class="bg-[#0f2d1f] text-white py-16">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-2">Our Community</p>
        <h1 class="text-4xl font-black mb-4">Meet Our Sellers</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Local farmers and vendors from Urdaneta City bringing you the freshest products straight from their farms and stores.</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-16">

      <!-- Search -->
      <div class="flex items-center justify-between mb-8">
        <input v-model="search" placeholder="Search sellers..." class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm w-72 outline-none focus:border-green-500 bg-white" />
        <p class="text-sm text-gray-400">{{ filteredSellers.length }} sellers found</p>
      </div>

      <!-- Sellers Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="seller in filteredSellers"
          :key="seller.id"
          class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow overflow-hidden"
        >
          <!-- Cover -->
          <div class="h-20 w-full" :style="{ background: seller.color }"></div>

          <!-- Info -->
          <div class="px-6 pb-6 pt-4">
            <div class="flex items-center gap-4 mb-4">
              <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-black text-white shrink-0" :style="{ background: seller.color }">
                {{ seller.name[0] }}
              </div>
              <div>
                <p class="font-black text-gray-900 text-sm">{{ seller.name }}</p>
                <p class="text-xs text-gray-400">{{ seller.location }}</p>
              </div>
            </div>
            <p class="text-sm text-gray-500 mb-4 leading-relaxed">{{ seller.description }}</p>
            <div class="flex items-center justify-between mb-4">
              <div class="flex gap-3 text-xs text-gray-400">
                <span>🛍️ {{ seller.products }} products</span>
                <span>⭐ {{ seller.rating }}</span>
              </div>
              <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full" :class="seller.status === 'Open' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">{{ seller.status }}</span>
            </div>
            <NuxtLink to="/customer" class="block w-full text-center bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2.5 rounded-xl transition-all">
              Visit Shop
            </NuxtLink>
          </div>
        </div>
      </div>

    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import GuestLayout from '~/components/layout/GuestLayout.vue'

const search = ref('')

const sellers = [
  { id: 1, name: "Aling Nena's Store",  location: 'Urdaneta City',        description: 'Fresh vegetables and fruits harvested daily from our family farm. Serving Urdaneta for over 20 years.',        products: 24, rating: 4.8, status: 'Open',   color: '#1D9E75' },
  { id: 2, name: 'Fresh Harvest PH',    location: 'Villasis, Pangasinan', description: 'Organic produce direct from our farm. We specialize in leafy greens, root crops, and seasonal fruits.',        products: 18, rating: 4.6, status: 'Open',   color: '#0f2d1f' },
  { id: 3, name: 'Green Market',        location: 'Urdaneta City',        description: 'Your one-stop shop for all your daily fresh produce needs. We source directly from local Pangasinan farmers.',   products: 32, rating: 4.7, status: 'Open',   color: '#2D6A4F' },
  { id: 4, name: 'Bagsak Goods',        location: 'Binalonan, Pangasinan',description: 'Wholesale and retail fresh goods. Best prices in Pangasinan. Delivering freshness every morning.',              products: 15, rating: 4.3, status: 'Closed', color: '#52B788' },
  { id: 5, name: "Mang Bert's Farm",    location: 'Sta. Barbara, Pangasinan', description: 'Family-owned farm selling fresh native chicken, eggs, and vegetables grown without pesticides.',           products: 20, rating: 4.9, status: 'Open',   color: '#40916C' },
  { id: 6, name: 'Lita Fresh Produce',  location: 'Asingan, Pangasinan', description: 'Specializing in fresh fish, seafood, and meat products sourced from trusted local suppliers daily.',            products: 12, rating: 4.5, status: 'Open',   color: '#1B4332' },
]

const filteredSellers = computed(() =>
  sellers.filter(s => s.name.toLowerCase().includes(search.value.toLowerCase()))
)
</script>
```

---

Save with `Ctrl+S` then go to:
```
localhost:3000/sellers