<template>
  <GuestLayout>
    <div class="min-h-screen bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Page Header -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-800">Shop</h1>
          <p class="text-sm text-gray-500 mt-1">Fresh products from local sellers in Urdaneta</p>
        </div>

        <div class="flex gap-6">

          <!-- Sidebar Filters -->
          <aside class="w-64 shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

              <!-- Categories -->
              <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Categories</h3>
                <div class="flex flex-col gap-2">
                  <label
                    v-for="cat in categories"
                    :key="cat"
                    class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-green-600"
                  >
                    <input
                      type="checkbox"
                      :value="cat"
                      v-model="selectedCategories"
                      class="accent-green-500"
                    />
                    {{ cat }}
                  </label>
                </div>
              </div>

              <!-- Price Range -->
              <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Price Range</h3>
                <div class="flex items-center gap-2">
                  <input
                    v-model="minPrice"
                    type="number"
                    placeholder="Min"
                    class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm outline-none focus:border-green-500"
                  />
                  <span class="text-gray-400">—</span>
                  <input
                    v-model="maxPrice"
                    type="number"
                    placeholder="Max"
                    class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-sm outline-none focus:border-green-500"
                  />
                </div>
              </div>

              <!-- Rating Filter -->
              <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Min Rating</h3>
                <div class="flex gap-1">
                  <button
                    v-for="star in 5"
                    :key="star"
                    @click="minRating = star"
                    class="text-lg"
                    :class="star <= minRating ? 'text-yellow-400' : 'text-gray-300'"
                  >★</button>
                </div>
              </div>

              <!-- Reset -->
              <button
                @click="resetFilters"
                class="w-full mt-2 text-sm text-green-600 border border-green-500 rounded-lg py-2 hover:bg-green-50 transition-all"
              >
                Reset Filters
              </button>

            </div>
          </aside>

          <!-- Product Grid -->
          <div class="flex-1">

            <!-- Search + Sort -->
            <div class="flex items-center justify-between mb-4">
              <input
                v-model="search"
                placeholder="Search products..."
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm w-64 outline-none focus:border-green-500 bg-white"
              />
              <select
                v-model="sortBy"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-white outline-none"
              >
                <option value="default">Sort: Default</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="rating">Top Rated</option>
              </select>
            </div>

            <!-- Results count -->
            <p class="text-xs text-gray-400 mb-4">Showing {{ filteredProducts.length }} products</p>

            <!-- Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <div
                v-for="product in filteredProducts"
                :key="product.id"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden"
              >
                <!-- Badge -->
                <span
                  v-if="product.badge"
                  class="absolute top-3 left-3 z-10 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"
                >{{ product.badge }}</span>

                <!-- Wishlist -->
                <button class="absolute top-3 right-3 z-10 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-gray-300 hover:text-red-400 transition-colors">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>

                <!-- Image -->
                <NuxtLink :to="`/customer/products/${product.id}`">
                  <div class="p-4 flex items-center justify-center h-44 bg-gray-50">
                    <img
                      :src="product.image"
                      :alt="product.name"
                      class="h-36 w-full object-contain group-hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                </NuxtLink>

                <!-- Info -->
                <div class="p-4 pt-2">
                  <NuxtLink :to="`/customer/products/${product.id}`">
                    <h3 class="text-sm font-semibold text-gray-800 hover:text-green-600 transition-colors mb-1 truncate">{{ product.name }}</h3>
                  </NuxtLink>
                  <p class="text-green-600 font-bold text-sm mb-2">₱{{ product.price.toFixed(2) }}</p>

                  <!-- Stars -->
                  <div class="flex items-center gap-0.5 mb-3">
                    <svg
                      v-for="star in 5"
                      :key="star"
                      class="w-3.5 h-3.5"
                      :class="star <= product.rating ? 'text-yellow-400' : 'text-gray-200'"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    ><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  </div>

                  <!-- Add to Cart -->
                  <button class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-semibold py-2 rounded-xl transition-colors">
                    Add to Cart
                  </button>
                </div>
              </div>
            </div>

            <!-- Empty state -->
            <div v-if="filteredProducts.length === 0" class="text-center py-20 text-gray-400">
              <p class="text-4xl mb-3">🥬</p>
              <p class="text-sm">No products found. Try adjusting your filters.</p>
            </div>

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
const sortBy = ref('default')
const selectedCategories = ref<string[]>([])
const minPrice = ref<number | null>(null)
const maxPrice = ref<number | null>(null)
const minRating = ref(0)

const categories = ['Vegetables', 'Fruits', 'Meat & Fish']

