<template>
  <GuestLayout>

    <!-- Hero Banner -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-10">
        <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-1">
          Online Bagsakan
        </p>
        <h1 class="text-white text-3xl font-black mb-2">Shop Fresh Products</h1>
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">Shop</span>
        </div>
      </div>
    </div>

    <!-- Shop Content -->
    <div class="max-w-7xl mx-auto px-6 py-10">
      <div class="flex gap-8">

        <!-- Sidebar -->
        <ShopSidebar
          :selected-category="selectedCategory"
          :selected-rating="selectedRating"
          :selected-tag="selectedTag"
          @select-category="handleCategorySelect"
          @price-change="handlePriceChange"
          @select-rating="handleRatingSelect"
          @select-tag="handleTagSelect"
        />

        <!-- Main Content -->
        <div class="flex-1 min-w-0">

          <!-- Top Bar -->
          <ShopTopBar
            :from="paginationFrom"
            :to="paginationTo"
            :total="filteredProducts.length"
            @sort="handleSort"
          />

          <!-- Product Grid -->
          <div
            v-if="paginatedProducts.length > 0"
            class="grid grid-cols-2 md:grid-cols-3 gap-5"
          >
            <ProductCard
              v-for="product in paginatedProducts"
              :key="product.id"
              :product="product"
            />
          </div>

          <!-- Empty State -->
          <div
            v-else
            class="flex flex-col items-center justify-center py-20 text-center"
          >
            <span class="text-5xl mb-4">🥦</span>
            <h3 class="text-lg font-bold text-gray-700 mb-2">No products found</h3>
            <p class="text-sm text-gray-400 mb-4">Try adjusting your filters</p>
            <button
              @click="resetFilters"
              class="px-5 py-2 bg-green-500 text-white text-sm font-semibold rounded-full hover:bg-green-600 transition-colors"
            >
              Reset Filters
            </button>
          </div>

          <!-- Pagination -->
          <ShopPagination
            v-if="totalPages > 1"
            :current-page="currentPage"
            :total-pages="totalPages"
            @change="handlePageChange"
          />

        </div>
      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import GuestLayout from '../../components/layout/GuestLayout.vue'
import ShopSidebar from '../../components/customer/shop/ShopSidebar.vue'
import ShopTopBar from '../../components/customer/shop/ShopTopBar.vue'
import ShopPagination from '../../components/customer/shop/ShopPagination.vue'
import ProductCard from '../../components/customer/products/ProductCard.vue'

const route = useRoute()
const selectedCategory = ref(
  route.query.category ? decodeURIComponent(route.query.category as string) : 'All Categories'
)
const selectedRating = ref(0)
const selectedTag = ref('')
const priceMax = ref(500)
const sortBy = ref('default')
const currentPage = ref(1)
const perPage = 12

const allProducts = [
  { id: 1,  name: 'Green Apple',       price: 15.00,  image: '/images/products/green-apple.png',     rating: 4, badge: 'Sale 50%', category: 'Fresh Fruit' },
  { id: 2,  name: 'Guijapur Mango',    price: 15.00,  image: '/images/products/mango.png',           rating: 4, category: 'Fresh Fruit' },
  { id: 3,  name: 'Red Tomatoes',      price: 15.00,  image: '/images/products/tomatoes.png',        rating: 4, category: 'Vegetables' },
  { id: 4,  name: 'Fresh Cauliflower', price: 15.00,  image: '/images/products/cauliflower.png',     rating: 4, category: 'Vegetables' },
  { id: 5,  name: 'Green Lettuce',     price: 15.00,  image: '/images/products/lettuce.png',         rating: 4, category: 'Vegetables' },
  { id: 6,  name: 'Eggplant',          price: 15.00,  image: '/images/products/eggplant.png',        rating: 4, category: 'Vegetables' },
  { id: 7,  name: 'Green Chili',       price: 15.00,  image: '/images/products/green-chili.png',     rating: 4, category: 'Vegetables' },
  { id: 8,  name: 'Bangus',            price: 85.00,  image: '/images/products/bangus.png',          rating: 4, category: 'Meat & Fish' },
  { id: 9,  name: 'Pork Liempo',       price: 220.00, image: '/images/products/pork.png',            rating: 5, category: 'Meat & Fish' },
  { id: 10, name: 'Tilapia',           price: 95.00,  image: '/images/products/tilapia.png',         rating: 4, category: 'Meat & Fish' },
  { id: 11, name: 'Chinese Cabbage',   price: 15.00,  image: '/images/products/chinese-cabbage.png', rating: 4, category: 'Vegetables' },
  { id: 12, name: 'Green Capsicum',    price: 15.00,  image: '/images/products/capsicum.png',        rating: 3, category: 'Vegetables' },
]

const filteredProducts = computed(() => {
  let result = [...allProducts]
  if (selectedCategory.value && selectedCategory.value !== 'All Categories') {
    result = result.filter(p => p.category === selectedCategory.value)
  }
  result = result.filter(p => p.price <= priceMax.value)
  if (selectedRating.value > 0) {
    result = result.filter(p => p.rating >= selectedRating.value)
  }
  if (sortBy.value === 'price_asc')  result.sort((a, b) => a.price - b.price)
  if (sortBy.value === 'price_desc') result.sort((a, b) => b.price - a.price)
  if (sortBy.value === 'rating')     result.sort((a, b) => b.rating - a.rating)
  return result
})

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / perPage))
const paginationFrom = computed(() => (currentPage.value - 1) * perPage + 1)
const paginationTo = computed(() => Math.min(currentPage.value * perPage, filteredProducts.value.length))
const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredProducts.value.slice(start, start + perPage)
})

const handleCategorySelect = (cat: string) => { selectedCategory.value = cat; currentPage.value = 1 }
const handlePriceChange = (val: number) => { priceMax.value = val; currentPage.value = 1 }
const handleRatingSelect = (rating: number) => { selectedRating.value = rating; currentPage.value = 1 }
const handleTagSelect = (tag: string) => { selectedTag.value = tag; currentPage.value = 1 }
const handleSort = (val: string) => { sortBy.value = val; currentPage.value = 1 }
const handlePageChange = (page: number) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
const resetFilters = () => {
  selectedCategory.value = 'All Categories'
  selectedRating.value = 0
  selectedTag.value = ''
  priceMax.value = 500
  sortBy.value = 'default'
  currentPage.value = 1
}
</script>