<template>
  <section class="relative bg-green-900 overflow-hidden">

    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/50 z-10" />

    <!-- Background Image placeholder -->
    <div class="absolute inset-0 bg-green-800 z-0">
      <!-- Replace with real image later -->
      <!-- <img src="/images/farm-banner.jpg" class="w-full h-full object-cover" /> -->
      <div class="w-full h-full bg-gradient-to-r from-green-900 via-green-800 to-green-900 flex items-center justify-center">
        <span class="text-8xl opacity-20">🌿</span>
      </div>
    </div>

    <!-- Content -->
    <div class="relative z-20 max-w-7xl mx-auto px-6 py-20 flex flex-col items-center justify-center text-center">

      <!-- Heading -->
      <h2 class="text-white text-2xl md:text-3xl font-black max-w-md leading-tight mb-8">
        We're the Best Organic Farm in the World
      </h2>

      <!-- Play Button -->
      <button
        @click="showVideo = true"
        class="w-16 h-16 bg-white/20 hover:bg-white/30 border-2 border-white rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 group"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-7 h-7 text-white ml-1"
          viewBox="0 0 24 24"
          fill="currentColor"
        >
          <path d="M8 5v14l11-7z"/>
        </svg>
      </button>

    </div>

    <!-- Video Modal -->
    <div
      v-if="showVideo"
      class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
      @click.self="showVideo = false"
    >
      <div class="relative bg-black rounded-2xl overflow-hidden w-full max-w-3xl aspect-video flex items-center justify-center">
        <!-- Close button -->
        <button
          @click="showVideo = false"
          class="absolute top-4 right-4 text-white bg-white/20 hover:bg-white/30 rounded-full w-8 h-8 flex items-center justify-center z-10 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        <!-- Video placeholder — replace src with real video URL -->
        <p class="text-white text-sm opacity-60">Video player coming soon...</p>

        <!-- Uncomment and add your YouTube embed or video URL later:
        <iframe
          class="w-full h-full"
          src="https://www.youtube.com/embed/YOUR_VIDEO_ID"
          frameborder="0"
          allowfullscreen
        />
        -->
      </div>
    </div>

  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

// Controls video modal visibility
const showVideo = ref(false)
</script>