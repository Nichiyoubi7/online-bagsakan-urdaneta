<template>
  <section class="bg-[#f4f9f0]">

    <!-- Main Hero -->
    <div class="max-w-7xl mx-auto px-4 py-10 md:py-16 flex flex-col md:flex-row items-center justify-between gap-8">

      <!-- Left: Text Content -->
      <div class="flex-1 max-w-lg text-center md:text-left">
        <p class="text-green-600 font-semibold text-sm tracking-widest uppercase mb-3">
          Welcome to Bagsakan
        </p>
        <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-4">
          Fresh &amp; Healthy <br />
          Organic Food
        </h1>
        <p class="text-gray-700 text-lg mb-2">
           
        </p>
        <p class="text-gray-500 text-sm mb-8">
          Free shipping on all your order. We deliver, you enjoy.
        </p>
        <NuxtLink
          to="/customer"
          class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-7 py-3 rounded-full transition-colors duration-200 shadow-md"
        >
          Shop now
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </NuxtLink>
      </div>

      <!-- Right: Hero Image -->
      <div class="flex-1 flex justify-center items-center">
        <img
          src="/fruits_and_vegetables.png"
          alt="Fresh fruits and vegetables"
          class="w-full max-w-xs md:max-w-xl object-contain drop-shadow-xl"
        />
      </div>

    </div>

    <!-- Feature Badges -->
    <div class="border-t border-green-100 bg-white">
      <div class="max-w-7xl mx-auto px-4 py-5 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">

        <!-- Free Shipping -->
        <div class="flex items-center gap-2 md:gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 md:w-9 md:h-9 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
          </svg>
          <div>
            <p class="text-xs md:text-sm font-semibold text-gray-800">Free Shipping</p>
            <p class="text-[10px] md:text-xs text-gray-400">Free shipping on all your order</p>
          </div>
        </div>

        <!-- Customer Support -->
        <div class="flex items-center gap-2 md:gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 md:w-9 md:h-9 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
          <div>
            <p class="text-xs md:text-sm font-semibold text-gray-800">Customer Support 24/7</p>
            <p class="text-[10px] md:text-xs text-gray-400">Instant access to support</p>
          </div>
        </div>

        <!-- Secure Payment -->
        <div class="flex items-center gap-2 md:gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 md:w-9 md:h-9 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
          <div>
            <p class="text-xs md:text-sm font-semibold text-gray-800">100% Secure Payment</p>
            <p class="text-[10px] md:text-xs text-gray-400">We ensure your money is safe</p>
          </div>
        </div>

        <!-- Money Back -->
        <div class="flex items-center gap-2 md:gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 md:w-9 md:h-9 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 2 2 2-2 2 2 2-2 4 2z"/>
          </svg>
          <div>
            <p class="text-xs md:text-sm font-semibold text-gray-800">Money-Back Guarantee</p>
            <p class="text-[10px] md:text-xs text-gray-400">30 days money-back guarantee</p>
          </div>
        </div>

      </div>
    </div>

  </section>
</template>

<script setup lang="ts">
// HeroSection.vue
</script>