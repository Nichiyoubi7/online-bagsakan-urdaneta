<template>
  <GuestLayout>
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <NuxtLink to="/customer" class="hover:text-green-400 transition-colors">Categories</NuxtLink>
          <span>/</span>
          <NuxtLink :to="`/customer?category=${encodeURIComponent(product.category)}`" class="hover:text-green-400 transition-colors">
            {{ product.category }}
          </NuxtLink>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <ProductGallery :images="product.images" :product-name="product.name" />
        <ProductInfo :product="product" />
      </div>
      <div class="mb-12">
        <ProductTabs :product="product" />
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
    id: 1, name: 'Tomato', rating: 4, sku: 'SKU-001', price: 20.00, originalPrice: 25.00, discount: 20, brand: 'Bagsakan',
    description: 'Fresh red tomatoes perfect for cooking, salads, and sauces.',
    highlights: ['Freshly picked from local farms', 'Rich in lycopene and vitamins', 'Perfect for cooking and salads', 'No artificial coloring'],
    additionalInfo: [{ label: 'Weight', value: '500 g' }, { label: 'Color', value: 'Red' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Organic'],
    reviews: [{ id: 1, name: 'Maria Santos', rating: 5, comment: 'Very fresh and delicious!', date: '2 hrs ago' }],
    images: ['/images/products/vegetables/Tomato.png', '/images/products/vegetables/Tomato.png'],
  },
  {
    id: 2, name: 'Eggplant', rating: 4, sku: 'SKU-002', price: 15.00, originalPrice: 20.00, discount: 25, brand: 'Bagsakan',
    description: 'Fresh eggplants perfect for adobo, tortang talong and other Filipino dishes.',
    highlights: ['Freshly harvested', 'Perfect for Filipino dishes', 'Rich in antioxidants', 'Locally sourced'],
    additionalInfo: [{ label: 'Weight', value: '500 g' }, { label: 'Color', value: 'Purple' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Carlos Reyes', rating: 4, comment: 'Fresh and perfect size!', date: '4 hrs ago' }],
    images: ['/images/products/vegetables/eggplant.png', '/images/products/vegetables/eggplant.png'],
  },
  {
    id: 3, name: 'Bitter Gourd', rating: 4, sku: 'SKU-003', price: 18.00, originalPrice: 22.00, discount: 18, brand: 'Bagsakan',
    description: 'Fresh bitter gourd perfect for pinakbet and other Filipino vegetable dishes.',
    highlights: ['Freshly harvested', 'Rich in vitamins', 'Perfect for Filipino dishes', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '500 g' }, { label: 'Color', value: 'Green' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Aling Nena', rating: 4, comment: 'Perfect for pinakbet!', date: '1 day ago' }],
    images: ['/images/products/vegetables/bitter_gourd.png', '/images/products/vegetables/bitter_gourd.png'],
  },
  {
    id: 4, name: 'Okra', rating: 4, sku: 'SKU-004', price: 12.00, originalPrice: 15.00, discount: 20, brand: 'Bagsakan',
    description: 'Fresh okra perfect for sinigang and other Filipino dishes.',
    highlights: ['Freshly harvested', 'Rich in fiber', 'Perfect for sinigang', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '250 g' }, { label: 'Color', value: 'Green' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Pedro Lim', rating: 4, comment: 'Fresh and tender!', date: '3 hrs ago' }],
    images: ['/images/products/vegetables/okra.png', '/images/products/vegetables/okra.png'],
  },
  {
    id: 5, name: 'Sitaw', rating: 4, sku: 'SKU-005', price: 15.00, originalPrice: 18.00, discount: 17, brand: 'Bagsakan',
    description: 'Fresh string beans perfect for adobo and ginisang sitaw.',
    highlights: ['Freshly harvested', 'Rich in protein', 'Perfect for Filipino dishes', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '250 g' }, { label: 'Color', value: 'Green' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Rosa Garcia', rating: 4, comment: 'Very fresh!', date: '5 hrs ago' }],
    images: ['/images/products/vegetables/sitaw.png', '/images/products/vegetables/sitaw.png'],
  },
  {
    id: 6, name: 'Kangkong', rating: 4, sku: 'SKU-006', price: 10.00, originalPrice: 12.00, discount: 17, brand: 'Bagsakan',
    description: 'Fresh water spinach perfect for adobong kangkong and sinigang.',
    highlights: ['Freshly harvested', 'Rich in iron', 'Perfect for Filipino dishes', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '250 g' }, { label: 'Color', value: 'Green' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Juan Santos', rating: 4, comment: 'Good quality!', date: '2 hrs ago' }],
    images: ['/images/products/vegetables/kangkong.png', '/images/products/vegetables/kangkong.png'],
  },
  {
    id: 7, name: 'Repolyo', rating: 4, sku: 'SKU-007', price: 25.00, originalPrice: 30.00, discount: 17, brand: 'Bagsakan',
    description: 'Fresh cabbage perfect for soups, stir fry and salads.',
    highlights: ['Freshly harvested', 'Rich in vitamins', 'Perfect for soups', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Color', value: 'Green' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Ana Reyes', rating: 4, comment: 'Very fresh and crispy!', date: '1 day ago' }],
    images: ['/images/products/vegetables/repolyo.png', '/images/products/vegetables/repolyo.png'],
  },
  {
    id: 8, name: 'Carrot', rating: 4, sku: 'SKU-008', price: 25.00, originalPrice: 30.00, discount: 17, brand: 'Bagsakan',
    description: 'Fresh carrots perfect for soups, salads and juicing.',
    highlights: ['Freshly harvested', 'Rich in Vitamin A', 'Perfect for soups', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '500 g' }, { label: 'Color', value: 'Orange' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Vegetables' }],
    category: 'Vegetables', tags: ['Vegetable', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Mang Bert', rating: 5, comment: 'Very fresh and crunchy!', date: '3 hrs ago' }],
    images: ['/images/products/vegetables/carrot.png', '/images/products/vegetables/carrot.png'],
  },
  {
    id: 9, name: 'Mango', rating: 5, sku: 'SKU-009', price: 50.00, originalPrice: 60.00, discount: 17, brand: 'Bagsakan',
    description: 'Sweet and juicy mangoes fresh from Pangasinan orchards.',
    highlights: ['Sweet and juicy variety', 'Locally grown in Pangasinan', 'Rich in Vitamin C', 'Perfect for juices and desserts'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Color', value: 'Yellow' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Fruits' }],
    category: 'Fruits', tags: ['Fruit', 'Mango', 'Sweet', 'Local'],
    reviews: [{ id: 1, name: 'Ana Reyes', rating: 5, comment: 'Sweetest mangoes ever!', date: '3 hrs ago' }],
    images: ['/images/products/fruits/mango.png', '/images/products/fruits/mango.png'],
  },
  {
    id: 10, name: 'Saging', rating: 4, sku: 'SKU-010', price: 30.00, originalPrice: 35.00, discount: 14, brand: 'Bagsakan',
    description: 'Fresh bananas perfect for snacking, cooking and making desserts.',
    highlights: ['Freshly harvested', 'Rich in potassium', 'Perfect for snacking', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Color', value: 'Yellow' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Fruits' }],
    category: 'Fruits', tags: ['Fruit', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Lola Caring', rating: 4, comment: 'Very fresh!', date: '1 day ago' }],
    images: ['/images/products/fruits/saging.png', '/images/products/fruits/saging.png'],
  },
  {
    id: 11, name: 'Papaya', rating: 4, sku: 'SKU-011', price: 40.00, originalPrice: 50.00, discount: 20, brand: 'Bagsakan',
    description: 'Fresh papaya perfect for eating fresh or cooking as a vegetable when unripe.',
    highlights: ['Freshly harvested', 'Rich in Vitamin C', 'Perfect for fresh eating', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Color', value: 'Orange' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Fruits' }],
    category: 'Fruits', tags: ['Fruit', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Maria Santos', rating: 4, comment: 'Very sweet!', date: '2 hrs ago' }],
    images: ['/images/products/fruits/papaya.png', '/images/products/fruits/papaya.png'],
  },
  {
    id: 12, name: 'Pakwan', rating: 4, sku: 'SKU-012', price: 80.00, originalPrice: 100.00, discount: 20, brand: 'Bagsakan',
    description: 'Fresh watermelon perfect for beating the heat and staying hydrated.',
    highlights: ['Freshly harvested', 'Rich in water content', 'Perfect for summer', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '3 kg' }, { label: 'Color', value: 'Green/Red' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Fruits' }],
    category: 'Fruits', tags: ['Fruit', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Pedro Cruz', rating: 4, comment: 'Very sweet and refreshing!', date: '1 day ago' }],
    images: ['/images/products/fruits/pakwan.png', '/images/products/fruits/pakwan.png'],
  },
  {
    id: 13, name: 'Pineapple', rating: 4, sku: 'SKU-013', price: 45.00, originalPrice: 55.00, discount: 18, brand: 'Bagsakan',
    description: 'Fresh pineapple perfect for juicing, cooking and eating fresh.',
    highlights: ['Freshly harvested', 'Rich in Vitamin C', 'Perfect for juicing', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Color', value: 'Yellow' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Fruits' }],
    category: 'Fruits', tags: ['Fruit', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Juan dela Cruz', rating: 4, comment: 'Very sweet!', date: '3 hrs ago' }],
    images: ['/images/products/fruits/Pineapple.png', '/images/products/fruits/Pineapple.png'],
  },
  {
    id: 14, name: 'Avocado', rating: 4, sku: 'SKU-014', price: 60.00, originalPrice: 75.00, discount: 20, brand: 'Bagsakan',
    description: 'Fresh avocado perfect for shakes, salads and spreads.',
    highlights: ['Freshly harvested', 'Rich in healthy fats', 'Perfect for shakes', 'Locally grown'],
    additionalInfo: [{ label: 'Weight', value: '500 g' }, { label: 'Color', value: 'Green' }, { label: 'Type', value: 'Organic' }, { label: 'Category', value: 'Fruits' }],
    category: 'Fruits', tags: ['Fruit', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Rosa Mendoza', rating: 5, comment: 'Perfect for shakes!', date: '5 hrs ago' }],
    images: ['/images/products/fruits/Avocado.png', '/images/products/fruits/Avocado.png'],
  },
  {
    id: 15, name: 'Chicken', rating: 4, sku: 'SKU-015', price: 180.00, originalPrice: 200.00, discount: 10, brand: 'Bagsakan',
    description: 'Fresh native chicken from local farms. Perfect for adobo, tinola and lechon manok.',
    highlights: ['Fresh from local farms', 'No hormones or antibiotics', 'Perfect for Filipino dishes', 'Cleaned and ready to cook'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Type', value: 'Native Chicken' }, { label: 'Category', value: 'Meat & Fish' }],
    category: 'Meat & Fish', tags: ['Meat', 'Chicken', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Aling Rosa', rating: 5, comment: 'Very tasty native chicken!', date: '2 hrs ago' }],
    images: ['/images/products/meat/Chicken.png', '/images/products/meat/Chicken.png'],
  },
  {
    id: 16, name: 'Pork Meat', rating: 4, sku: 'SKU-016', price: 220.00, originalPrice: 250.00, discount: 12, brand: 'Bagsakan',
    description: 'Fresh pork meat perfect for adobo, sinigang and other Filipino dishes.',
    highlights: ['Fresh from local farms', 'No preservatives', 'Perfect for Filipino dishes', 'Cleaned and sliced'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Type', value: 'Fresh Pork' }, { label: 'Category', value: 'Meat & Fish' }],
    category: 'Meat & Fish', tags: ['Meat', 'Pork', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Mang Bert', rating: 4, comment: 'Very fresh!', date: '1 day ago' }],
    images: ['/images/products/meat/pork_meat.png', '/images/products/meat/pork_meat.png'],
  },
  {
    id: 17, name: 'Egg', rating: 5, sku: 'SKU-017', price: 12.00, originalPrice: 14.00, discount: 14, brand: 'Bagsakan',
    description: 'Fresh native eggs from free-range chickens. Perfect for any dish.',
    highlights: ['Fresh from free-range chickens', 'Rich in protein', 'Perfect for any dish', 'Locally sourced'],
    additionalInfo: [{ label: 'Count', value: '1 piece' }, { label: 'Type', value: 'Native Egg' }, { label: 'Category', value: 'Meat & Fish' }],
    category: 'Meat & Fish', tags: ['Egg', 'Fresh', 'Local'],
    reviews: [{ id: 1, name: 'Maria Santos', rating: 5, comment: 'Very fresh native eggs!', date: '3 hrs ago' }],
    images: ['/images/products/meat/Egg.png', '/images/products/meat/Egg.png'],
  },
  {
    id: 18, name: 'Rice', rating: 4, sku: 'SKU-018', price: 55.00, originalPrice: 60.00, discount: 8, brand: 'Bagsakan',
    description: 'Premium quality rice from local Pangasinan farms.',
    highlights: ['Freshly milled', 'Premium quality', 'From local farms', 'No additives'],
    additionalInfo: [{ label: 'Weight', value: '1 kg' }, { label: 'Type', value: 'Local Rice' }, { label: 'Category', value: 'Meat & Fish' }],
    category: 'Meat & Fish', tags: ['Rice', 'Local', 'Fresh'],
    reviews: [{ id: 1, name: 'Juan dela Cruz', rating: 4, comment: 'Very good quality rice!', date: '1 day ago' }],
    images: ['/images/products/meat/rice.png', '/images/products/meat/rice.png'],
  },
]

const product = computed(() =>
  allProducts.find(p => p.id === productId) ?? allProducts[0]
)

const relatedProducts = computed(() =>
  allProducts
    .filter(p => p.category === product.value.category && p.id !== productId)
    .slice(0, 4)
    .map(p => ({ ...p, badge: undefined }))
)
</script>
```

---

Save with `Ctrl+S` then go to:
```
localhost:3000/customer/products/1