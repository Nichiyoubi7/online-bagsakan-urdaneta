<template>
  <GuestLayout>
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-10">
        <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-1">Online Bagsakan</p>
        <h1 class="text-white text-3xl font-black mb-2">Shop Fresh Products</h1>
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">Shop</span>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
      <div class="flex gap-8">
        <ShopSidebar
          :selected-category="selectedCategory"
          :selected-rating="selectedRating"
          :selected-tag="selectedTag"
          @select-category="handleCategorySelect"
          @price-change="handlePriceChange"
          @select-rating="handleRatingSelect"
          @select-tag="handleTagSelect"
        />
        <div class="flex-1 min-w-0">
          <ShopTopBar
            :from="paginationFrom"
            :to="paginationTo"
            :total="filteredProducts.length"
            @sort="handleSort"
          />
          <div v-if="paginatedProducts.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-5">
            <ProductCard v-for="product in paginatedProducts" :key="product.id" :product="product" />
          </div>
          <div v-else class="flex flex-col items-center justify-center py-20 text-center">
            <span class="text-5xl mb-4">🥦</span>
            <h3 class="text-lg font-bold text-gray-700 mb-2">No products found</h3>
            <p class="text-sm text-gray-400 mb-4">Try adjusting your filters</p>
            <button @click="resetFilters" class="px-5 py-2 bg-green-500 text-white text-sm font-semibold rounded-full hover:bg-green-600 transition-colors">Reset Filters</button>
          </div>
          <ShopPagination v-if="totalPages > 1" :current-page="currentPage" :total-pages="totalPages" @change="handlePageChange" />
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
  { id: 1,  name: 'Tomato',         price: 20.00,  image: '/images/products/vegetables/Tomato.png',          rating: 4, badge: 'Sale', category: 'Vegetables' },
  { id: 2,  name: 'Eggplant',       price: 15.00,  image: '/images/products/vegetables/eggplant.png',        rating: 4, category: 'Vegetables' },
  { id: 3,  name: 'Bitter Gourd',   price: 18.00,  image: '/images/products/vegetables/bitter_gourd.png',    rating: 4, category: 'Vegetables' },
  { id: 4,  name: 'Okra',           price: 12.00,  image: '/images/products/vegetables/okra.png',            rating: 4, category: 'Vegetables' },
  { id: 5,  name: 'Sitaw',          price: 15.00,  image: '/images/products/vegetables/sitaw.png',           rating: 4, category: 'Vegetables' },
  { id: 6,  name: 'Kangkong',       price: 10.00,  image: '/images/products/vegetables/kangkong.png',        rating: 4, category: 'Vegetables' },
  { id: 7,  name: 'Repolyo',        price: 25.00,  image: '/images/products/vegetables/repolyo.png',         rating: 4, category: 'Vegetables' },
  { id: 8,  name: 'Carrot',         price: 25.00,  image: '/images/products/vegetables/carrot.png',          rating: 4, category: 'Vegetables' },
  { id: 9,  name: 'Potato',         price: 30.00,  image: '/images/products/vegetables/potato.png',          rating: 4, category: 'Vegetables' },
  { id: 10, name: 'Sibuyas',        price: 60.00,  image: '/images/products/vegetables/sibuyas.png',         rating: 4, category: 'Vegetables' },
  { id: 11, name: 'Bawang',         price: 80.00,  image: '/images/products/vegetables/bawang.png',          rating: 4, category: 'Vegetables' },
  { id: 12, name: 'Mais',           price: 15.00,  image: '/images/products/vegetables/mais.png',            rating: 4, category: 'Vegetables' },
  { id: 13, name: 'Mango',          price: 50.00,  image: '/images/products/fruits/mango.png',               rating: 5, badge: 'Sale', category: 'Fruits' },
  { id: 14, name: 'Saging',         price: 30.00,  image: '/images/products/fruits/saging.png',              rating: 4, category: 'Fruits' },
  { id: 15, name: 'Papaya',         price: 40.00,  image: '/images/products/fruits/papaya.png',              rating: 4, category: 'Fruits' },
  { id: 16, name: 'Pakwan',         price: 80.00,  image: '/images/products/fruits/pakwan.png',              rating: 4, category: 'Fruits' },
  { id: 17, name: 'Pineapple',      price: 45.00,  image: '/images/products/fruits/Pineapple.png',           rating: 4, category: 'Fruits' },
  { id: 18, name: 'Avocado',        price: 60.00,  image: '/images/products/fruits/Avocado.png',             rating: 4, category: 'Fruits' },
  { id: 19, name: 'Guava',          price: 25.00,  image: '/images/products/fruits/Guava.png',               rating: 4, category: 'Fruits' },
  { id: 20, name: 'Rambutan',       price: 35.00,  image: '/images/products/fruits/Rambutan.png',            rating: 4, category: 'Fruits' },
  { id: 21, name: 'Chicken',        price: 180.00, image: '/images/products/meat/Chicken.png',               rating: 4, category: 'Meat & Fish' },
  { id: 22, name: 'Pork Meat',      price: 220.00, image: '/images/products/meat/pork_meat.png',             rating: 4, category: 'Meat & Fish' },
  { id: 23, name: 'Egg',            price: 12.00,  image: '/images/products/meat/Egg.png',                   rating: 5, category: 'Meat & Fish' },
  { id: 24, name: 'Rice',           price: 55.00,  image: '/images/products/meat/rice.png',                  rating: 4, category: 'Meat & Fish' },
]

const filteredProducts = computed(() => {
  let result = [...allProducts]
  if (selectedCategory.value && selectedCategory.value !== 'All Categories') {
    result = result.filter(p => p.category === selectedCategory.value)
  }
  result = result.filter(p => p.price <= priceMax.value)
  if (selectedRating.value > 0) result = result.filter(p => p.rating >= selectedRating.value)
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
const handlePageChange = (page: number) => { currentPage.value = page; window.scrollTo({ top: 0, behavior: 'smooth' }) }
const resetFilters = () => {
  selectedCategory.value = 'All Categories'
  selectedRating.value = 0
  selectedTag.value = ''
  priceMax.value = 500
  sortBy.value = 'default'
  currentPage.value = 1
}
</script>