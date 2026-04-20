<template>
  <GuestLayout>

    <!-- Breadcrumb -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <NuxtLink to="/customer" class="hover:text-green-400 transition-colors">Shop</NuxtLink>
          <span>/</span>
          <span class="text-white truncate max-w-[200px]">{{ product?.name }}</span>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 md:py-10">

      <!-- Loading -->
      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="bg-gray-100 rounded-2xl h-80 md:h-96 animate-pulse" />
        <div class="space-y-4">
          <div class="bg-gray-100 rounded h-8 w-3/4 animate-pulse" />
          <div class="bg-gray-100 rounded h-6 w-1/2 animate-pulse" />
          <div class="bg-gray-100 rounded h-24 animate-pulse" />
          <div class="bg-gray-100 rounded h-12 animate-pulse" />
        </div>
      </div>

      <!-- Product Detail -->
      <div v-else-if="product" class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-10 mb-12">

        <!-- Left: Gallery -->
        <ProductGallery :images="productImages" :product-name="product.name" />

        <!-- Right: Seller Badge + Product Info -->
        <div class="flex flex-col">

          <!-- Seller Badge -->
          <NuxtLink
            :to="`/seller-store/${product.user_id}`"
            class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-full hover:bg-green-100 transition-colors mb-4 w-fit"
          >
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
              {{ (product.seller?.name || product.user?.name || 'S').charAt(0) }}
            </div>
            <span class="text-sm font-semibold text-green-700">
              {{ product.seller?.store_name || product.seller?.name || product.user?.name || 'OBRA Store' }}
            </span>
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </NuxtLink>

          <!-- Product Info -->
          <ProductInfo :product="mappedProduct" @add-to-cart="handleAddToCart" />

        </div>
      </div>

      <!-- Not Found -->
      <div v-else class="flex flex-col items-center justify-center py-20 text-center">
        <span class="text-5xl mb-4">😕</span>
        <h3 class="text-lg font-bold text-gray-700 mb-2">Product not found</h3>
        <NuxtLink to="/customer" class="text-green-500 hover:underline text-sm">Back to Shop</NuxtLink>
      </div>

      <!-- Tabs -->
      <div v-if="product" class="mb-12">
        <ProductTabs :product="mappedProduct" />
      </div>

      <!-- Related Products -->
      <div v-if="relatedProducts.length > 0">
        <h2 class="text-xl md:text-2xl font-black text-gray-900 mb-6">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
          <ProductCard
            v-for="related in relatedProducts"
            :key="related.id"
            :product="related"
          />
        </div>
      </div>

    </div>

    <!-- Added to Cart Toast -->
    <Transition name="slide-up">
      <div v-if="showToast"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm font-semibold px-6 py-3 rounded-full shadow-xl z-50 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Added to cart!
      </div>
    </Transition>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import GuestLayout from '~/components/layout/GuestLayout.vue'
import ProductGallery from '~/components/customer/products/ProductGallery.vue'
import ProductInfo from '~/components/customer/products/ProductInfo.vue'
import ProductTabs from '~/components/customer/products/ProductTabs.vue'
import ProductCard from '~/components/customer/products/ProductCard.vue'

const route = useRoute()
const cartStore = useCartStore()
const authStore = useAuthStore()
const config = useRuntimeConfig()

const product = ref<any>(null)
const relatedProducts = ref<any[]>([])
const loading = ref(true)
const showToast = ref(false)

const imageMap: Record<string, string> = {
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
  'Chicken':       '/images/products/meat/Chicken.png',
  'Pork Meat':     '/images/products/meat/pork_meat.png',
  'Egg':           '/images/products/meat/Egg.png',
  'Rice':          '/images/products/meat/rice.png',
  'Bangus':        '/images/products/meat/Chicken.png',
  'Tilapia':       '/images/products/meat/Chicken.png',
  'Hipon':         '/images/products/meat/Chicken.png',
}

// Use real uploaded images if available, otherwise fall back to imageMap
const productImages = computed(() => {
  if (!product.value) return []
  const uploaded = (product.value.images || []).map((img: any) =>
    `https://api.obra-ur.xyz/storage/${img.path}`
  )
  if (uploaded.length > 0) return uploaded
  return [imageMap[product.value.name] || '/images/products/placeholder.png']
})

const mappedProduct = computed(() => {
  if (!product.value) return null
  const hasDiscount = product.value.original_price &&
    Number(product.value.original_price) > Number(product.value.price)
  const discount = hasDiscount
    ? Math.round((1 - Number(product.value.price) / Number(product.value.original_price)) * 100)
    : 0
  const catName = product.value.category?.name || ''
  return {
    id: product.value.id,
    name: product.value.name,
    sku: product.value.sku || '—',
    price: Number(product.value.price),
    originalPrice: hasDiscount ? Number(product.value.original_price) : undefined,
    discount,
    rating: 4,
    reviews: [],
    brand: 'OBRA Local',
    description: product.value.description || 'Fresh local product from Urdaneta City, Pangasinan.',
    category: catName,
    stock: product.value.stock ?? 0,
    tags: [catName, 'Fresh', 'Local', 'Urdaneta'].filter(Boolean),
    image: imageMap[product.value.name] || '/images/products/placeholder.png',
    sellerId: product.value.user_id ?? 1,
    sellerName: product.value.seller?.name || product.value.user?.name || 'OBRA Store',
  }
})

const loadProduct = async () => {
  loading.value = true
  try {
    const res: any = await $fetch(`/products/${route.params.id}`, {
      baseURL: config.public.apiBase,
      headers: { Accept: 'application/json' },
    })
    product.value = res

    if (res.category_id) {
      const relRes: any = await $fetch('/products', {
        baseURL: config.public.apiBase,
        params: { category_id: res.category_id, per_page: 5 },
        headers: { Accept: 'application/json' },
      })
      relatedProducts.value = (relRes.data || [])
        .filter((p: any) => p.id !== res.id)
        .slice(0, 4)
        .map((p: any) => ({
          id: p.id,
          name: p.name,
          price: Number(p.price),
          originalPrice: p.original_price ? Number(p.original_price) : undefined,
          image: imageMap[p.name] || '/images/products/placeholder.png',
          category: p.category?.name || '',
          rating: 4,
          badge: p.original_price && Number(p.original_price) > Number(p.price) ? 'Sale' : undefined,
          sellerId: p.user_id ?? 1,
          sellerName: p.seller?.name || p.user?.name || 'OBRA Store',
        }))
    }
  } catch (e) {
    console.error('Failed to load product', e)
    product.value = null
  } finally {
    loading.value = false
  }
}

const { requireAuth } = useAuthGate()

const handleAddToCart = (quantity: number) => {
  requireAuth(() => {
    if (!mappedProduct.value) return
    cartStore.addItem({
      productId: mappedProduct.value.id,
      name: mappedProduct.value.name,
      image: mappedProduct.value.image,
      price: mappedProduct.value.price,
      originalPrice: mappedProduct.value.originalPrice,
      quantity,
      category: mappedProduct.value.category,
      sellerId: mappedProduct.value.sellerId,
      sellerName: mappedProduct.value.sellerName,
    })
    showToast.value = true
    setTimeout(() => { showToast.value = false }, 2500)
  })
}

onMounted(() => loadProduct())
</script>

<style scoped>
.slide-up-enter-active, .slide-up-leave-active { transition: all 0.3s ease; }
.slide-up-enter-from, .slide-up-leave-to { opacity: 0; transform: translate(-50%, 20px); }
</style>