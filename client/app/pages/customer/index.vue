<template>
  <GuestLayout>

    <!-- Page Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
        <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-1">Online Bagsakan</p>
        <h1 class="text-white text-2xl md:text-3xl font-black mb-2">Shop Fresh Products</h1>
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">Shop</span>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">

      <!-- Mobile Filter Toggle -->
      <button
        class="md:hidden flex items-center justify-center gap-2 mb-4 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 shadow-sm w-full"
        @click="showMobileSidebar = !showMobileSidebar"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M9 12h6"/>
        </svg>
        {{ showMobileSidebar ? 'Hide Filters' : 'Show Filters' }}
      </button>

      <div class="flex gap-6">

        <!-- Sidebar -->
        <div :class="['shrink-0 w-64', showMobileSidebar ? 'block' : 'hidden md:block']">
          <ShopSidebar
            :selected-category="selectedCategory"
            :selected-rating="selectedRating"
            :selected-tag="selectedTag"
            @select-category="handleCategorySelect"
            @price-change="handlePriceChange"
            @select-rating="handleRatingSelect"
            @select-tag="handleTagSelect"
          />
        </div>

        <!-- Products Area -->
        <div class="flex-1 min-w-0">
          <ShopTopBar
            :from="paginationFrom"
            :to="paginationTo"
            :total="totalProducts"
            @sort="handleSort"
          />

          <!-- Loading -->
          <div v-if="loading" class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
            <div v-for="n in 6" :key="n" class="bg-gray-100 rounded-2xl h-64 animate-pulse" />
          </div>

          <!-- Products Grid -->
          <div v-else-if="products.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-5">
            <ProductCard
              v-for="product in products"
              :key="product.id"
              :product="mapProduct(product)"
            />
          </div>

          <!-- Empty -->
          <div v-else class="flex flex-col items-center justify-center py-20 text-center">
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
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import GuestLayout from '../../components/layout/GuestLayout.vue'
import ShopSidebar from '../../components/customer/shop/ShopSidebar.vue'
import ShopTopBar from '../../components/customer/shop/ShopTopBar.vue'
import ShopPagination from '../../components/customer/shop/ShopPagination.vue'
import ProductCard from '../../components/customer/products/ProductCard.vue'

const route = useRoute()
const { get } = useApi()

const showMobileSidebar = ref(false)

const selectedCategory = ref(
  route.query.category ? String(route.query.category) : ''
)
const selectedRating = ref(0)
const selectedTag = ref('')
const priceMax = ref(500)
const sortBy = ref('default')

const currentPage = ref(1)
const perPage = 12
const totalProducts = ref(0)
const totalPages = ref(1)

const products = ref<any[]>([])
const loading = ref(false)
const categoryMap = ref<Record<string, number>>({})

const imageMap: Record<string, string> = {
  // Vegetables
  'Tomato':        '/images/products/vegetables/Tomato.png',
  'Eggplant':      '/images/products/vegetables/eggplant.png',
  'Bitter Gourd':  '/images/products/vegetables/bitter_gourd.png',
  'Okra':          '/images/products/vegetables/okra.png',
  'Sitaw':         '/images/products/vegetables/sitaw.png',
  'Kangkong':      '/images/products/vegetables/kangkong.png',
  'Repolyo':       '/images/products/vegetables/repolyo.png',
  'Carrot':        '/images/products/vegetables/carrot.png',
  'Potato':        '/images/products/vegetables/potato.png',
  'Sibuyas':       '/images/products/vegetables/sibuyas.png',
  'Bawang':        '/images/products/vegetables/bawang.png',
  'Luya':          '/images/products/vegetables/luya.png',
  'Mais':          '/images/products/vegetables/mais.png',
  'Siling Haba':   '/images/products/vegetables/siling_haba.png',
  'Siling Labuyo': '/images/products/vegetables/siling_labuyo.png',
  'Upo':           '/images/products/vegetables/upo.png',
  'Patola':        '/images/products/vegetables/patola.png',
  'Sigarilyas':    '/images/products/vegetables/sigarilyas.png',
  'Gabi':          '/images/products/vegetables/gabi.png',
  'Kamote':        '/images/products/vegetables/kamote.png',
  'Labanos':       '/images/products/vegetables/labanos.png',
  // Fruits
  'Banana':        '/images/products/fruits/saging.png',
  'Mango':         '/images/products/fruits/mango.png',
  'Papaya':        '/images/products/fruits/papaya.png',
  'Pakwan':        '/images/products/fruits/pakwan.png',
  'Bayabas':       '/images/products/fruits/Guava.png',
  'Melon':         '/images/products/fruits/melon.png',
  'Pineapple':     '/images/products/fruits/Pineapple.png',
  'Avocado':       '/images/products/fruits/Avocado.png',
  'Guava':         '/images/products/fruits/Guava.png',
  'Rambutan':      '/images/products/fruits/Rambutan.png',
  'Lanzones':      '/images/products/fruits/Lanzones.jpg',
  'Calamansi':     '/images/products/fruits/Calamansi.png',
  'Orange':        '/images/products/fruits/Orange.png',
  'Apple':         '/images/products/fruits/Apple.png',
  'Grapes':        '/images/products/fruits/Grapes.png',
  // Meat & Fish
  'Chicken':       '/images/products/meat/Chicken.png',
  'Pork Meat':     '/images/products/meat/pork_meat.png',
  'Egg':           '/images/products/meat/Egg.png',
  'Rice':          '/images/products/meat/rice.png',
  'Bangus':        '/images/products/meat/Chicken.png',
  'Tilapia':       '/images/products/meat/Chicken.png',
  'Hipon':         '/images/products/meat/Chicken.png',
}

const mapProduct = (p: any) => ({
  id: p.id,
  name: p.name,
  price: Number(p.price),
  originalPrice: p.original_price ? Number(p.original_price) : undefined,
  image: imageMap[p.name] || '/images/products/placeholder.png',
  category: p.category?.name || '',
  rating: 4,
  badge: p.original_price && Number(p.original_price) > Number(p.price) ? 'Sale' : undefined,
})

const loadCategories = async () => {
  try {
    const res: any = await get('/categories')
    res.forEach((cat: any) => {
      categoryMap.value[cat.name] = cat.id
    })
  } catch (e) {
    console.error('Failed to load categories', e)
  }
}

const loadProducts = async () => {
  loading.value = true
  try {
    const params: Record<string, any> = { page: currentPage.value }
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

onMounted(async () => {
  await loadCategories()
  await loadProducts()
})

const paginationFrom = computed(() =>
  totalProducts.value === 0 ? 0 : (currentPage.value - 1) * perPage + 1
)
const paginationTo = computed(() =>
  Math.min(currentPage.value * perPage, totalProducts.value)
)

const handleCategorySelect = async (cat: string) => {
  selectedCategory.value = cat
  currentPage.value = 1
  showMobileSidebar.value = false
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