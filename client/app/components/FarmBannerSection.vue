<template>
  <section class="relative bg-green-900 overflow-hidden">

    <!-- Background Video -->
    <video
      class="absolute inset-0 w-full h-full object-cover z-0"
      autoplay
      muted
      loop
      playsinline
    >
      <source src="/images/video_banner/banner_video.mp4" type="video/mp4" />
    </video>

    <!-- Dark overlay -->
    <div class="absolute inset-0 bg-black/55 z-10" />

    <!-- Content -->
    <div class="relative z-20 max-w-7xl mx-auto px-6 py-24 flex flex-col items-center justify-center text-center">

      <p class="text-green-400 font-semibold text-xs tracking-widest uppercase mb-3">
        Online Bagsakan Urdaneta
      </p>

      <h2 class="text-white text-2xl md:text-4xl font-black max-w-lg leading-tight mb-4">
        Fresh Products Delivered to Your Door
      </h2>

      <p class="text-green-200 text-sm md:text-base mb-8 max-w-md">
        Support local farmers and vendors from Urdaneta City. Fresh, affordable, and delivered fast.
      </p>

      <div class="flex items-center gap-4">
        <NuxtLink
          to="/customer"
          class="bg-green-500 hover:bg-green-600 text-white font-bold px-7 py-3 rounded-full transition-colors"
        >
          Shop Now →
        </NuxtLink>
        <button
          @click="showVideo = true"
          class="flex items-center gap-2 text-white border border-white/50 hover:border-white px-5 py-3 rounded-full transition-colors"
        >
          <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white ml-0.5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M8 5v14l11-7z"/>
            </svg>
          </div>
          Watch Video
        </button>
      </div>
    </div>

    <!-- Video Modal -->
    <Transition name="fade">
      <div
        v-if="showVideo"
        class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
        @click.self="showVideo = false"
      >
        <div class="relative w-full max-w-3xl aspect-video rounded-2xl overflow-hidden bg-black">
          <button
            @click="showVideo = false"
            class="absolute top-4 right-4 text-white bg-white/20 hover:bg-white/40 rounded-full w-8 h-8 flex items-center justify-center z-10 transition-colors"
          >
            ✕
          </button>
          <video class="w-full h-full object-cover" controls autoplay>
            <source src="/images/video_banner/banner_video.mp4" type="video/mp4" />
          </video>
        </div>
      </div>
    </Transition>

  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const showVideo = ref(false)
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>