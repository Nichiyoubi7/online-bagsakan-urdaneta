<template>
  <GuestLayout>
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <NuxtLink to="/customer" class="hover:text-green-400 transition-colors">Shop</NuxtLink>
          <span>/</span>
          <span class="text-white">{{ product?.name }}</span>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <div class="bg-gray-100 rounded-2xl h-96 animate-pulse" />
        <div class="space-y-4">
          <div class="bg-gray-100 rounded h-8 w-3/4 animate-pulse" />
          <div class="bg-gray-100 rounded h-6 w-1/2 animate-pulse" />
          <div class="bg-gray-100 rounded h-24 animate-pulse" />
        </div>
      </div>

      <div v-else-if="product" class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <ProductGallery :images="productImages" :product-name="product.name" />
        <ProductInfo :product="mappedProduct" />
      </div>

      <div v-else class="text-center py-16 text-gray-400">
        Product not found.
      </div>

      <div v-if="product" class="mb-12">
        <ProductTabs :product="mappedProduct" />
      </div>

      <div v-if="relatedProducts.length > 0">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
          <ProductCard v-for="related in relatedProducts" :key="related.id" :product="related" />
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import GuestLayout from '~/components/layout/GuestLayout.vue'
import ProductGallery from '~/components/customer/products/ProductGallery.vue'
import ProductInfo from '~/components/customer/products/ProductInfo.vue'
import ProductTabs from '~/components/customer/products/ProductTabs.vue'
import ProductCard from '~/components/customer/products/ProductCard.vue'

const route = useRoute()
const config = useRuntimeConfig()
const API = config.public.apiBase

const productId = route.params.id

const product = ref<any>(null)
const relatedProducts = ref<any[]>([])
const loading = ref(true)

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
}

const productImages = computed(() => {
  if (!product.value) return []
  return [imageMap[product.value.name] || '/images/products/vegetables/Tomato.png']
})

const mappedProduct = computed(() => {
  if (!product.value) return null
  const discount = product.value.original_price && product.value.original_price > product.value.price
    ? Math.round((1 - product.value.price / product.value.original_price) * 100)
    : 0
  return {
    id: product.value.id,
    name: product.value.name,
    price: product.value.price,
    originalPrice: product.value.original_price,
    discount,
    rating: 4,
    sku: product.value.sku,
    brand: 'Bagsakan',
    description: product.value.description,
    category: product.value.category?.name || '',
    stock: product.value.stock,
    highlights: ['Fresh from local farms', 'Locally sourced in Urdaneta', 'No preservatives', 'Quality guaranteed'],
    additionalInfo: [
      { label: 'Weight', value: `${product.value.weight_kg} kg` },
      { label: 'Category', value: product.value.category?.name || '' },
      { label: 'SKU', value: product.value.sku },
    ],
    tags: [product.value.category?.name || '', 'Fresh', 'Local'],
    reviews: [],
    images: productImages.value,
  }
})

onMounted(async () => {
  try {
    const res: any = await $fetch(`${API}/products/${productId}`)
    product.value = res

    if (product.value?.category_id) {
      const related: any = await $fetch(`${API}/products`, {
        params: { category_id: product.value.category_id, per_page: 4 }
      })
      relatedProducts.value = (related.data || [])
        .filter((p: any) => p.id !== product.value.id)
        .slice(0, 4)
        .map((p: any) => ({
          id: p.id,
          name: p.name,
          price: p.price,
          rating: 4,
          category: p.category?.name || '',
          badge: p.original_price && p.original_price > p.price ? 'Sale' : undefined,
          image: imageMap[p.name] || '/images/products/vegetables/Tomato.png',
        }))
    }
  } catch (e) {
    console.error('Failed to load product', e)
  } finally {
    loading.value = false
  }
})
</script>