const products = [
  { id: 1,  name: 'Tomato',         price: 20.00, image: '/images/products/vegetables/Tomato.png',       rating: 4, category: 'Vegetables', badge: 'Sale' },
  { id: 2,  name: 'Eggplant',       price: 15.00, image: '/images/products/vegetables/eggplant.png',     rating: 4, category: 'Vegetables' },
  { id: 3,  name: 'Bitter Gourd',   price: 18.00, image: '/images/products/vegetables/bitter_gourd.png', rating: 4, category: 'Vegetables' },
  { id: 4,  name: 'Okra',           price: 12.00, image: '/images/products/vegetables/okra.png',         rating: 4, category: 'Vegetables' },
  { id: 5,  name: 'Sitaw',          price: 15.00, image: '/images/products/vegetables/sitaw.png',        rating: 4, category: 'Vegetables' },
  { id: 6,  name: 'Kangkong',       price: 10.00, image: '/images/products/vegetables/kangkong.png',     rating: 4, category: 'Vegetables' },
  { id: 7,  name: 'Repolyo',        price: 25.00, image: '/images/products/vegetables/repolyo.png',      rating: 4, category: 'Vegetables' },
  { id: 8,  name: 'Carrot',         price: 25.00, image: '/images/products/vegetables/carrot.png',       rating: 4, category: 'Vegetables' },
  { id: 9,  name: 'Potato',         price: 30.00, image: '/images/products/vegetables/potato.png',       rating: 4, category: 'Vegetables' },
  { id: 10, name: 'Sibuyas',        price: 60.00, image: '/images/products/vegetables/sibuyas.png',      rating: 4, category: 'Vegetables' },
  { id: 11, name: 'Bawang',         price: 80.00, image: '/images/products/vegetables/bawang.png',       rating: 4, category: 'Vegetables' },
  { id: 12, name: 'Mais',           price: 15.00, image: '/images/products/vegetables/mais.png',         rating: 4, category: 'Vegetables' },
  { id: 13, name: 'Mango',          price: 50.00, image: '/images/products/fruits/mango.png',            rating: 5, category: 'Fruits', badge: 'Sale' },
  { id: 14, name: 'Saging',         price: 30.00, image: '/images/products/fruits/saging.png',           rating: 4, category: 'Fruits' },
  { id: 15, name: 'Papaya',         price: 40.00, image: '/images/products/fruits/papaya.png',           rating: 4, category: 'Fruits' },
  { id: 16, name: 'Pakwan',         price: 80.00, image: '/images/products/fruits/pakwan.png',           rating: 4, category: 'Fruits' },
  { id: 17, name: 'Pineapple',      price: 45.00, image: '/images/products/fruits/Pineapple.png',        rating: 4, category: 'Fruits' },
  { id: 18, name: 'Avocado',        price: 60.00, image: '/images/products/fruits/Avocado.png',          rating: 4, category: 'Fruits' },
  { id: 19, name: 'Guava',          price: 25.00, image: '/images/products/fruits/Guava.png',            rating: 4, category: 'Fruits' },
  { id: 20, name: 'Rambutan',       price: 35.00, image: '/images/products/fruits/Rambutan.png',         rating: 4, category: 'Fruits' },
  { id: 21, name: 'Chicken',        price: 180.00, image: '/images/products/meat/Chicken.png',           rating: 4, category: 'Meat & Fish' },
  { id: 22, name: 'Pork Meat',      price: 220.00, image: '/images/products/meat/pork_meat.png',         rating: 4, category: 'Meat & Fish' },
  { id: 23, name: 'Egg',            price: 12.00,  image: '/images/products/meat/Egg.png',               rating: 5, category: 'Meat & Fish' },
  { id: 24, name: 'Rice',           price: 55.00,  image: '/images/products/meat/rice.png',              rating: 4, category: 'Meat & Fish' },
]

const filteredProducts = computed(() => {
  let result = [...products]

  if (search.value)
    result = result.filter(p => p.name.toLowerCase().includes(search.value.toLowerCase()))

  if (selectedCategories.value.length > 0)
    result = result.filter(p => selectedCategories.value.includes(p.category))

  if (minPrice.value !== null)
    result = result.filter(p => p.price >= minPrice.value!)

  if (maxPrice.value !== null)
    result = result.filter(p => p.price <= maxPrice.value!)

  if (minRating.value > 0)
    result = result.filter(p => p.rating >= minRating.value)

  if (sortBy.value === 'price-asc') result.sort((a, b) => a.price - b.price)
  if (sortBy.value === 'price-desc') result.sort((a, b) => b.price - a.price)
  if (sortBy.value === 'rating') result.sort((a, b) => b.rating - a.rating)

  return result
})

function resetFilters() {
  search.value = ''
  sortBy.value = 'default'
  selectedCategories.value = []
  minPrice.value = null
  maxPrice.value = null
  minRating.value = 0
}
</script>
