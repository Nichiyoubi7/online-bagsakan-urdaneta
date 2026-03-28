<template>
  <GuestLayout>

    <!-- Breadcrumb Banner -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <NuxtLink to="/customer" class="hover:text-green-400 transition-colors">Categories</NuxtLink>
          <span>/</span>
          <NuxtLink
            :to="`/customer?category=${encodeURIComponent(product.category)}`"
            class="hover:text-green-400 transition-colors"
          >
            {{ product.category }}
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- Product Detail -->
    <div class="max-w-7xl mx-auto px-6 py-10">

      <!-- Top Section: Gallery + Info -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">

        <!-- Left: Image Gallery -->
        <ProductGallery
          :images="product.images"
          :product-name="product.name"
        />

        <!-- Right: Product Info -->
        <ProductInfo :product="product" />

      </div>

      <!-- Tabs -->
      <div class="mb-12">
        <ProductTabs :product="product" />
      </div>

      <!-- Related Products -->
      <div v-if="relatedProducts.length > 0">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
          <ProductCard
            v-for="related in relatedProducts"
            :key="related.id"
            :product="related"
          />
        </div>
      </div>

    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import GuestLayout from '../../../components/layout/GuestLayout.vue'
import ProductGallery from '../../../components/customer/products/ProductGallery.vue'
import ProductInfo from '../../../components/customer/products/ProductInfo.vue'
import ProductTabs from '../../../components/customer/products/ProductTabs.vue'
import ProductCard from '../../../components/customer/products/ProductCard.vue'

const route = useRoute()
const productId = Number(route.params.id)

