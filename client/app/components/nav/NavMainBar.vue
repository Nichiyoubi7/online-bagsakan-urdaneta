<template>
  <div class="bg-white border-b border-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-4 flex items-center gap-3">

      <!-- Hamburger (mobile only) -->
      <button
        class="md:hidden flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors shrink-0"
        @click="$emit('toggle-menu')"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <!-- Logo -->
      <NuxtLink to="/" class="flex items-center shrink-0">
        <img src="/images/logo/OBRA_Logo.png" alt="OBRA" class="h-8 md:h-10 w-auto object-contain" />
        <span class="text-xl md:text-2xl font-black text-gray-800 tracking-tight">OBRA</span>
      </NuxtLink>

      <!-- Desktop Search Bar -->
      <div class="hidden md:flex flex-1 flex-col max-w-2xl relative">
        <div class="flex items-center border border-gray-200 rounded-md overflow-hidden">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search products..."
            class="flex-1 px-4 py-2.5 text-sm text-gray-700 outline-none placeholder:text-gray-400"
            @keyup.enter="handleSearch"
            @input="handleInput"
            @focus="showSuggestions = true"
            @blur="hideSuggestions"
          />
          <button
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 text-sm font-medium transition-colors"
            @click="handleSearch"
          >
            Search
          </button>
        </div>
        <!-- Desktop Suggestions -->
        <div
          v-if="showSuggestions && filteredSuggestions.length > 0"
          class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 z-50 max-h-72 overflow-y-auto"
        >
          <NuxtLink
            v-for="product in filteredSuggestions"
            :key="product.id"
            :to="`/customer/products/${product.id}`"
            class="flex items-center gap-3 px-4 py-3 hover:bg-green-50 transition-colors cursor-pointer"
            @click="selectSuggestion(product.name)"
          >
            <img :src="product.image" :alt="product.name" class="w-10 h-10 object-contain rounded-lg bg-gray-50" />
            <div>
              <p class="text-sm font-medium text-gray-800">{{ product.name }}</p>
              <p class="text-xs text-gray-400">{{ product.category?.name || product.category }} · ₱{{ Number(product.price).toFixed(2) }}</p>
            </div>
          </NuxtLink>
        </div>
      </div>

      <!-- Spacer on mobile -->
      <div class="flex-1 md:hidden" />

      <!-- Right Icons -->
      <div class="flex items-center gap-2 md:gap-3 shrink-0">

        <!-- Mobile Search Icon -->
        <button
          class="md:hidden flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
          @click="mobileSearchOpen = !mobileSearchOpen"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
          </svg>
        </button>

        <!-- Cart -->
        <!-- Cart -->
