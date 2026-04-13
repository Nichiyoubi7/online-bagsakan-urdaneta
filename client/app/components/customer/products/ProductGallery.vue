<template>
  <div class="flex flex-col gap-4">

    <!-- Main Image with arrows -->
    <div class="relative bg-gray-50 rounded-2xl overflow-hidden aspect-square flex items-center justify-center">
      <img
        :src="currentImage"
        :alt="productName"
        class="w-full h-full object-contain p-4 transition-all duration-300"
      />

      <!-- Left Arrow -->
      <button
        v-if="allImages.length > 1"
        @click="prev"
        class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white shadow-md rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors"
      >
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <!-- Right Arrow -->
      <button
        v-if="allImages.length > 1"
        @click="next"
        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white shadow-md rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors"
      >
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>

      <!-- Dot indicators -->
      <div v-if="allImages.length > 1" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
        <button
          v-for="(_, i) in allImages"
          :key="i"
          @click="currentIndex = i"
          :class="['w-2 h-2 rounded-full transition-colors', i === currentIndex ? 'bg-green-500' : 'bg-gray-300']"
        />
      </div>
    </div>

    <!-- Thumbnail Strip -->
    <div v-if="allImages.length > 1" class="flex gap-2 overflow-x-auto pb-1">
      <button
        v-for="(img, i) in allImages"
        :key="i"
        @click="currentIndex = i"
        :class="['shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 transition-colors',
          i === currentIndex ? 'border-green-500' : 'border-gray-200 hover:border-gray-300']"
      >
        <img :src="img" class="w-full h-full object-cover" />
      </button>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'

const props = defineProps<{
  images: string[]
  productName: string
}>()

const currentIndex = ref(0)

// Reset index when images change
watch(() => props.images, () => {
  currentIndex.value = 0
})

const allImages = computed(() =>
  props.images.length > 0 ? props.images : ['/images/products/placeholder.png']
)

const currentImage = computed(() => allImages.value[currentIndex.value])

const prev = () => {
  currentIndex.value = currentIndex.value === 0
    ? allImages.value.length - 1
    : currentIndex.value - 1
}

const next = () => {
  currentIndex.value = currentIndex.value === allImages.value.length - 1
    ? 0
    : currentIndex.value + 1
}
</script>