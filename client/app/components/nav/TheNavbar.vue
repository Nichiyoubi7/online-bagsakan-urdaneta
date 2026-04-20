<template>
  <header class="sticky top-0 z-50 w-full shadow-sm">
    <NavMainBar @toggle-menu="mobileMenuOpen = !mobileMenuOpen" />
    <NavMenuBar class="hidden md:block" />

    <!-- Mobile Drawer -->
    <Transition name="drawer">
      <div v-if="mobileMenuOpen" class="fixed inset-0 z-50 md:hidden">
        <div class="absolute inset-0 bg-black/50" @click="mobileMenuOpen = false" />
        <div class="absolute top-0 left-0 h-full w-72 bg-white shadow-2xl flex flex-col">

          <!-- Header -->
          <div class="flex items-center justify-between px-4 py-4 border-b border-gray-100">
            <span class="text-xl font-black text-gray-800">OBRA</span>
            <button @click="mobileMenuOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Links -->
          <nav class="flex-1 overflow-y-auto px-3 py-4 flex flex-col gap-1">
            <NuxtLink to="/" @click="mobileMenuOpen = false"
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium transition-colors">
              🏠 Home
            </NuxtLink>
            <NuxtLink to="/customer" @click="mobileMenuOpen = false"
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium transition-colors">
              🛍️ Shop
            </NuxtLink>
            <NuxtLink to="/sellers" @click="mobileMenuOpen = false"
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium transition-colors">
              🏪 Our Sellers
            </NuxtLink>
            <NuxtLink to="/about" @click="mobileMenuOpen = false"
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium transition-colors">
              ℹ️ About Us
            </NuxtLink>
            <NuxtLink to="/contact" @click="mobileMenuOpen = false"
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium transition-colors">
              📞 Contact Us
            </NuxtLink>

            <div class="mt-4 border-t border-gray-100 pt-4 flex flex-col gap-1">
              <NuxtLink to="/customer/cart" @click="mobileMenuOpen = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-600 font-medium transition-colors">
                🛒 Cart
              </NuxtLink>
            </div>

            <div class="mt-4 border-t border-gray-100 pt-4 flex flex-col gap-2">
              <NuxtLink to="/auth/register?role=seller" @click="mobileMenuOpen = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 text-green-700 font-semibold text-sm hover:bg-green-100 transition-colors">
                🏪 Sign Up as Seller
              </NuxtLink>
              <NuxtLink to="/auth/register?role=driver" @click="mobileMenuOpen = false"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 text-green-700 font-semibold text-sm hover:bg-green-100 transition-colors">
                🛵 Sign Up as Driver
              </NuxtLink>
            </div>
          </nav>

          <div class="px-4 py-4 border-t border-gray-100 text-sm text-gray-400 flex items-center gap-2">
            📞 +63 966 081 8437
          </div>
        </div>
      </div>
    </Transition>

    <AuthModal v-model="showAuthModal" />
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import NavMainBar from '~/components/nav/NavMainBar.vue'
import NavMenuBar from '~/components/nav/NavMenuBar.vue'
import AuthModal from '~/components/auth/AuthModal.vue'

const showAuthModal = ref(false)
const mobileMenuOpen = ref(false)
</script>

<style scoped>
.drawer-enter-active, .drawer-leave-active { transition: opacity 0.25s ease; }
.drawer-enter-from, .drawer-leave-to { opacity: 0; }
</style>