<NuxtLink v-if="authStore.isLoggedIn" to="/customer/cart" class="flex items-center gap-1.5 text-gray-700 hover:text-green-500 transition-colors">
          <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/>
            </svg>
            <span v-if="cartCount > 0" class="absolute -top-1.5 -right-1.5 bg-green-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
              {{ cartCount }}
            </span>
          </div>
          <div class="hidden md:flex flex-col leading-tight">
            <span class="text-[10px] text-gray-400">Shopping Cart</span>
            <span class="text-sm font-semibold text-gray-800">₱{{ cartTotal.toFixed(2) }}</span>
          </div>
        </NuxtLink>

        <!-- Divider (desktop only) -->
        <div class="hidden md:block w-px h-8 bg-gray-200" />

        <!-- Guest: Log In + Sign Up -->
        <template v-if="!authStore.isLoggedIn">
          <!-- Mobile -->
          <div class="flex items-center gap-1.5 md:hidden">
            <button
              @click="showAuthModal = true"
              class="px-3 py-1.5 text-xs font-semibold text-gray-700 border border-gray-300 rounded-full hover:border-green-500 hover:text-green-600 transition-all"
            >
              Log In
            </button>
            <button
              @click="showAuthModal = true"
              class="px-3 py-1.5 text-xs font-semibold text-white bg-green-500 hover:bg-green-600 rounded-full transition-all shadow-sm"
            >
              Sign Up
            </button>
          </div>
          <!-- Desktop -->
          <div class="hidden md:flex items-center gap-2">
            <button
              @click="showAuthModal = true"
              class="px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-full hover:border-green-500 hover:text-green-600 transition-all"
            >
              Log In
            </button>
            <button
              @click="showAuthModal = true"
              class="px-4 py-2 text-sm font-semibold text-white bg-green-500 hover:bg-green-600 rounded-full transition-all shadow-sm"
            >
              Sign Up
            </button>
          </div>
        </template>

        <!-- Logged In: User Dropdown -->
        <div v-else class="relative" ref="dropdownRef">
          <button
            @click="showDropdown = !showDropdown"
            class="flex items-center gap-1.5 px-2 md:px-3 py-2 rounded-full border border-gray-200 hover:border-green-400 transition-all"
          >
            <div class="w-7 h-7 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold">
              {{ authStore.user?.name?.charAt(0).toUpperCase() }}
            </div>
            <span class="hidden md:block text-sm font-semibold text-gray-700 max-w-[100px] truncate">
              {{ authStore.user?.name }}
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="hidden md:block w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <!-- Dropdown Menu -->
          <div
            v-if="showDropdown"
            class="absolute right-0 top-full mt-2 w-52 bg-white border border-gray-100 rounded-xl shadow-lg z-50 py-1 overflow-hidden"
          >
            <div class="px-4 py-3 border-b border-gray-100">
              <p class="text-xs font-semibold text-gray-800 truncate">{{ authStore.user?.name }}</p>
              <p class="text-xs text-gray-400 truncate">{{ authStore.user?.email }}</p>
            </div>
            <NuxtLink to="/customer/orders" @click="showDropdown = false"
              class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              Orders & Reordering
            </NuxtLink>
            <NuxtLink to="/customer/account" @click="showDropdown = false"
              class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Profile
            </NuxtLink>
            <button @click="handleLogout"
              class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
              Log Out
            </button>
          </div>
        </div>

      </div>
    </div>

    <!-- Mobile Search Expandable Bar -->
    <Transition name="slide-down">
      <div v-if="mobileSearchOpen" class="md:hidden px-4 pb-3 pt-2 relative">
        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden shadow-sm">
          <input
            ref="mobileSearchInput"
            v-model="searchQuery"
            type="text"
            placeholder="Search products..."
            class="flex-1 px-4 py-2.5 text-sm text-gray-700 outline-none placeholder:text-gray-400"
            @keyup.enter="handleSearch"
            @input="handleInput"
            @focus="showSuggestions = true"
            @blur="hideSuggestions"
          />
          <button
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 transition-colors"
            @click="handleSearch"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
          </button>
        </div>
        <!-- Mobile Suggestions -->
        <div
          v-if="showSuggestions && filteredSuggestions.length > 0"
          class="absolute left-4 right-4 bg-white border border-gray-200 rounded-xl shadow-lg mt-1 z-50 max-h-60 overflow-y-auto"
        >
          <NuxtLink
            v-for="product in filteredSuggestions"
            :key="product.id"
            :to="`/customer/products/${product.id}`"
            class="flex items-center gap-3 px-4 py-3 hover:bg-green-50 transition-colors"
            @click="selectSuggestion(product.name); mobileSearchOpen = false"
          >
            <img :src="product.image" :alt="product.name" class="w-9 h-9 object-contain rounded-lg bg-gray-50" />
            <div>
              <p class="text-sm font-medium text-gray-800">{{ product.name }}</p>
              <p class="text-xs text-gray-400">₱{{ Number(product.price).toFixed(2) }}</p>
            </div>
          </NuxtLink>
        </div>
      </div>
    </Transition>

    <AuthModal v-model="showAuthModal" />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick, onMounted, onBeforeUnmount } from 'vue'
import AuthModal from '~/components/auth/AuthModal.vue'

defineEmits(['toggle-menu'])

const authStore = useAuthStore()
const config = useRuntimeConfig()

const searchQuery = ref('')
const showSuggestions = ref(false)
const showDropdown = ref(false)
const { showAuthModal } = useAuthGate()
const mobileSearchOpen = ref(false)
const filteredSuggestions = ref<any[]>([])
const dropdownRef = ref<HTMLElement | null>(null)
const mobileSearchInput = ref<HTMLInputElement | null>(null)

const cartStore = useCartStore()
const cartCount = computed(() => cartStore.totalCount)
const cartTotal = computed(() => cartStore.totalPrice)
const wishlistCount = ref(0)

onMounted(() => {
  cartStore.loadFromStorage()
})

