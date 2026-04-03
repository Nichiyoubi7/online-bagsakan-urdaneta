<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">
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
        <NuxtLink to="/customer" class="px-5 py-1.5 rounded-full text-sm font-medium border border-gray-200 text-gray-500 hover:border-green-400 hover:text-green-500 transition-colors">
          View All
        </NuxtLink>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <div v-for="n in 8" :key="n" class="bg-gray-100 rounded-2xl h-64 animate-pulse" />
      </div>

      <!-- Products -->
      <div v-else-if="products.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <ProductCard v-for="product in products" :key="product.id" :product="mapProduct(product)" />
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-gray-400 text-sm">
        No products found in this category.
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import ProductCard from './customer/products/ProductCard.vue'

const activeTab = ref('All')
const tabs = [
  { label: 'All', categoryName: '' },
  { label: 'Vegetable', categoryName: 'Vegetables' },
  { label: 'Fruit', categoryName: 'Fruits' },
  { label: 'Meat & Fish', categoryName: 'Meat & Fish' },
]

const products = ref<any[]>([])
const loading = ref(false)
const categoryMap = ref<Record<string, number>>({})

const API = 'https://online-bagsakan-urdaneta-production.up.railway.app/api'

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

const imageMap: Record<string, string> = {
  'Tomato': '/images/products/vegetables/Tomato.png',
  'Eggplant': '/images/products/vegetables/eggplant.png',
  'Bitter Gourd': '/images/products/vegetables/bitter_gourd.png',
  'Okra': '/images/products/vegetables/okra.png',
  'Sitaw': '/images/products/vegetables/sitaw.png',
  'Kangkong': '/images/products/vegetables/kangkong.png',
  'Repolyo': '/images/products/vegetables/repolyo.png',
  'Carrot': '/images/products/vegetables/carrot.png',
  'Potato': '/images/products/vegetables/potato.png',
  'Sibuyas': '/images/products/vegetables/sibuyas.png',
  'Bawang': '/images/products/vegetables/bawang.png',
  'Luya': '/images/products/vegetables/luya.png',
  'Mais': '/images/products/vegetables/mais.png',
  'Siling Haba': '/images/products/vegetables/siling_haba.png',
  'Siling Labuyo': '/images/products/vegetables/siling_labuyo.png',
  'Upo': '/images/products/vegetables/upo.png',
  'Patola': '/images/products/vegetables/patola.png',
  'Sigarilyas': '/images/products/vegetables/sigarilyas.png',
  'Labanos': '/images/products/vegetables/labanos.png',
  'Gabi': '/images/products/vegetables/gabi.png',
  'Kamote': '/images/products/vegetables/kamote.png',
  'Mango': '/images/products/fruits/mango.png',
  'Saging': '/images/products/fruits/saging.png',
  'Papaya': '/images/products/fruits/papaya.png',
  'Pakwan': '/images/products/fruits/pakwan.png',
  'Melon': '/images/products/fruits/melon.png',
  'Pineapple': '/images/products/fruits/Pineapple.png',
  'Avocado': '/images/products/fruits/Avocado.png',
  'Guava': '/images/products/fruits/Guava.png',
  'Rambutan': '/images/products/fruits/Rambutan.png',
  'Lanzones': '/images/products/fruits/Lanzones.jpg',
  'Calamansi': '/images/products/fruits/Calamansi.png',
  'Orange': '/images/products/fruits/Orange.png',
  'Apple': '/images/products/fruits/Apple.png',
  'Grapes': '/images/products/fruits/Grapes.png',
  'Chicken': '/images/products/meat/Chicken.png',
  'Pork Meat': '/images/products/meat/pork_meat.png',
  'Egg': '/images/products/meat/Egg.png',
  'Rice': '/images/products/meat/rice.png',
  'Bangus': '/images/products/meat/rice.png',
  'Tilapia': '/images/products/meat/rice.png',
  'Hipon': '/images/products/meat/rice.png',
}

const mapProduct = (p: any) => ({
  id: p.id,
  name: p.name,
  price: p.price,
  image: imageMap[p.name] || '/images/products/vegetables/Tomato.png',
  rating: 4,
  category: p.category?.name || '',
  badge: p.original_price && p.original_price > p.price ? 'Sale' : undefined,
})


onMounted(async () => {
  await loadCategories()
  await loadProducts()
})
</script>