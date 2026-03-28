<template>
  <div class="flex items-center justify-between mb-6">

    <!-- Results count -->
    <p class="text-sm text-gray-500">
      Showing <span class="font-semibold text-gray-800">{{ from }}-{{ to }}</span> of
      <span class="font-semibold text-gray-800">{{ total }}</span> results
    </p>

    <!-- Sort dropdown -->
    <div class="flex items-center gap-3">
      <label class="text-sm text-gray-500">Sort by:</label>
      <select
        v-model="sortBy"
        @change="$emit('sort', sortBy)"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none focus:border-green-500 transition-colors bg-white"
      >
        <option value="default">Default</option>
        <option value="price_asc">Price: Low to High</option>
        <option value="price_desc">Price: High to Low</option>
        <option value="rating">Best Rating</option>
        <option value="newest">Newest</option>
      </select>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineProps<{
  from: number
  to: number
  total: number
}>()

defineEmits<{
  (e: 'sort', value: string): void
}>()

const sortBy = ref('default')
</script>