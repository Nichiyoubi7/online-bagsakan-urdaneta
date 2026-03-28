<template>
  <div class="flex gap-4">

    <!-- Thumbnails -->
    <div class="flex flex-col gap-2">
      <button
        v-for="(img, index) in images"
        :key="index"
        @click="activeIndex = index"
        :class="[
          'w-16 h-16 rounded-xl border-2 overflow-hidden transition-all',
          activeIndex === index
            ? 'border-green-500'
            : 'border-gray-100 hover:border-green-300'
        ]"
      >
        <img
          :src="img"
          :alt="`Product image ${index + 1}`"
          class="w-full h-full object-contain bg-gray-50"
        />
      </button>
    </div>

    <!-- Main Image -->
    <div class="flex-1 bg-gray-50 rounded-2xl overflow-hidden flex items-center justify-center min-h-[400px]">
      <img
        :src="images[activeIndex]"
        :alt="productName"
        class="w-full h-full object-contain max-h-[400px] p-6"
      />
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineProps<{
  images: string[]
  productName: string
}>()

const activeIndex = ref(0)
</script>