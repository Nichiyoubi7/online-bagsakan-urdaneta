<template>
  <div class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">

      <!-- Nav Links -->
      <nav class="flex items-center">
        <template v-for="item in navLinks" :key="item.label">

          <!-- Link with dropdown -->
          <div v-if="item.children" class="relative group">
            <button class="flex items-center gap-1 px-4 py-3.5 text-sm font-medium hover:text-green-400 transition-colors">
              {{ item.label }}
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <!-- Dropdown -->
            <div class="absolute top-full left-0 bg-white text-gray-800 shadow-lg rounded-b-md min-w-[160px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
              <NuxtLink
                v-for="child in item.children"
                :key="child.label"
                :to="child.to"
                class="block px-4 py-2.5 text-sm hover:bg-green-50 hover:text-green-600 transition-colors"
              >
                {{ child.label }}
              </NuxtLink>
            </div>
          </div>

          <!-- Simple link -->
          <NuxtLink
            v-else
            :to="item.to"
            class="px-4 py-3.5 text-sm font-medium hover:text-green-400 transition-colors"
            active-class="text-green-400"
          >
            {{ item.label }}
          </NuxtLink>

        </template>
      </nav>

      <!-- Phone Number -->
      <div class="flex items-center gap-2 text-sm text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
        </svg>
        <span>(234) 555-0104</span>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
const navLinks = [
  {
    label: "Home", to: "/",
    children: [
      { label: "Homepage", to: "/" },
      { label: "About Us", to: "/about" },
    ],
  },
  {
    label: "Pages", to: "/pages",
    children: [
      { label: "Our Sellers", to: "/sellers" },
      { label: "How It Works", to: "/how-it-works" },
    ],
  },
  { label: "Blog",       to: "/blog" },
  { label: "About Us",   to: "/about" },
  { label: "Contact Us", to: "/contact" },
]
</script>