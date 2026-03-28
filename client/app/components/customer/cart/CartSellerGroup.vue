<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-4 overflow-hidden">

    <!-- Seller Header -->
    <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50">
      <input
        type="checkbox"
        :checked="allSelected"
        @change="$emit('toggleAll', seller.id)"
        class="w-4 h-4 accent-green-500 cursor-pointer"
      />
      <div class="flex items-center gap-2">
        <span class="text-lg">🏪</span>
        <span class="text-sm font-bold text-gray-800">{{ seller.name }}</span>
        <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-medium">
          Official Store
        </span>
      </div>
    </div>

    <!-- Cart Items -->
    <div class="px-5">
      <CartItem
        v-for="item in seller.items"
        :key="item.id"
        :item="item"
        @toggle="$emit('toggleItem', $event)"
        @increase="$emit('increase', $event)"
        @decrease="$emit('decrease', $event)"
        @remove="$emit('remove', $event)"
      />
    </div>

    <!-- Voucher + Shipping -->
    <div class="px-5 py-3 border-t border-gray-100 flex flex-col gap-2">
      <div class="flex items-center gap-2 text-xs text-green-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        <span>Up to ₱15 off voucher available</span>
        <button class="text-green-600 font-semibold hover:underline">More Vouchers</button>
      </div>
      <div class="flex items-center gap-2 text-xs text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span>₱25 off shipping with min order of ₱0</span>
        <button class="text-green-500 font-semibold hover:underline">Learn more</button>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import CartItem from './CartItem.vue'

const props = defineProps<{
  seller: {
    id: number
    name: string
    items: {
      id: number
      name: string
      image: string
      price: number
      quantity: number
      variation: string
      selected: boolean
    }[]
  }
}>()

defineEmits<{
  (e: 'toggleAll', sellerId: number): void
  (e: 'toggleItem', itemId: number): void
  (e: 'increase', itemId: number): void
  (e: 'decrease', itemId: number): void
  (e: 'remove', itemId: number): void
}>()

const allSelected = computed(() =>
  props.seller.items.every(item => item.selected)
)
</script>