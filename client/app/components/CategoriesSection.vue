<template>
  <section class="py-12 bg-[#f4f9f0]">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Label -->
      <p class="text-green-500 font-semibold text-xs tracking-widest uppercase text-center mb-2">
        Category
      </p>

      <!-- Section Title -->
      <h2 class="text-2xl font-black text-gray-900 text-center mb-8">
        Shop by Top Categories
      </h2>

      <!-- Categories Grid -->
      <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
        <NuxtLink
          v-for="category in categories"
          :key="category.name"
          :to="`/customer?category=${encodeURIComponent(category.name)}`"
          :class="[
            'flex flex-col items-center gap-2 p-3 rounded-xl border-2 bg-white transition-all duration-200 hover:border-green-400 hover:shadow-md',
            activeCategory === category.name
              ? 'border-green-500 shadow-md'
              : 'border-gray-100'
          ]"
          @click="activeCategory = category.name"
        >
          <!-- Category Image -->
          <div class="w-16 h-16 flex items-center justify-center">
            <img
              :src="category.image"
              :alt="category.name"
              class="w-full h-full object-contain"
            />
          </div>

          <!-- Category Name -->
          <span
            :class="[
              'text-xs font-medium text-center leading-tight',
              activeCategory === category.name
                ? 'text-green-600'
                : 'text-gray-600'
            ]"
          >
            {{ category.name }}
          </span>

        </NuxtLink>
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

// Highlight the active category based on current URL query
const activeCategory = ref(
  route.query.category ? decodeURIComponent(route.query.category as string) : ''
)

const categories = [
  { name: 'Fresh Fruit',      image: '/images/categories/fresh-fruit.png' },
  { name: 'Fresh Vegetables', image: '/images/categories/fresh-vegetables.png' },
  { name: 'Meat & Fish',      image: '/images/categories/meat-fish.png' },
  { name: 'Snacks',           image: '/images/categories/snacks.png' },
  { name: 'Beverages',        image: '/images/categories/beverages.png' },
  { name: 'Beauty & Health',  image: '/images/categories/beauty-health.png' },
  { name: 'Bread & Bakery',   image: '/images/categories/bread-bakery.png' },
  { name: 'Baking Needs',     image: '/images/categories/baking-needs.png' },
  { name: 'Cooking',          image: '/images/categories/cooking.png' },
  { name: 'Diabetic Food',    image: '/images/categories/diabetic-food.png' },
  { name: 'Dish Detergents',  image: '/images/categories/dish-detergents.png' },
  { name: 'Oil',              image: '/images/categories/oil.png' },
]
</script>
```

---

**How it works:**

When you click **Fresh Vegetables** → redirects to:
```
/customer?category=Fresh%20Vegetables
```

When you click **Meat & Fish** → redirects to:
```
/customer?category=Meat%20%26%20Fish