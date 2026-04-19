<template>
  <GuestLayout>
    <div class="min-h-screen flex items-center justify-center px-4 py-20">
      <div class="bg-white rounded-2xl shadow-xl p-10 max-w-md w-full text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <h1 class="text-2xl font-black text-gray-900 mb-2">Payment Successful! 🎉</h1>
        <p class="text-gray-400 text-sm mb-2">Your GCash payment has been received.</p>
        <p class="text-gray-400 text-sm mb-8">Order #{{ orderId }} is now being processed by the seller.</p>
        <NuxtLink
          to="/customer/orders"
          class="w-full block bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors"
        >
          View My Orders
        </NuxtLink>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import GuestLayout from '../../components/layout/GuestLayout.vue'
const route = useRoute()
const orderId = route.query.order_id
</script>