const allProducts = [
  {
    id: 1,
    name: 'Green Apple',
    rating: 4,
    reviews: [
      { id: 1, name: 'Maria Santos',   rating: 5, comment: 'Very fresh and delicious!',      date: '2 hrs ago' },
      { id: 2, name: 'Juan dela Cruz', rating: 4, comment: 'Great quality, will buy again.', date: '1 day ago' },
    ],
    sku: 'SKU-001',
    price: 15.00,
    originalPrice: 25.00,
    discount: 40,
    brand: 'Bagsakan',
    description: 'Fresh green apples sourced directly from local farms in Pangasinan. Crispy, sweet and healthy.',
    highlights: [
      'Freshly harvested from local farms',
      'Rich in vitamins and antioxidants',
      'Perfect for snacking or cooking',
      'No artificial preservatives',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '1 kg' },
      { label: 'Color',    value: 'Green' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Fresh Fruit' },
    ],
    category: 'Fresh Fruit',
    tags: ['Fruit', 'Fresh', 'Organic', 'Healthy'],
    images: ['/images/products/green-apple.png', '/images/products/green-apple.png'],
  },
  {
    id: 2,
    name: 'Guijapur Mango',
    rating: 4,
    reviews: [
      { id: 1, name: 'Ana Reyes', rating: 5, comment: 'Sweetest mangoes I have ever tasted!', date: '3 hrs ago' },
    ],
    sku: 'SKU-002',
    price: 15.00,
    originalPrice: 20.00,
    discount: 25,
    brand: 'Bagsakan',
    description: 'Sweet and juicy Guijapur mangoes fresh from Pangasinan orchards.',
    highlights: [
      'Sweet and juicy variety',
      'Locally grown in Pangasinan',
      'Rich in Vitamin C',
      'Perfect for juices and desserts',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '1 kg' },
      { label: 'Color',    value: 'Yellow' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Fresh Fruit' },
    ],
    category: 'Fresh Fruit',
    tags: ['Fruit', 'Mango', 'Sweet', 'Local'],
    images: ['/images/products/mango.png', '/images/products/mango.png'],
  },
  {
    id: 3,
    name: 'Red Tomatoes',
    rating: 4,
    reviews: [
      { id: 1, name: 'Pedro Cruz', rating: 4, comment: 'Perfect for cooking!', date: '1 day ago' },
    ],
    sku: 'SKU-003',
    price: 15.00,
    originalPrice: 20.00,
    discount: 25,
    brand: 'Bagsakan',
    description: 'Fresh red tomatoes perfect for cooking, salads, and sauces.',
    highlights: [
      'Freshly picked from local farms',
      'Rich in lycopene and vitamins',
      'Perfect for cooking and salads',
      'No artificial coloring',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '500 g' },
      { label: 'Color',    value: 'Red' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Vegetables' },
    ],
    category: 'Vegetables',
    tags: ['Vegetable', 'Fresh', 'Organic'],
    images: ['/images/products/tomatoes.png', '/images/products/tomatoes.png'],
  },
  {
    id: 4,
    name: 'Chinese Cabbage',
    rating: 4,
    reviews: [
      { id: 1, name: 'Kristi Watson',  rating: 5, comment: 'Puls in dapimper nulla, eu dictum arcu.', date: '2 hrs ago' },
      { id: 2, name: 'Jake Cooper',    rating: 4, comment: 'Amazing product for the freshest quality.', date: '1 day ago' },
      { id: 3, name: 'Jacob Jones',    rating: 5, comment: 'Wow! Fresh and crispy.', date: '3 hrs ago' },
    ],
    sku: 'SKU-123/94',
    price: 15.00,
    originalPrice: 42.00,
    discount: 64,
    brand: 'Bagsakan',
    description: 'Fresh Chinese cabbage sourced from local Urdaneta farmers. Perfect for stir fry and soups.',
    highlights: [
      '100 g of fresh leaves per serving',
      'Rich in vitamins A, C and K',
      'Perfect for stir fry and soups',
      'Freshly harvested daily',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '1 kg' },
      { label: 'Color',    value: 'Green' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Vegetables' },
    ],
    category: 'Vegetables',
    tags: ['Vegetables', 'Healthy', 'Chinese', 'Cabbage'],
    images: ['/images/products/chinese-cabbage.png', '/images/products/chinese-cabbage.png'],
  },
  {
    id: 5,
    name: 'Green Lettuce',
    rating: 4,
    reviews: [
      { id: 1, name: 'Rosa Mendoza', rating: 4, comment: 'Very fresh and crispy!', date: '5 hrs ago' },
    ],
    sku: 'SKU-005',
    price: 15.00,
    originalPrice: 18.00,
    discount: 17,
    brand: 'Bagsakan',
    description: 'Fresh green lettuce perfect for salads and sandwiches.',
    highlights: [
      'Crispy and fresh leaves',
      'Low in calories',
      'Perfect for salads',
      'Locally grown',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '500 g' },
      { label: 'Color',    value: 'Green' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Vegetables' },
    ],
    category: 'Vegetables',
    tags: ['Vegetable', 'Salad', 'Fresh'],
    images: ['/images/products/lettuce.png', '/images/products/lettuce.png'],
  },
  {
    id: 6,
    name: 'Eggplant',
    rating: 4,
    reviews: [
      { id: 1, name: 'Carlos Reyes', rating: 4, comment: 'Fresh and perfect size!', date: '4 hrs ago' },
    ],
    sku: 'SKU-006',
    price: 15.00,
    originalPrice: 20.00,
    discount: 25,
    brand: 'Bagsakan',
    description: 'Fresh eggplants perfect for adobo, tortang talong and other Filipino dishes.',
    highlights: [
      'Freshly harvested',
      'Perfect for Filipino dishes',
      'Rich in antioxidants',
      'Locally sourced',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '500 g' },
      { label: 'Color',    value: 'Purple' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Vegetables' },
    ],
    category: 'Vegetables',
    tags: ['Vegetable', 'Fresh', 'Local'],
    images: ['/images/products/eggplant.png', '/images/products/eggplant.png'],
  },
  {
    id: 7,
    name: 'Green Chili',
    rating: 4,
    reviews: [
      { id: 1, name: 'Nena Santos', rating: 5, comment: 'Very spicy and fresh!', date: '6 hrs ago' },
    ],
    sku: 'SKU-007',
    price: 15.00,
    originalPrice: 18.00,
    discount: 17,
    brand: 'Bagsakan',
    description: 'Fresh green chili peppers perfect for adding spice to your dishes.',
    highlights: [
      'Freshly picked',
      'Rich in Vitamin C',
      'Perfect for spicy dishes',
      'Locally grown',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '250 g' },
      { label: 'Color',    value: 'Green' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Vegetables' },
    ],
    category: 'Vegetables',
    tags: ['Vegetable', 'Spicy', 'Fresh'],
    images: ['/images/products/green-chili.png', '/images/products/green-chili.png'],
  },
  {
    id: 8,
    name: 'Bangus',
    rating: 4,
    reviews: [
      { id: 1, name: 'Mang Bert', rating: 5, comment: 'Very fresh from the fish pond!', date: '1 hr ago' },
    ],
    sku: 'SKU-008',
    price: 85.00,
    originalPrice: 100.00,
    discount: 15,
    brand: 'Bagsakan',
    description: 'Fresh bangus (milkfish) sourced from local fish ponds in Pangasinan.',
    highlights: [
      'Fresh from local fish ponds',
      'Rich in Omega-3',
      'Perfect for sinigang and daing',
      'Cleaned and ready to cook',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '1 kg' },
      { label: 'Type',     value: 'Fresh Fish' },
      { label: 'Category', value: 'Meat & Fish' },
    ],
    category: 'Meat & Fish',
    tags: ['Fish', 'Fresh', 'Seafood', 'Local'],
    images: ['/images/products/bangus.png', '/images/products/bangus.png'],
  },
  {
    id: 9,
    name: 'Pork Liempo',
    rating: 5,
    reviews: [
      { id: 1, name: 'Aling Rosa', rating: 5, comment: 'Perfect for inihaw!', date: '2 hrs ago' },
    ],
    sku: 'SKU-009',
    price: 220.00,
    originalPrice: 260.00,
    discount: 15,
    brand: 'Bagsakan',
    description: 'Fresh pork liempo (belly) perfect for inihaw, lechon kawali and adobo.',
    highlights: [
      'Fresh from local farms',
      'Perfect for grilling',
      'No preservatives',
      'Cleaned and sliced',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '1 kg' },
      { label: 'Type',     value: 'Fresh Pork' },
      { label: 'Category', value: 'Meat & Fish' },
    ],
    category: 'Meat & Fish',
    tags: ['Meat', 'Pork', 'Fresh', 'Local'],
    images: ['/images/products/pork.png', '/images/products/pork.png'],
  },
  {
    id: 10,
    name: 'Tilapia',
    rating: 4,
    reviews: [
      { id: 1, name: 'Tatay Ben', rating: 4, comment: 'Good quality fish!', date: '3 hrs ago' },
    ],
    sku: 'SKU-010',
    price: 95.00,
    originalPrice: 110.00,
    discount: 14,
    brand: 'Bagsakan',
    description: 'Fresh tilapia from local fish ponds. Perfect for sinigang and paksiw.',
    highlights: [
      'Fresh from fish ponds',
      'High in protein',
      'Perfect for Filipino dishes',
      'Cleaned and ready to cook',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '1 kg' },
      { label: 'Type',     value: 'Fresh Fish' },
      { label: 'Category', value: 'Meat & Fish' },
    ],
    category: 'Meat & Fish',
    tags: ['Fish', 'Fresh', 'Seafood'],
    images: ['/images/products/tilapia.png', '/images/products/tilapia.png'],
  },
  {
    id: 11,
    name: 'Chinese Cabbage',
    rating: 4,
    reviews: [
      { id: 1, name: 'Lola Caring', rating: 4, comment: 'Very fresh!', date: '1 day ago' },
    ],
    sku: 'SKU-011',
    price: 15.00,
    originalPrice: 20.00,
    discount: 25,
    brand: 'Bagsakan',
    description: 'Fresh Chinese cabbage perfect for soups and stir fry.',
    highlights: [
      'Freshly harvested',
      'Perfect for soups',
      'Rich in vitamins',
      'Locally grown',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '1 kg' },
      { label: 'Color',    value: 'Green' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Vegetables' },
    ],
    category: 'Vegetables',
    tags: ['Vegetable', 'Fresh', 'Organic'],
    images: ['/images/products/chinese-cabbage.png', '/images/products/chinese-cabbage.png'],
  },
  {
    id: 12,
    name: 'Green Capsicum',
    rating: 3,
    reviews: [
      { id: 1, name: 'Kuya Jun', rating: 3, comment: 'Good quality but a bit small.', date: '2 days ago' },
    ],
    sku: 'SKU-012',
    price: 15.00,
    originalPrice: 18.00,
    discount: 17,
    brand: 'Bagsakan',
    description: 'Fresh green capsicum perfect for salads, stir fry and stuffed peppers.',
    highlights: [
      'Freshly harvested',
      'Rich in Vitamin C',
      'Perfect for stir fry',
      'Locally sourced',
    ],
    additionalInfo: [
      { label: 'Weight',   value: '500 g' },
      { label: 'Color',    value: 'Green' },
      { label: 'Type',     value: 'Organic' },
      { label: 'Category', value: 'Vegetables' },
    ],
    category: 'Vegetables',
    tags: ['Vegetable', 'Fresh', 'Organic'],
    images: ['/images/products/capsicum.png', '/images/products/capsicum.png'],
  },
]

// Find product by ID from URL
const product = computed(() =>
  allProducts.find(p => p.id === productId) ?? allProducts[0]
)

// Related products — same category, exclude current
const relatedProducts = computed(() =>
  allProducts
    .filter(p => p.category === product.value.category && p.id !== productId)
    .slice(0, 4)
    .map(p => ({ ...p, badge: undefined }))
)
</script>