const imageMap: Record<string, string> = {
  // Vegetables
  'Tomato':        '/images/products/vegetables/Tomato.png',
  'Eggplant':      '/images/products/vegetables/eggplant.png',
  'Bitter Gourd':  '/images/products/vegetables/bitter_gourd.png',
  'Okra':          '/images/products/vegetables/okra.png',
  'Sitaw':         '/images/products/vegetables/sitaw.png',
  'Kangkong':      '/images/products/vegetables/kangkong.png',
  'Repolyo':       '/images/products/vegetables/repolyo.png',
  'Carrot':        '/images/products/vegetables/carrot.png',
  'Potato':        '/images/products/vegetables/potato.png',
  'Sibuyas':       '/images/products/vegetables/sibuyas.png',
  'Bawang':        '/images/products/vegetables/bawang.png',
  'Luya':          '/images/products/vegetables/luya.png',
  'Mais':          '/images/products/vegetables/mais.png',
  'Siling Haba':   '/images/products/vegetables/siling_haba.png',
  'Siling Labuyo': '/images/products/vegetables/siling_labuyo.png',
  'Upo':           '/images/products/vegetables/upo.png',
  'Patola':        '/images/products/vegetables/patola.png',
  'Sigarilyas':    '/images/products/vegetables/sigarilyas.png',
  'Gabi':          '/images/products/vegetables/gabi.png',
  'Kamote':        '/images/products/vegetables/kamote.png',
  'Labanos':       '/images/products/vegetables/labanos.png',
  // Fruits
  'Banana':        '/images/products/fruits/saging.png',
  'Mango':         '/images/products/fruits/mango.png',
  'Papaya':        '/images/products/fruits/papaya.png',
  'Pakwan':        '/images/products/fruits/pakwan.png',
  'Bayabas':       '/images/products/fruits/Guava.png',
  'Melon':         '/images/products/fruits/melon.png',
  'Pineapple':     '/images/products/fruits/Pineapple.png',
  'Avocado':       '/images/products/fruits/Avocado.png',
  'Guava':         '/images/products/fruits/Guava.png',
  'Rambutan':      '/images/products/fruits/Rambutan.png',
  'Lanzones':      '/images/products/fruits/Lanzones.jpg',
  'Calamansi':     '/images/products/fruits/Calamansi.png',
  'Orange':        '/images/products/fruits/Orange.png',
  'Apple':         '/images/products/fruits/Apple.png',
  'Grapes':        '/images/products/fruits/Grapes.png',
  // Meat & Fish
  'Chicken':       '/images/products/meat/Chicken.png',
  'Pork Meat':     '/images/products/meat/pork_meat.png',
  'Egg':           '/images/products/meat/Egg.png',
  'Rice':          '/images/products/meat/rice.png',
  'Bangus':        '/images/products/meat/Chicken.png',
  'Tilapia':       '/images/products/meat/Chicken.png',
  'Hipon':         '/images/products/meat/Chicken.png',
}

let debounceTimer: ReturnType<typeof setTimeout>

const handleInput = () => {
  clearTimeout(debounceTimer)
  if (!searchQuery.value.trim()) {
    filteredSuggestions.value = []
    return
  }
  debounceTimer = setTimeout(async () => {
    try {
      const res: any = await $fetch('/products', {
        baseURL: config.public.apiBase,
        params: { search: searchQuery.value, per_page: 6 },
        headers: { Accept: 'application/json' },
      })
      filteredSuggestions.value = (res.data || []).map((p: any) => ({
        ...p,
        image: imageMap[p.name] || '/images/products/placeholder.png',
      }))
    } catch {
      filteredSuggestions.value = []
    }
  }, 300)
}

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    navigateTo(`/customer?search=${encodeURIComponent(searchQuery.value)}`)
    showSuggestions.value = false
    mobileSearchOpen.value = false
  }
}

const selectSuggestion = (name: string) => {
  searchQuery.value = name
  showSuggestions.value = false
}

const hideSuggestions = () => {
  setTimeout(() => { showSuggestions.value = false }, 150)
}

const handleLogout = async () => {
  showDropdown.value = false
  await authStore.logout()
  navigateTo('/')
}

watch(mobileSearchOpen, async (val) => {
  if (val) {
    await nextTick()
    mobileSearchInput.value?.focus()
  }
})

const handleClickOutside = (e: MouseEvent) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target as Node)) {
    showDropdown.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.2s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>