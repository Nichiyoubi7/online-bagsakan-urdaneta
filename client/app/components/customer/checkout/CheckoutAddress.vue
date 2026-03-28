<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-base font-black text-gray-800">Delivery Address</h3>
      <button
        @click="showChange = !showChange"
        class="text-sm text-green-500 hover:text-green-600 font-semibold transition-colors"
      >
        Change
      </button>
    </div>

    <!-- Current Address -->
    <div class="flex items-start gap-3 mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      <div>
        <p class="text-sm font-semibold text-gray-800">{{ address.name }}</p>
        <p class="text-sm text-gray-500">{{ address.full }}</p>
        <p class="text-sm text-gray-500">{{ address.city }}</p>
      </div>
    </div>

    <!-- House/Landmark input -->
    <div>
      <input
        v-model="landmark"
        type="text"
        placeholder="House no. / my building landmark"
        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
      />
    </div>

    <!-- Contactless Delivery Toggle -->
    <div class="flex items-center justify-between mt-4">
      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span class="text-sm text-gray-600">Contactless delivery</span>
      </div>
      <button
        @click="contactless = !contactless"
        :class="[
          'relative w-11 h-6 rounded-full transition-colors duration-200',
          contactless ? 'bg-green-500' : 'bg-gray-200'
        ]"
      >
        <span
          :class="[
            'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200',
            contactless ? 'translate-x-5' : 'translate-x-0'
          ]"
        />
      </button>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const address = {
  name: 'Sunday Carolino',
  full: 'Psu Executive Vlg, 2428 Urdaneta, Philippines Psu Executive Vlg',
  city: 'Pangasinan',
}

const landmark = ref('')
const contactless = ref(true)
const showChange = ref(false)
</script>