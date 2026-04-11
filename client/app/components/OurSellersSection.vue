<template>
  <section class="py-16 bg-[#f4f9f0]">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Section Label -->
      <p class="text-green-500 font-semibold text-xs tracking-widest uppercase text-center mb-2">
        Client Testimonial
      </p>

      <!-- Section Title -->
      <h2 class="text-2xl font-black text-gray-900 text-center mb-12">
        What our Clients Say
      </h2>

      <!-- Testimonials Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="testimonial in testimonials"
          :key="testimonial.name"
          class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
        >
          <!-- Quote Icon -->
          <div class="text-green-500 mb-4">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
              <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
          </div>

          <!-- Review Text -->
          <p class="text-gray-600 text-sm leading-relaxed mb-6">
            {{ testimonial.review }}
          </p>

          <!-- Reviewer -->
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
              :style="{ backgroundColor: testimonial.color }">
              {{ testimonial.name.charAt(0) }}
            </div>
            <div>
              <p class="text-sm font-bold text-gray-800">{{ testimonial.name }}</p>
              <p class="text-xs text-gray-400">{{ testimonial.role }}</p>
            </div>
            <!-- Stars -->
            <div class="ml-auto flex items-center gap-0.5">
              <span v-for="n in 5" :key="n" :class="n <= testimonial.rating ? 'text-yellow-400' : 'text-gray-200'" class="text-sm">★</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup lang="ts">
const testimonials = [
  {
    name: 'Maria Santos',
    role: 'Customer',
    rating: 5,
    color: '#16a34a',
    review: 'Napakaayo ng serbisyo! Ang mga gulay ay sariwang-sariwa at dumating pa ng maaga. Dito na ako palagi mag-order ng mga pangangailangan sa bahay.',
  },
  {
    name: 'Juan dela Cruz',
    role: 'Customer',
    rating: 5,
    color: '#2563eb',
    review: 'Maginhawa talaga ang OBRA! Hindi na kailangan pang pumunta sa palengke. Fresh ang lahat ng produkto at abot-kaya ang presyo. Highly recommended!',
  },
  {
    name: 'Ana Reyes',
    role: 'Customer',
    rating: 4,
    color: '#dc2626',
    review: 'Ang bilis ng delivery at palagi namang bago ang mga prutas at gulay. Masarap din ang serbisyo ng mga driver. Sulit na sulit ang bawat order ko!',
  },
]
</script>