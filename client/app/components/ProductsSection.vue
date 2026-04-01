<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-2xl font-black text-gray-900 text-center mb-6">
        Introducing Our Products
      </h2>
      <div class="flex items-center justify-center gap-2 mb-8 flex-wrap">
        <button
          v-for="tab in tabs"
          :key="tab"
          @click="activeTab = tab"
          :class="[
            'px-5 py-1.5 rounded-full text-sm font-medium border transition-colors duration-200',
            activeTab === tab
              ? 'bg-white border-green-500 text-green-600'
              : 'bg-transparent border-gray-200 text-gray-500 hover:border-green-400 hover:text-green-500'
          ]"
        >
          {{ tab }}
        </button>
        <NuxtLink to="/customer" class="px-5 py-1.5 rounded-full text-sm font-medium border border-gray-200 text-gray-500 hover:border-green-400 hover:text-green-500 transition-colors">
          View All
        </NuxtLink>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" />
      </div>
      <div v-if="filteredProducts.length === 0" class="text-center py-16 text-gray-400 text-sm">
        No products found in this category.
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import ProductCard from './customer/products/ProductCard.vue'

const activeTab = ref('All')
const tabs = ['All', 'Vegetable', 'Fruit', 'Meat & Fish']

const products = [
  { id: 1,  name: 'Tomato',       price: 20.00,  image: '/images/products/vegetables/Tomato.png',       rating: 4, badge: 'Sale', category: 'Vegetable' },
  { id: 2,  name: 'Eggplant',     price: 15.00,  image: '/images/products/vegetables/eggplant.png',     rating: 4, category: 'Vegetable' },
  { id: 3,  name: 'Bitter Gourd', price: 18.00,  image: '/images/products/vegetables/bitter_gourd.png', rating: 4, category: 'Vegetable' },
  { id: 4,  name: 'Okra',         price: 12.00,  image: '/images/products/vegetables/okra.png',         rating: 4, category: 'Vegetable' },
  { id: 5,  name: 'Sitaw',        price: 15.00,  image: '/images/products/vegetables/sitaw.png',        rating: 4, category: 'Vegetable' },
  { id: 6,  name: 'Kangkong',     price: 10.00,  image: '/images/products/vegetables/kangkong.png',     rating: 4, category: 'Vegetable' },
  { id: 7,  name: 'Repolyo',      price: 25.00,  image: '/images/products/vegetables/repolyo.png',      rating: 4, category: 'Vegetable' },
  { id: 8,  name: 'Carrot',       price: 25.00,  image: '/images/products/vegetables/carrot.png',       rating: 4, category: 'Vegetable' },
  { id: 9,  name: 'Mango',        price: 50.00,  image: '/images/products/fruits/mango.png',            rating: 5, badge: 'Sale', category: 'Fruit' },
  { id: 10, name: 'Saging',       price: 30.00,  image: '/images/products/fruits/saging.png',           rating: 4, category: 'Fruit' },
  { id: 11, name: 'Papaya',       price: 40.00,  image: '/images/products/fruits/papaya.png',           rating: 4, category: 'Fruit' },
  { id: 12, name: 'Pakwan',       price: 80.00,  image: '/images/products/fruits/pakwan.png',           rating: 4, category: 'Fruit' },
  { id: 13, name: 'Pineapple',    price: 45.00,  image: '/images/products/fruits/Pineapple.png',        rating: 4, category: 'Fruit' },
  { id: 14, name: 'Avocado',      price: 60.00,  image: '/images/products/fruits/Avocado.png',          rating: 4, category: 'Fruit' },
  { id: 15, name: 'Chicken',      price: 180.00, image: '/images/products/meat/Chicken.png',            rating: 4, category: 'Meat & Fish' },
  { id: 16, name: 'Pork Meat',    price: 220.00, image: '/images/products/meat/pork_meat.png',          rating: 4, category: 'Meat & Fish' },
  { id: 17, name: 'Egg',          price: 12.00,  image: '/images/products/meat/Egg.png',                rating: 5, category: 'Meat & Fish' },
  { id: 18, name: 'Rice',         price: 55.00,  image: '/images/products/meat/rice.png',               rating: 4, category: 'Meat & Fish' },
]

const filteredProducts = computed(() => {
  if (activeTab.value === 'All') return products
  return products.filter(p => p.category === activeTab.value)
})
</script>