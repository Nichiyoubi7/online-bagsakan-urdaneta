<template>
  <footer class="bg-[#1a2e1a] text-white">

    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-6 py-14">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-10">

        <!-- Col 1: Brand -->
        <div class="md:col-span-1">
          <NuxtLink to="/" class="inline-block mb-4">
            <span class="text-2xl font-black text-white tracking-tight">OBRA</span>
          </NuxtLink>
          <p class="text-gray-400 text-xs leading-relaxed mb-5">
            Morbi cursus porttitor enim lobortis malesuada. Duis gravida turpis at augue iaculis, interdum quam condimentum.
          </p>
          <!-- Contact -->
          <div class="flex flex-col gap-2">
            <a href="tel:+639444094457" class="flex items-center gap-2 text-xs text-gray-400 hover:text-green-400 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              +639444094457
            </a>
            <a href="mailto:proxy@gmail.com" class="flex items-center gap-2 text-xs text-gray-400 hover:text-green-400 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              proxy@gmail.com
            </a>
          </div>
        </div>

        <!-- Col 2: My Account -->
        <div>
          <h4 class="text-sm font-bold text-white mb-4">My Account</h4>
          <ul class="flex flex-col gap-2.5">
            <li v-for="link in accountLinks" :key="link.label">
              <NuxtLink
                :to="link.to"
                class="text-xs text-gray-400 hover:text-green-400 transition-colors"
              >
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Col 3: Helps -->
        <div>
          <h4 class="text-sm font-bold text-white mb-4">Helps</h4>
          <ul class="flex flex-col gap-2.5">
            <li v-for="link in helpsLinks" :key="link.label">
              <NuxtLink
                :to="link.to"
                class="text-xs text-gray-400 hover:text-green-400 transition-colors"
              >
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Col 4: Proxy -->
        <div>
          <h4 class="text-sm font-bold text-white mb-4">Proxy</h4>
          <ul class="flex flex-col gap-2.5">
            <li v-for="link in proxyLinks" :key="link.label">
              <NuxtLink
                :to="link.to"
                class="text-xs text-gray-400 hover:text-green-400 transition-colors"
              >
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
              <NuxtLink
                :to="link.to"
                class="text-xs text-gray-400 hover:text-green-400 transition-colors"
              >
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-white/10">
      <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p class="text-xs text-gray-500">
          © {{ new Date().getFullYear() }} Online Bagsakan Urdaneta. All rights reserved.
        </p>
        <div class="flex items-center gap-4">
          <NuxtLink to="/privacy" class="text-xs text-gray-500 hover:text-green-400 transition-colors">
            Privacy Policy
          </NuxtLink>
          <NuxtLink to="/terms" class="text-xs text-gray-500 hover:text-green-400 transition-colors">
            Terms & Conditions
          </NuxtLink>
        </div>
      </div>
    </div>

  </footer>
</template>

<script setup lang="ts">
// TheFooter.vue
// Main footer for all public pages

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
  { label: 'About',        to: '/about' },
  { label: 'Shop',         to: '/customer' },
  { label: 'Product',      to: '/customer' },
  { label: 'Track Order',  to: '/track' },
]

const categoryLinks = [
  { label: 'Fruit & Vegetables', to: '/customer?category=Fresh%20Fruit' },
  { label: 'Meat & Fish',        to: '/customer?category=Meat%20%26%20Fish' },
  { label: 'Bread & Bakery',     to: '/customer?category=Bread%20%26%20Bakery' },
  { label: 'Beauty & Health',    to: '/customer?category=Beauty%20%26%20Health' },
]
</script>