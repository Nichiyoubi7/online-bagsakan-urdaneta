<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-base font-black text-gray-800">Payment</h3>
    </div>
    <div class="flex flex-col gap-3">
      <label
        v-for="method in paymentMethods"
        :key="method.id"
        :class="[
          'flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all',
          selected === method.id ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200'
        ]"
      >
        <input type="radio" :value="method.id" v-model="selected" class="accent-green-500" />
        <div class="w-10 h-7 rounded flex items-center justify-center text-white text-xs font-black" :style="{ backgroundColor: method.color }">
          {{ method.icon }}
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-gray-800">{{ method.name }}</p>
          <p class="text-xs text-gray-400">{{ method.detail }}</p>
        </div>
        <span v-if="method.primary" class="text-xs bg-green-100 text-green-600 font-semibold px-2 py-0.5 rounded-full">Primary</span>
      </label>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const selected = ref('cod')
const paymentMethods = [
  {
    id: 'gcash',
    name: 'GCash',
    detail: 'Pay via GCash mobile wallet',
    icon: 'G',
    color: '#0077FF',
    primary: false,
  },
  {
    id: 'cod',
    name: 'Cash on Delivery',
    detail: 'Pay when you receive',
    icon: '₱',
    color: '#22c55e',
    primary: true,
  },
]
</script>