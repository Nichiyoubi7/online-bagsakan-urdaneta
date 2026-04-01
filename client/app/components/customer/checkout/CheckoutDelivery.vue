<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
    <h3 class="text-base font-black text-gray-800 mb-4">Delivery Options</h3>

    <!-- Weight Warning -->
    <div v-if="isOverWeight" class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-4">
      <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p class="text-xs text-red-600 font-medium">Order exceeds 10kg limit for delivery. Please choose Pickup instead.</p>
    </div>

    <div class="flex flex-col gap-3">
      <label
        v-for="option in deliveryOptions"
        :key="option.value"
        :class="[
          'flex items-center justify-between px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all',
          isOverWeight && option.value !== 'pickup' ? 'opacity-40 pointer-events-none' : '',
          selected === option.value ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200'
        ]"
      >
        <div class="flex items-center gap-3">
          <input
            type="radio"
            :value="option.value"
            v-model="selected"
            class="accent-green-500"
            :disabled="isOverWeight && option.value !== 'pickup'"
            @change="$emit('change', option)"
          />
          <div>
            <p class="text-sm font-semibold text-gray-800">{{ option.label }}</p>
            <p class="text-xs text-gray-400">{{ option.time }}</p>
          </div>
        </div>
        <span v-if="option.extra" class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
          + ₱{{ option.extra }}
        </span>
        <span v-else class="text-xs font-semibold text-green-600">Free</span>
      </label>
    </div>

    <!-- Weight indicator -->
    <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
      <span>Max delivery weight: <strong class="text-gray-600">10kg</strong> per order</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineProps<{ isOverWeight?: boolean }>()
defineEmits<{ (e: 'change', option: object): void }>()

const selected = ref('standard')
const deliveryOptions = [
  { value: 'standard',  label: 'Standard Delivery', time: '30 - 60 mins',           extra: null  },
  { value: 'priority',  label: 'Priority Delivery',  time: '15 - 30 mins',           extra: 20.00 },
  { value: 'pickup',    label: 'Pickup',              time: 'Pick up from seller',    extra: null  },
]
</script>