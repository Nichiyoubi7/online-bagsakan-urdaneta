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
            :total="totalProducts"
            @sort="handleSort"
          />

          <!-- Loading -->
          <div v-if="loading" class="grid grid-cols-2 md:grid-cols-3 gap-5">
            <div v-for="n in 6" :key="n" class="bg-gray-100 rounded-2xl h-64 animate-pulse" />
          </div>

          <!-- Products -->
          <div v-else-if="products.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-5">
            <ProductCard v-for="product in products" :key="product.id" :product="mapProduct(product)" />
          </div>

          <!-- Empty -->
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
import { useRoute } from 'vue-router'
import GuestLayout from '../../components/layout/GuestLayout.vue'
import ShopSidebar from '../../components/customer/shop/ShopSidebar.vue'
import ShopTopBar from '../../components/customer/shop/ShopTopBar.vue'
import ShopPagination from '../../components/customer/shop/ShopPagination.vue'
import ProductCard from '../../components/customer/products/ProductCard.vue'

const route = useRoute()
const { get } = useApi()

const selectedCategory = ref(
  route.query.category ? decodeURIComponent(route.query.category as string) : ''
)
const selectedRating = ref(0)
const selectedTag = ref('')
const priceMax = ref(500)
const sortBy = ref('default')
const currentPage = ref(1)
const perPage = 12

const products = ref<any[]>([])
const totalProducts = ref(0)
const totalPages = ref(1)
const loading = ref(false)

const categoryMap = ref<Record<string, number>>({})

const loadCategories = async () => {
  const res: any = await get('/categories')
  res.forEach((cat: any) => {
    categoryMap.value[cat.name] = cat.id
  })
}

const loadProducts = async () => {
  loading.value = true
  try {
    const params: Record<string, any> = {
      page: currentPage.value,
    }
    if (selectedCategory.value && selectedCategory.value !== 'All Categories') {
      const catId = categoryMap.value[selectedCategory.value]
      if (catId) params.category_id = catId
    }
    if (sortBy.value === 'price_asc') params.sort = 'price_asc'
    if (sortBy.value === 'price_desc') params.sort = 'price_desc'

    const res: any = await get('/products', params)
    products.value = res.data
    totalProducts.value = res.total
    totalPages.value = res.last_page
  } catch (e) {
    console.error('Failed to load products', e)
  } finally {
    loading.value = false
  }
}

const mapProduct = (p: any) => ({
  id: p.id,
  name: p.name,
  price: p.price,
  image: p.image || `/images/products/${getCategoryFolder(p.category?.name)}/${p.name.toLowerCase().replace(/ /g, '_')}.png`,
  rating: 4,
  category: p.category?.name || '',
  badge: p.original_price && p.original_price > p.price ? 'Sale' : undefined,
})

const getCategoryFolder = (cat: string) => {
  if (cat === 'Fruits') return 'fruits'
  if (cat === 'Meat & Fish') return 'meat'
  return 'vegetables'
}

onMounted(async () => {
  await loadCategories()
  await loadProducts()
})

const paginationFrom = computed(() => totalProducts.value === 0 ? 0 : (currentPage.value - 1) * perPage + 1)
const paginationTo = computed(() => Math.min(currentPage.value * perPage, totalProducts.value))

const handleCategorySelect = async (cat: string) => {
  selectedCategory.value = cat
  currentPage.value = 1
  await loadProducts()
}
const handlePriceChange = (val: number) => { priceMax.value = val }
const handleRatingSelect = (rating: number) => { selectedRating.value = rating }
const handleTagSelect = (tag: string) => { selectedTag.value = tag }
const handleSort = async (val: string) => {
  sortBy.value = val
  currentPage.value = 1
  await loadProducts()
}
const handlePageChange = async (page: number) => {
  currentPage.value = page
  await loadProducts()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
const resetFilters = async () => {
  selectedCategory.value = ''
  selectedRating.value = 0
  selectedTag.value = ''
  priceMax.value = 500
  sortBy.value = 'default'
  currentPage.value = 1
  await loadProducts()
}
</script>