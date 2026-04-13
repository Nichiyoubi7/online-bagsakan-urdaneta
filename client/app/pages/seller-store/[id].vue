<template>
  <GuestLayout>

    <!-- Loading -->
    <div v-if="loading" class="max-w-7xl mx-auto px-4 py-12">
      <div class="h-40 bg-gray-100 rounded-2xl animate-pulse mb-6" />
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="n in 8" :key="n" class="h-64 bg-gray-100 rounded-2xl animate-pulse" />
      </div>
    </div>

    <div v-else-if="seller">

      <!-- Store Banner -->
      <div class="bg-[#0f2d1f] text-white py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center gap-6">

          <!-- Store Avatar -->
          <div class="w-24 h-24 bg-green-500 rounded-2xl flex items-center justify-center text-white font-black text-4xl shrink-0 shadow-lg">
            {{ seller.store_name?.charAt(0) || seller.name?.charAt(0) }}
          </div>

          <!-- Store Info -->
          <div class="flex-1 text-center md:text-left">
            <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-1">Local Seller</p>
            <h1 class="text-3xl font-black mb-2">{{ seller.store_name || seller.name }}</h1>
            <p class="text-green-100 text-sm max-w-xl">{{ seller.store_description || 'Fresh local products from Urdaneta City, Pangasinan.' }}</p>
            <div class="flex items-center gap-4 mt-3 justify-center md:justify-start">
              <span class="text-xs text-green-300">📦 {{ totalProducts }} Products</span>
              <span class="text-xs text-green-300">📍 Urdaneta City, Pangasinan</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Products -->
      <div class="max-w-7xl mx-auto px-6 py-10">
        <h2 class="text-xl font-black text-gray-900 mb-6">Products from this Seller</h2>

        <!-- Empty -->
        <div v-if="products.length === 0" class="text-center py-20 text-gray-400">
          <p class="text-4xl mb-3">🥬</p>
          <p class="text-sm">This seller has no products yet.</p>
        </div>

        <!-- Grid -->
        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
          <ProductCard
            v-for="product in products"
            :key="product.id"
            :product="product"
          />
        </div>

      </div>
    </div>

    <!-- Not Found -->
    <div v-else class="text-center py-20 text-gray-400">
      <p class="text-4xl mb-3">😕</p>
      <p class="text-sm">Seller not found.</p>
      <NuxtLink to="/sellers" class="text-green-500 hover:underline text-sm mt-2 inline-block">Browse Sellers</NuxtLink>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import GuestLayout from '~/components/layout/GuestLayout.vue'
import ProductCard from '~/components/customer/products/ProductCard.vue'

const route = useRoute()
const config = useRuntimeConfig()
const { get } = useApi()

const seller = ref<any>(null)
const products = ref<any[]>([])
const totalProducts = ref(0)
const loading = ref(true)

const imageMap: Record<string, string> = {
  'Tomato':       '/images/products/vegetables/Tomato.png',
  'Eggplant':     '/images/products/vegetables/eggplant.png',
  'Mango':        '/images/products/fruits/mango.png',
  'Chicken':      '/images/products/meat/Chicken.png',
  'Pork Meat':    '/images/products/meat/pork_meat.png',
  'Egg':          '/images/products/meat/Egg.png',
  'Rice':         '/images/products/meat/rice.png',
}

const mapProduct = (p: any) => {
  // Use uploaded images if available, otherwise fall back to imageMap
  const uploadedImages = (p.images || []).map((img: any) =>
    `https://api.obra-ur.xyz/storage/${img.path}`
  )
  return {
    id: p.id,
    name: p.name,
    price: Number(p.price),
    originalPrice: p.original_price ? Number(p.original_price) : undefined,
    image: uploadedImages[0] || imageMap[p.name] || '/images/products/placeholder.png',
    category: p.category?.name || '',
    rating: 4,
    sellerId: p.user_id,
    sellerName: seller.value?.store_name || seller.value?.name || 'OBRA Store',
  }
}

onMounted(async () => {
  loading.value = true
  try {
    const sellerId = route.params.id

    // Load seller info
    const sellerRes: any = await get(`/users/${sellerId}`)
    seller.value = sellerRes

    // Load seller's products
    const productsRes: any = await get('/products', { seller_id: sellerId, per_page: 100 })
    products.value = (productsRes.data || []).map(mapProduct)
    totalProducts.value = productsRes.total || products.value.length

  } catch (e) {
    console.error('Failed to load seller store', e)
    seller.value = null
  } finally {
    loading.value = false
  }
})
</script>