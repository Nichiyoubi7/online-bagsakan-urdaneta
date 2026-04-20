<template>
  <div class="flex flex-col items-center text-center px-6 py-8">

    <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mb-4 shadow-md">
      <span class="text-white text-2xl font-black">O</span>
    </div>

    <h2 class="text-2xl font-black text-gray-900 mb-1">Welcome!</h2>
    <p class="text-gray-400 text-sm mb-6">Sign up or log in to continue</p>

    <button
      @click="$emit('goto', 'login')"
      class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-colors duration-200 mb-3"
    >
      Log In
    </button>

    <button
      @click="$emit('goto-register', 'customer')"
      class="w-full bg-white hover:bg-gray-50 text-gray-800 font-semibold py-3 rounded-xl border border-gray-200 transition-colors duration-200 mb-6"
    >
      Sign Up
    </button>

    <div class="flex items-center gap-3 w-full mb-6">
      <div class="flex-1 h-px bg-gray-200" />
      <span class="text-xs text-gray-400">or continue as</span>
      <div class="flex-1 h-px bg-gray-200" />
    </div>

    <div class="flex flex-col gap-2 w-full">
      <button
        @click="$emit('goto-register', 'seller')"
        class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-green-400 hover:bg-green-50 transition-all duration-200 w-full"
      >
        <span class="text-xl">🏪</span>
        <div class="text-left">
          <p class="text-sm font-semibold text-gray-800">Register as Seller</p>
          <p class="text-xs text-gray-400">List your products on Bagsakan</p>
        </div>
      </button>

      <button
        @click="$emit('goto-register', 'driver')"
        class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-green-400 hover:bg-green-50 transition-all duration-200 w-full"
      >
        <span class="text-xl">🛵</span>
        <div class="text-left">
          <p class="text-sm font-semibold text-gray-800">Register as Driver</p>
          <p class="text-xs text-gray-400">Deliver orders and earn money</p>
        </div>
      </button>
    </div>

    <p class="text-xs text-gray-400 mt-6">
      By signing up, you agree to our
      <NuxtLink to="/terms" class="text-green-500 hover:underline">Terms & Conditions</NuxtLink>
      and
      <NuxtLink to="/privacy" class="text-green-500 hover:underline">Privacy Policy</NuxtLink>
    </p>

  </div>
</template>

<script setup lang="ts">
defineEmits<{
  (e: 'goto', step: string): void
  (e: 'goto-register', role: string): void
  (e: 'close'): void
}>()
</script> 