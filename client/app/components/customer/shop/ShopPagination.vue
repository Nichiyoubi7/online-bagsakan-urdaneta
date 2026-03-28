<template>
  <div class="flex items-center justify-center gap-2 mt-8">

    <!-- Prev -->
    <button
      @click="$emit('change', currentPage - 1)"
      :disabled="currentPage === 1"
      class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-green-500 hover:text-green-500 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
    </button>

    <!-- Page Numbers -->
    <button
      v-for="page in totalPages"
      :key="page"
      @click="$emit('change', page)"
      :class="[
        'w-9 h-9 rounded-full text-sm font-medium transition-colors',
        currentPage === page
          ? 'bg-green-500 text-white'
          : 'border border-gray-200 text-gray-600 hover:border-green-500 hover:text-green-500'
      ]"
    >
      {{ page }}
    </button>

    <!-- Next -->
    <button
      @click="$emit('change', currentPage + 1)"
      :disabled="currentPage === totalPages"
      class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-green-500 hover:text-green-500 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </button>

  </div>
</template>

<script setup lang="ts">
defineProps<{
  currentPage: number
  totalPages: number
}>()

defineEmits<{
  (e: 'change', page: number): void
}>()
</script>