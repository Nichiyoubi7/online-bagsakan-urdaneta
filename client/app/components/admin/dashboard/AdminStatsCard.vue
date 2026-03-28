<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
    <div
      class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shrink-0"
      :style="{ backgroundColor: bgColor }"
    >
      {{ icon }}
    </div>
    <div class="flex-1 min-w-0">
      <p class="text-xs text-gray-400 font-medium mb-1">{{ label }}</p>
      <p class="text-2xl font-black text-gray-800">{{ value }}</p>
      <div class="flex items-center gap-1 mt-1">
        <span :class="['text-xs font-semibold', change >= 0 ? 'text-green-500' : 'text-red-500']">
          {{ change >= 0 ? '↑' : '↓' }} {{ Math.abs(change) }}%
        </span>
        <span class="text-xs text-gray-400">vs last month</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  icon: string
  label: string
  value: string
  change: number
  bgColor: string
}>()
</script>