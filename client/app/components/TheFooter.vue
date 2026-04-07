<template>
  <footer class="bg-gray-900 text-white pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4">

      <!-- Top Grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 mb-10">

        <!-- Col 1: Brand -->
        <div class="col-span-2 sm:col-span-3 lg:col-span-1">
          <div class="flex items-center gap-2 mb-3">
            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center font-black text-white text-sm">O</div>
            <span class="text-lg font-black text-white">OBRA</span>
          </div>
          <p class="text-xs text-gray-400 leading-relaxed mb-4">
            Online Bagsakan Urdaneta — Fresh local products delivered to your door in Urdaneta City, Pangasinan.
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-400">
            <span>📞</span>
            <span>+63 966 081 8437</span>
          </div>
        </div>

        <!-- Col 2: Account -->
        <div>
          <h4 class="text-sm font-bold text-white mb-4">Account</h4>
          <ul class="flex flex-col gap-2.5">
            <li v-for="link in accountLinks" :key="link.label">
              <NuxtLink :to="link.to" class="text-xs text-gray-400 hover:text-green-400 transition-colors">
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Col 3: Help -->
        <div>
          <h4 class="text-sm font-bold text-white mb-4">Help</h4>
          <ul class="flex flex-col gap-2.5">
            <li v-for="link in helpsLinks" :key="link.label">
              <NuxtLink :to="link.to" class="text-xs text-gray-400 hover:text-green-400 transition-colors">
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Col 4: Quick Links -->
        <div>
          <h4 class="text-sm font-bold text-white mb-4">Quick Links</h4>
          <ul class="flex flex-col gap-2.5">
            <li v-for="link in proxyLinks" :key="link.label">
              <NuxtLink :to="link.to" class="text-xs text-gray-400 hover:text-green-400 transition-colors">
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Col 5: Categories -->
        <div>
          <h4 class="text-sm font-bold text-white mb-4">Categories</h4>
          <ul class="flex flex-col gap-2.5">
            <li v-for="link in categoryLinks" :key="link.label">
              <NuxtLink :to="link.to" class="text-xs text-gray-400 hover:text-green-400 transition-colors">
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-white/10 pt-4 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p class="text-xs text-gray-500 text-center sm:text-left">
          © {{ new Date().getFullYear() }} Online Bagsakan Urdaneta. All rights reserved.
        </p>
        <div class="flex items-center gap-4">
          <NuxtLink to="/privacy" class="text-xs text-gray-500 hover:text-green-400 transition-colors">Privacy Policy</NuxtLink>
          <NuxtLink to="/terms" class="text-xs text-gray-500 hover:text-green-400 transition-colors">Terms & Conditions</NuxtLink>
        </div>
      </div>

    </div>
  </footer>
</template>

<script setup lang="ts">
const accountLinks = [
  { label: 'My Account',    to: '/customer/profile' },
  { label: 'Order History', to: '/customer/orders' },
  { label: 'Shopping Cart', to: '/customer/cart' },
]
const helpsLinks = [
  { label: 'Contact',           to: '/contact' },
  { label: 'FAQs',              to: '/faqs' },
  { label: 'Terms & Condition', to: '/terms' },
  { label: 'Privacy Policy',    to: '/privacy' },
]
const proxyLinks = [
  { label: 'About',       to: '/about' },
  { label: 'Shop',        to: '/customer' },
  { label: 'Products',    to: '/customer' },
  { label: 'Track Order', to: '/track' },
]
const categoryLinks = [
  { label: 'Fruit & Vegetables', to: '/customer?category=Vegetables' },
  { label: 'Meat & Fish',        to: '/customer?category=Meat%20%26%20Fish' },
  { label: 'Fresh Fruits',       to: '/customer?category=Fruits' },
]
</script>