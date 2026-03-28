<template>
  <div class="sticky bottom-0 bg-white border-t border-gray-200 shadow-lg z-30">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">

      <!-- Left: Select All + Delete -->
      <div class="flex items-center gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
          <input
            type="checkbox"
            :checked="allSelected"
            @change="$emit('toggleAll')"
            class="w-4 h-4 accent-green-500"
          />
          <span class="text-sm font-medium text-gray-700">Select All ({{ totalItems }})</span>
        </label>
        <button
          @click="$emit('deleteSelected')"
          class="text-sm text-gray-500 hover:text-red-500 transition-colors"
        >
          Delete
        </button>
        <button class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
          Remove inactive products
        </button>
      </div>

      <!-- Right: Total + Checkout -->
      <div class="flex items-center gap-6">

        <!-- Platform Voucher -->
        <div class="flex items-center gap-2 text-sm text-gray-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
          </svg>
          <span>Platform Voucher</span>
          <button class="text-green-500 font-semibold hover:underline text-xs">
            Select or enter code
          </button>
        </div>

        <!-- Total -->
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-600">
            Total ({{ selectedItems }} item{{ selectedItems !== 1 ? 's' : '' }}):
          </span>
          <span class="text-xl font-black text-green-600">
            ₱{{ total.toFixed(2) }}
          </span>
        </div>

        <!-- Checkout Button -->
        <NuxtLink
          to="/customer/checkout"
          class="bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-3 rounded-xl transition-colors shadow-md"
          :class="selectedItems === 0 ? 'opacity-50 pointer-events-none' : ''"
        >
          Check Out
        </NuxtLink>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  allSelected: boolean
  totalItems: number
  selectedItems: number
  total: number
}>()

defineEmits<{
  (e: 'toggleAll'): void
  (e: 'deleteSelected'): void
}>()
</script>