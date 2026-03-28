<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">

    <h3 class="text-base font-black text-gray-800 mb-4">Delivery Options</h3>

    <div class="flex flex-col gap-3">
      <label
        v-for="option in deliveryOptions"
        :key="option.value"
        :class="[
          'flex items-center justify-between px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all',
          selected === option.value
            ? 'border-green-500 bg-green-50'
            : 'border-gray-100 hover:border-gray-200'
        ]"
      >
        <div class="flex items-center gap-3">
          <input
            type="radio"
            :value="option.value"
            v-model="selected"
            class="accent-green-500"
            @change="$emit('change', option)"
          />
          <div>
            <p class="text-sm font-semibold text-gray-800">{{ option.label }}</p>
            <p class="text-xs text-gray-400">{{ option.time }}</p>
          </div>
        </div>
        <span
          v-if="option.extra"
          class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-full"
        >
          + ₱{{ option.extra }}
        </span>
        <span
          v-else
          class="text-xs font-semibold text-green-600"
        >
          Free
        </span>
      </label>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineEmits<{
  (e: 'change', option: object): void
}>()

const selected = ref('standard')

const deliveryOptions = [
  { value: 'standard',  label: 'Standard',  time: '5 - 20 mins',           extra: null },
  { value: 'priority',  label: 'Priority',  time: '5 - 15 mins',           extra: 20.30 },
  { value: 'scheduled', label: 'Scheduled', time: 'Select a date and time', extra: null },
]
</script>