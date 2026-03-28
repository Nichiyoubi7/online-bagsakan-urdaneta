<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">

    <!-- Header -->
    <h3 class="text-base font-black text-gray-800 mb-4">
      Your order from
      <span class="text-green-600">{{ sellerName }}</span>
    </h3>

    <!-- Items -->
    <div class="flex flex-col gap-3 mb-4">
      <div
        v-for="item in items"
        :key="item.id"
        class="flex items-center justify-between text-sm"
      >
        <div class="flex items-center gap-2">
          <span class="text-gray-500">{{ item.quantity }}x</span>
          <span class="text-gray-700 truncate max-w-[180px]">{{ item.name }}</span>
        </div>
        <span class="text-gray-800 font-medium shrink-0">
          ₱{{ (item.price * item.quantity).toFixed(2) }}
        </span>
      </div>
    </div>

    <!-- Add More -->
    <button class="w-full text-sm text-green-500 hover:text-green-600 font-semibold py-2 border border-dashed border-green-200 rounded-xl mb-4 transition-colors">
      + Add more items
    </button>

    <!-- Voucher -->
    <div class="flex items-center gap-2 mb-4 py-3 border-t border-gray-100">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
      </svg>
      <button class="text-sm text-green-500 hover:text-green-600 font-semibold">
        Apply a voucher
      </button>
    </div>

    <!-- Price Breakdown -->
    <div class="flex flex-col gap-2 py-3 border-t border-gray-100 text-sm">
      <div class="flex items-center justify-between text-gray-600">
        <span>Subtotal</span>
        <span>₱{{ subtotal.toFixed(2) }}</span>
      </div>
      <div class="flex items-center justify-between text-gray-600">
        <span>Standard delivery</span>
        <span class="text-green-500 font-semibold">Free</span>
      </div>
    </div>

    <!-- Total -->
    <div class="flex items-center justify-between py-3 border-t border-gray-100">
      <div>
        <p class="text-sm font-bold text-gray-800">Total</p>
        <p class="text-xs text-gray-400">(incl. fees and tax)</p>
      </div>
      <div class="text-right">
        <p class="text-2xl font-black text-green-600">₱{{ subtotal.toFixed(2) }}</p>
      </div>
    </div>

    <!-- Place Order Button -->
    <button
      @click="$emit('placeOrder')"
      class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md mt-2"
    >
      Place Order
    </button>

  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  sellerName: string
  items: {
    id: number
    name: string
    price: number
    quantity: number
  }[]
}>()

defineEmits<{
  (e: 'placeOrder'): void
}>()

const subtotal = computed(() =>
  props.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
)
</script>