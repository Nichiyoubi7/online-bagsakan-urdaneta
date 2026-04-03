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

const getCategoryFolder = (cat: string) => {
  if (cat === 'Fruits') return 'fruits'
  if (cat === 'Meat & Fish') return 'meat'
  return 'vegetables'
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

onMounted(async () => {
  await loadCategories()
  await loadProducts()
})
</script>