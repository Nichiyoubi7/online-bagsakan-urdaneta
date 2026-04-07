<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-2xl font-black text-gray-900 text-center mb-6">
        Introducing Our Products
      </h2>
      <div class="flex items-center justify-center gap-2 mb-8 flex-wrap">
        <button
          v-for="tab in tabs"
          :key="tab.label"
          @click="handleTabChange(tab)"
          :class="[
            'px-5 py-1.5 rounded-full text-sm font-medium border transition-colors duration-200',
            activeTab === tab.label
              ? 'bg-white border-green-500 text-green-600'
              : 'bg-transparent border-gray-200 text-gray-500 hover:border-green-400 hover:text-green-500'
          ]"
        >
          {{ tab.label }}
        </button>
        <NuxtLink
          to="/customer"
          class="px-5 py-1.5 rounded-full text-sm font-medium border border-gray-200 text-gray-500 hover:border-green-400 hover:text-green-500 transition-colors"
        >
          View All
        </NuxtLink>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
        <div v-for="n in 8" :key="n" class="bg-gray-100 rounded-2xl h-64 animate-pulse" />
      </div>

      <!-- Products -->
      <div v-else-if="products.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
        <ProductCard
          v-for="product in products"
          :key="product.id"
          :product="mapProduct(product)"
        />
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-gray-400 text-sm">
        No products found in this category.
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import ProductCard from './customer/products/ProductCard.vue'

const activeTab = ref('All')
const tabs = [
  { label: 'All',          categoryName: '' },
  { label: 'Vegetable',    categoryName: 'Vegetables' },
  { label: 'Fruit',        categoryName: 'Fruits' },
  { label: 'Meat & Fish',  categoryName: 'Meat & Fish' },
]

const products = ref<any[]>([])
const loading = ref(false)
const categoryMap = ref<Record<string, number>>({})

const API = 'https://api.obra-ur.xyz/api'

const imageMap: Record<string, string> = {
  // Vegetables
  'Tomato':        '/images/products/vegetables/Tomato.png',
  'Eggplant':      '/images/products/vegetables/eggplant.png',
  'Bitter Gourd':  '/images/products/vegetables/Ampalaya.png',
  'Okra':          '/images/products/vegetables/Okra.png',
  'Sitaw':         '/images/products/vegetables/Sitaw.png',
  'Kangkong':      '/images/products/vegetables/kangkong.png',
  'Repolyo':       '/images/products/vegetables/Repolyo.png',
  'Carrot':        '/images/products/vegetables/Carrot.png',
  'Potato':        '/images/products/vegetables/Potato.png',
  'Sibuyas':       '/images/products/vegetables/Sibuyas.png',
  'Bawang':        '/images/products/vegetables/Bawang.png',
  'Luya':          '/images/products/vegetables/Luya.png',
  'Mais':          '/images/products/vegetables/Mais.png',
  'Siling Haba':   '/images/products/vegetables/Siling_Haba.png',
  'Siling Labuyo': '/images/products/vegetables/Siling_Labuyo.png',
  'Upo':           '/images/products/vegetables/Upo.png',
  'Patola':        '/images/products/vegetables/Patola.png',
  'Sigarilyas':    '/images/products/vegetables/Sigarilyas.png',
  'Gabi':          '/images/products/vegetables/Gabi.png',
  'Kamote':        '/images/products/vegetables/Kamote.png',
  'Labanos':       '/images/products/vegetables/Labanos.png',
  // Fruits
  'Banana':        '/images/products/fruits/Banana.png',
  'Mango':         '/images/products/fruits/Mango.png',
  'Papaya':        '/images/products/fruits/Papaya.png',
  'Pakwan':        '/images/products/fruits/Pakwan.png',
  'Bayabas':       '/images/products/fruits/Bayabas.png',
  'Melon':         '/images/products/fruits/Melon.png',
  'Pineapple':     '/images/products/fruits/Pinya.png',
  'Avocado':       '/images/products/fruits/Avocado.png',
  'Guava':         '/images/products/fruits/Guava.png',
  'Rambutan':      '/images/products/fruits/Rambutan.png',
  'Lanzones':      '/images/products/fruits/Lanzones.png',
  'Calamansi':     '/images/products/fruits/Calamansi.png',
  'Orange':        '/images/products/fruits/Orange.png',
  'Apple':         '/images/products/fruits/Apple.png',
  'Grapes':        '/images/products/fruits/Grapes.png',
  // Meat & Fish
  'Chicken':       '/images/products/meat/Chicken.png',
  'Pork Meat':     '/images/products/meat/pork_meat.png',
  'Egg':           '/images/products/meat/Egg.png',
  'Rice':          '/images/products/meat/rice.png',
  'Bangus':        '/images/products/meat/Bangus.png',
  'Tilapia':       '/images/products/meat/Tilapia.png',
  'Hipon':         '/images/products/meat/Hipon.png',
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
    const res: any = await $fetch(`${API}/categories`)
    res.forEach((cat: any) => {
      categoryMap.value[cat.name] = cat.id
    })
  } catch (e) {
    console.error('Failed to load categories', e)
  }
}

const loadProducts = async (categoryId?: number) => {
  loading.value = true
  try {
    const params: Record<string, any> = { per_page: 8 }
    if (categoryId) params.category_id = categoryId
    const res: any = await $fetch(`${API}/products`, { params })
    products.value = res.data
  } catch (e) {
    console.error('Failed to load products', e)
  } finally {
    loading.value = false
  }
}

const handleTabChange = async (tab: { label: string, categoryName: string }) => {
  activeTab.value = tab.label
  const catId = tab.categoryName ? categoryMap.value[tab.categoryName] : undefined
  await loadProducts(catId)
}

onMounted(async () => {
  await loadCategories()
  await loadProducts()
})
</script>