<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">

      <!-- 3 Promo Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- Card 1: Sale of the Month (Blue) -->
        <div class="relative rounded-2xl overflow-hidden bg-blue-500 p-6 flex flex-col justify-between min-h-[220px]">

          <div class="relative z-10">
            <p class="text-blue-100 text-xs font-semibold uppercase tracking-widest mb-1">
              Best Deals
            </p>
            <h3 class="text-white text-2xl font-black leading-tight mb-4">
              Sale of the <br /> Month
            </h3>

            <!-- Countdown Timer -->
            <div class="flex items-center gap-2 mb-4">
              <div
                v-for="unit in countdown"
                :key="unit.label"
                class="flex flex-col items-center"
              >
                <span class="bg-white text-blue-600 font-black text-sm w-9 h-9 flex items-center justify-center rounded-lg">
                  {{ unit.value }}
                </span>
                <span class="text-blue-100 text-[10px] mt-1">{{ unit.label }}</span>
              </div>
            </div>
          </div>

          <!-- Shop Now -->
          <NuxtLink
            to="/customer"
            class="relative z-10 inline-flex items-center gap-2 bg-white text-blue-600 font-semibold text-sm px-5 py-2 rounded-full w-fit hover:bg-blue-50 transition-colors"
          >
            Shop now
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>

        </div>

        <!-- Card 2: Low-Fat Meat (Dark) -->
        <div class="relative rounded-2xl overflow-hidden bg-gray-900 p-6 flex flex-col justify-between min-h-[220px]">

          <div class="relative z-10">
            <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">
              85% Fat Free
            </p>
            <h3 class="text-white text-2xl font-black leading-tight mb-2">
              Low-Fat Meat
            </h3>
            <p class="text-gray-300 text-sm mb-4">
              Started at <span class="text-white font-bold">₱365.00</span>
            </p>
          </div>

          <NuxtLink
            to="/customer?category=Meat%20%26%20Fish"
            class="relative z-10 inline-flex items-center gap-2 bg-white text-gray-900 font-semibold text-sm px-5 py-2 rounded-full w-fit hover:bg-gray-100 transition-colors"
          >
            Shop now
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>

        </div>

        <!-- Card 3: Fresh Fruit (Yellow) -->
        <div class="relative rounded-2xl overflow-hidden bg-yellow-400 p-6 flex flex-col justify-between min-h-[220px]">

          <div class="relative z-10">
            <p class="text-yellow-800 text-xs font-semibold uppercase tracking-widest mb-1">
              Summer Sale
            </p>
            <h3 class="text-gray-900 text-2xl font-black leading-tight mb-2">
              100% Fresh Fruit
            </h3>
            <p class="text-gray-800 text-sm mb-4">
              Up to
              <span class="bg-yellow-600 text-white font-bold px-2 py-0.5 rounded-md ml-1">
                64% OFF
              </span>
            </p>
          </div>

          <NuxtLink
            to="/customer?category=Fresh%20Fruit"
            class="relative z-10 inline-flex items-center gap-2 bg-gray-900 text-white font-semibold text-sm px-5 py-2 rounded-full w-fit hover:bg-gray-800 transition-colors"
          >
            Shop now
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>

        </div>

      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

// Countdown timer for "Sale of the Month"
const countdown = ref([
  { label: 'Days',  value: '00' },
  { label: 'Hours', value: '02' },
  { label: 'Mins',  value: '18' },
  { label: 'Secs',  value: '46' },
])

// Target date — 2 days from now
const targetDate = new Date()
targetDate.setDate(targetDate.getDate() + 2)

let timer: ReturnType<typeof setInterval>

const updateCountdown = () => {
  const now  = new Date()
  const diff = targetDate.getTime() - now.getTime()

  if (diff <= 0) {
    clearInterval(timer)
    return
  }

  const days  = Math.floor(diff / (1000 * 60 * 60 * 24))
  const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
  const mins  = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))
  const secs  = Math.floor((diff % (1000 * 60)) / 1000)

  countdown.value = [
    { label: 'Days',  value: String(days).padStart(2, '0') },
    { label: 'Hours', value: String(hours).padStart(2, '0') },
    { label: 'Mins',  value: String(mins).padStart(2, '0') },
    { label: 'Secs',  value: String(secs).padStart(2, '0') },
  ]
}

onMounted(() => {
  updateCountdown()
  timer = setInterval(updateCountdown, 1000)
})

onUnmounted(() => {
  clearInterval(timer)
})
</script>