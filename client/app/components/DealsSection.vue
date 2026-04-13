<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- Card 1: Sale of the Month -->
        <div class="relative rounded-2xl overflow-hidden flex flex-col justify-between min-h-[380px]">
          <img
            src="/images/banners/2.png"
            alt=""
            class="absolute inset-0 w-full h-full object-cover object-center"
          />
          <!-- Gradient overlay for text readability only -->
          <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/20 to-black/50" />

          <div class="relative z-10 p-6">
            <p class="text-gray-200 text-xs font-semibold uppercase tracking-widest mb-1">
              Best Deals
            </p>
            <h3 class="text-white text-2xl font-black leading-tight mb-4 drop-shadow-lg">
              Sale of the <br /> Month
            </h3>
            <div class="flex items-center gap-2 mb-4">
              <div v-for="unit in countdown" :key="unit.label" class="flex flex-col items-center">
                <span class="bg-black/40 text-white font-black text-sm w-10 h-10 flex items-center justify-center rounded-lg border border-white/30 backdrop-blur-sm">
                  {{ unit.value }}
                </span>
                <span class="text-gray-200 text-[10px] mt-1 uppercase">{{ unit.label }}</span>
              </div>
            </div>
          </div>

          <div class="relative z-10 p-6">
            <NuxtLink
              to="/customer"
              class="inline-flex items-center gap-2 bg-white text-gray-900 font-semibold text-sm px-5 py-2.5 rounded-full w-fit hover:bg-gray-100 transition-colors"
            >
              Shop Now →
            </NuxtLink>
          </div>
        </div>

        <!-- Card 2: Low-Fat Meat -->
        <div class="relative rounded-2xl overflow-hidden flex flex-col justify-between min-h-[380px]">
          <img
            src="/images/banners/4.png"
            alt=""
            class="absolute inset-0 w-full h-full object-cover object-center"
          />

          <div class="relative z-10 p-6">
            <p class="text-gray-300 text-xs font-semibold uppercase tracking-widest mb-1">
              85% Fat Free
            </p>
            <h3 class="text-white text-2xl font-black leading-tight mb-2 drop-shadow-lg">
              Low-Fat Meat
            </h3>
            <p class="text-gray-300 text-sm">
              Started at <span class="text-white font-bold">₱365.00</span>
            </p>
          </div>

          <div class="relative z-10 p-6">
            <NuxtLink
              to="/customer?category=Meat%20%26%20Fish"
              class="inline-flex items-center gap-2 bg-white text-gray-900 font-semibold text-sm px-5 py-2.5 rounded-full w-fit hover:bg-gray-100 transition-colors"
            >
              Shop Now →
            </NuxtLink>
          </div>
        </div>

        <!-- Card 3: Fresh Fruit -->
        <div class="relative rounded-2xl overflow-hidden flex flex-col justify-between min-h-[380px]">
          <img
            src="/images/banners/3.png"
            alt=""
            class="absolute inset-0 w-full h-full object-cover object-center"
          />

          <div class="relative z-10 p-6">
            <p class="text-yellow-900 text-xs font-semibold uppercase tracking-widest mb-1">
              Summer Sale
            </p>
            <h3 class="text-gray-900 text-2xl font-black leading-tight mb-2 drop-shadow">
              100% Fresh Fruit
            </h3>
            <p class="text-gray-900 text-sm">
              Up to
              <span class="bg-yellow-700 text-white font-bold px-2 py-0.5 rounded-md ml-1">
                64% OFF
              </span>
            </p>
          </div>

          <div class="relative z-10 p-6">
            <NuxtLink
              to="/customer?category=Fruits"
              class="inline-flex items-center gap-2 bg-gray-900 text-white font-semibold text-sm px-5 py-2.5 rounded-full w-fit hover:bg-gray-800 transition-colors"
            >
              Shop Now →
            </NuxtLink>
          </div>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const countdown = ref([
  { label: 'Days',  value: '00' },
  { label: 'Hours', value: '00' },
  { label: 'Mins',  value: '00' },
  { label: 'Secs',  value: '00' },
])

const targetDate = new Date()
targetDate.setDate(targetDate.getDate() + 2)

let timer: ReturnType<typeof setInterval>

const updateCountdown = () => {
  const now  = new Date()
  const diff = targetDate.getTime() - now.getTime()
  if (diff <= 0) { clearInterval(timer); return }
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

onMounted(() => { updateCountdown(); timer = setInterval(updateCountdown, 1000) })
onUnmounted(() => clearInterval(timer))
</script> 