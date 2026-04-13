<template>
  <section class="py-16 bg-[#f4f9f0]">
    <div class="max-w-7xl mx-auto px-6">

      <p class="text-green-500 font-semibold text-xs tracking-widest uppercase text-center mb-2">
        Category
      </p>
      <h2 class="text-2xl font-black text-gray-900 text-center mb-10">
        Shop by Top Categories
      </h2>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">

        <!-- Active categories — clickable NuxtLink -->
        <template v-for="cat in categories" :key="cat.name">

          <NuxtLink
            v-if="cat.active"
            :to="`/customer?category=${encodeURIComponent(cat.dbName)}`"
            class="relative group rounded-2xl p-4 flex flex-col items-center text-center transition-all duration-300 border-2 bg-white border-gray-100 hover:border-green-400 hover:shadow-lg hover:-translate-y-1 cursor-pointer"
          >
            <div
              :class="['w-full rounded-2xl flex items-center justify-center mb-3 overflow-hidden transition-transform duration-300 group-hover:scale-105', cat.bg]"
              style="height: 140px;"
            >
              <img :src="cat.image" :alt="cat.name" class="w-full h-full object-contain p-3" />
            </div>
            <h3 class="text-sm font-bold leading-tight mb-1 text-gray-800">{{ cat.name }}</h3>
            <p class="text-[11px] text-gray-400 leading-snug">{{ cat.description }}</p>
            <span class="mt-3 text-[10px] font-semibold px-3 py-1 rounded-full bg-green-50 text-green-600">
              {{ cat.count }} products
            </span>
          </NuxtLink>

          <!-- Inactive categories — non-clickable div -->
          <div
            v-else
            class="relative group rounded-2xl p-4 flex flex-col items-center text-center border-2 bg-white/60 border-dashed border-gray-200 cursor-default opacity-75"
          >
            <span class="absolute top-2 right-2 bg-gray-100 text-gray-400 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">
              Soon
            </span>
            <div
              :class="['w-full rounded-2xl flex items-center justify-center mb-3 overflow-hidden', cat.bg]"
              style="height: 140px;"
            >
              <img :src="cat.image" :alt="cat.name" class="w-full h-full object-contain p-3" />
            </div>
            <h3 class="text-sm font-bold leading-tight mb-1 text-gray-400">{{ cat.name }}</h3>
            <p class="text-[11px] text-gray-400 leading-snug">{{ cat.description }}</p>
            <span class="mt-3 text-[10px] font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-400">
              Coming Soon
            </span>
          </div>

        </template>
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
const categories = [
  {
    name: 'Fresh Vegetables',
    dbName: 'Vegetables',
    description: 'Lowland & highland produce, heart of the Bagsakan',
    image: '/images/products/Categories/fresh_vegetables.png',
    bg: 'bg-green-50',
    active: true,
    count: 21,
  },
  {
    name: 'Fresh Fruit',
    dbName: 'Fruits',
    description: 'Local Pangasinan fruits — mangoes, calamansi & more',
    image: '/images/products/Categories/Fresh_fruits.png',
    bg: 'bg-orange-50',
    active: true,
    count: 14,
  },
  {
    name: 'Meat & Poultry',
    dbName: 'Meat & Fish',
    description: 'Fresh butcher cuts — pork, beef, carabeef & chicken',
    image: '/images/products/Categories/meat_and_poultry.png',
    bg: 'bg-red-50',
    active: true,
    count: 4,
  },
  {
    name: 'Seafood & Fish',
    dbName: '',
    description: 'Fresh fish, shrimp, and shells from the Isdaan',
    image: '/images/products/Categories/seafoods.png',
    bg: 'bg-blue-50',
    active: false,
    count: 0,
  },
  {
    name: 'Spices & Aromatics',
    dbName: '',
    description: 'Onions, garlic, ginger, chili & black pepper',
    image: '/images/products/Categories/spices.png',
    bg: 'bg-yellow-50',
    active: false,
    count: 0,
  },
  {
    name: 'Pantry & Condiments',
    dbName: '',
    description: 'Soy sauce, vinegar, oil & Pangasinan Bagoong',
    image: '/images/products/Categories/condiments.png',
    bg: 'bg-amber-50',
    active: false,
    count: 0,
  },
  {
    name: 'Rice & Grains',
    dbName: '',
    description: 'Sacks and kilos of rice, corn & munggo',
    image: '/images/products/Categories/rice_and_grains.png',
    bg: 'bg-yellow-50',
    active: false,
    count: 0,
  },
  {
    name: 'Eggs & Dairy',
    dbName: '',
    description: 'Chicken, duck & salted eggs — high-volume staples',
    image: '/images/products/Categories/eggs_and_dairy.png',
    bg: 'bg-orange-50',
    active: false,
    count: 0,
  },
  {
    name: 'Dried Fish & Processed',
    dbName: '',
    description: 'Tuyo, Daing, Tinapa & other preserved goods',
    image: '/images/products/Categories/dried_fish.jpg',
    bg: 'bg-stone-50',
    active: false,
    count: 0,
  },
  {
    name: 'Snacks & Local Deli',
    dbName: '',
    description: 'Tupig, Puto, ready-to-eat viands & local treats',
    image: '/images/products/Categories/Delicacies.jpg',
    bg: 'bg-pink-50',
    active: false,
    count: 0,
  },
  {
    name: 'Non-Food & Household',
    dbName: '',
    description: 'Walis tambo, uling, dish soap & kitchen essentials',
    image: '/images/products/Categories/household.jpg',
    bg: 'bg-gray-50',
    active: false,
    count: 0,
  },
]
</script>