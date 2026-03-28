<template>
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Label -->
      <p class="text-green-500 font-semibold text-xs tracking-widest uppercase text-center mb-2">
        How It Works
      </p>

      <!-- Section Title -->
      <h2 class="text-2xl font-black text-gray-900 text-center mb-4">
        Order in 4 Easy Steps
      </h2>
      <p class="text-gray-400 text-sm text-center max-w-md mx-auto mb-12">
        Fresh food from Urdaneta's local sellers delivered straight to your door.
      </p>

      <!-- Steps -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="(step, index) in steps"
          :key="step.title"
          class="flex flex-col items-center text-center"
        >
          <!-- Icon Circle -->
          <div
            class="relative w-20 h-20 rounded-full flex items-center justify-center mb-5 shadow-md"
            :class="step.bgColor"
          >
            <span class="text-3xl">{{ step.emoji }}</span>
            <span class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 text-white text-xs font-black rounded-full flex items-center justify-center">
              {{ index + 1 }}
            </span>
          </div>

          <!-- Title -->
          <h3 class="text-base font-bold text-gray-800 mb-2">
            {{ step.title }}
          </h3>

          <!-- Description -->
          <p class="text-sm text-gray-400 leading-relaxed">
            {{ step.description }}
          </p>
        </div>
      </div>

      <!-- CTA Button -->
      <div class="flex justify-center mt-12">
        <NuxtLink
          to="/customer"
          class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-3 rounded-full transition-colors duration-200 shadow-md"
        >
          Start Shopping
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </NuxtLink>
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
const steps = [
  {
    title: 'Browse Products',
    description: 'Explore fresh vegetables, fruits, meat and more from local Urdaneta sellers.',
    emoji: '🛒',
    bgColor: 'bg-green-50',
  },
  {
    title: 'Choose a Seller',
    description: 'Pick your favorite local seller or bagsakan stall and add items to your cart.',
    emoji: '🏪',
    bgColor: 'bg-blue-50',
  },
  {
    title: 'Place Your Order',
    description: 'Checkout securely and choose your preferred delivery address in Urdaneta.',
    emoji: '📦',
    bgColor: 'bg-yellow-50',
  },
  {
    title: 'Fast Delivery',
    description: 'Our drivers pick up your order and deliver it fresh right to your doorstep.',
    emoji: '🛵',
    bgColor: 'bg-orange-50',
  },
]
</script>