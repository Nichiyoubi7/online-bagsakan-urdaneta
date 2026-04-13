<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">Shopping Cart</span>
        </div>
        <h1 class="text-white text-2xl font-black mt-1">Shopping Cart</h1>
      </div>
    </div>

    <!-- Cart Content -->
    <div class="max-w-7xl mx-auto px-4 py-6 pb-40">

      <!-- Column Headers (desktop only) -->
      <div class="hidden md:flex items-center gap-4 px-5 py-3 bg-white rounded-xl border border-gray-100 shadow-sm mb-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
        <div class="w-4"></div>
        <div class="w-20"></div>
        <div class="flex-1">Product</div>
        <div class="w-24 text-center">Unit Price</div>
        <div class="w-32 text-center">Quantity</div>
        <div class="w-24 text-center">Total Price</div>
        <div class="w-16 text-center">Remove</div>
      </div>

      <!-- Empty Cart -->
      <div
        v-if="cartStore.items.length === 0"
        class="flex flex-col items-center justify-center py-24 text-center"
      >
        <span class="text-6xl mb-4">🛒</span>
        <h3 class="text-lg font-bold text-gray-700 mb-2">Your cart is empty</h3>
        <p class="text-sm text-gray-400 mb-6">Add some products to your cart first!</p>
        <NuxtLink
          to="/customer"
          class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full transition-colors"
        >
          Start Shopping
        </NuxtLink>
      </div>

      <!-- Cart Groups -->
      <div v-else>
        <div
          v-for="group in cartStore.groupedBySeller"
          :key="group.id"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-4 overflow-hidden"
        >
          <!-- Seller Header -->
          <div class="flex items-center gap-3 px-4 md:px-5 py-3 border-b border-gray-100 bg-gray-50">
            <input
              type="checkbox"
              :checked="group.items.every(i => i.selected)"
              @change="toggleSellerAll(group.id)"
              class="w-4 h-4 accent-green-500 cursor-pointer"
            />
            <span class="text-lg">🏪</span>
            <span class="text-sm font-bold text-gray-800">{{ group.name }}</span>
            <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-medium">Official Store</span>
          </div>

          <!-- Items -->
          <div class="divide-y divide-gray-50">
            <div
              v-for="item in group.items"
              :key="item.id"
              class="flex items-center gap-3 md:gap-4 px-4 md:px-5 py-4"
            >
              <!-- Checkbox -->
              <input
                type="checkbox"
                :checked="item.selected"
                @change="cartStore.toggleSelected(item.id)"
                class="w-4 h-4 accent-green-500 cursor-pointer shrink-0"
              />

              <!-- Image -->
              <div class="w-14 h-14 md:w-20 md:h-20 bg-gray-50 rounded-xl overflow-hidden shrink-0">
                <img :src="item.image" :alt="item.name" class="w-full h-full object-contain p-1" />
              </div>

              <!-- Name + Category -->
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ item.name }}</p>
                <p class="text-xs text-gray-400">{{ item.category }}</p>
                <!-- Mobile: price shown here -->
                <p class="text-sm font-bold text-green-600 md:hidden mt-1">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
              </div>

              <!-- Unit Price (desktop) -->
              <div class="hidden md:block w-24 text-center">
                <p class="text-sm font-semibold text-gray-800">₱{{ item.price.toFixed(2) }}</p>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden shrink-0">
                <button
                  @click="cartStore.decreaseQty(item.id)"
                  class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors font-bold text-sm"
                >−</button>
                <span class="w-7 md:w-8 text-center text-sm font-semibold text-gray-800">{{ item.quantity }}</span>
                <button
                  @click="cartStore.increaseQty(item.id)"
                  class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors font-bold text-sm"
                >+</button>
              </div>

              <!-- Total Price (desktop) -->
              <div class="hidden md:block w-24 text-center">
                <p class="text-sm font-bold text-gray-800">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
              </div>

              <!-- Remove -->
              <button
                @click="cartStore.removeItem(item.id)"
                class="w-7 h-7 md:w-8 md:h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors shrink-0"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Summary Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-30">
      <div class="max-w-7xl mx-auto px-4 py-3">

        <!-- Mobile Layout -->
        <div class="flex flex-col gap-2 md:hidden">
          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
              <input
                type="checkbox"
                :checked="cartStore.allSelected"
                @change="cartStore.toggleAll(!cartStore.allSelected)"
                class="w-4 h-4 accent-green-500"
              />
              <span class="text-sm font-medium text-gray-700">All ({{ cartStore.items.length }})</span>
            </label>
            <button @click="cartStore.deleteSelected()" class="text-sm text-red-500 font-medium">Delete Selected</button>
          </div>
          <div class="flex items-center justify-between">
            <div>
              <span class="text-xs text-gray-500">Total ({{ cartStore.selectedCount }} items):</span>
              <span class="text-lg font-black text-green-600 ml-1">₱{{ cartStore.selectedTotal.toFixed(2) }}</span>
            </div>
            <NuxtLink
              to="/customer/checkout"
              :class="[
                'bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors text-sm',
                cartStore.selectedCount === 0 ? 'opacity-50 pointer-events-none' : ''
              ]"
            >
              Check Out ({{ cartStore.selectedCount }})
            </NuxtLink>
          </div>
        </div>

        <!-- Desktop Layout -->
        <div class="hidden md:flex items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input
                type="checkbox"
                :checked="cartStore.allSelected"
                @change="cartStore.toggleAll(!cartStore.allSelected)"
                class="w-4 h-4 accent-green-500"
              />
              <span class="text-sm font-medium text-gray-700">Select All ({{ cartStore.items.length }})</span>
            </label>
            <button @click="cartStore.deleteSelected()" class="text-sm text-gray-500 hover:text-red-500 transition-colors">
              Delete Selected
            </button>
          </div>
          <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">Total ({{ cartStore.selectedCount }} items):</span>
              <span class="text-xl font-black text-green-600">₱{{ cartStore.selectedTotal.toFixed(2) }}</span>
            </div>
            <NuxtLink
              to="/customer/checkout"
              :class="[
                'bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-3 rounded-xl transition-colors shadow-md',
                cartStore.selectedCount === 0 ? 'opacity-50 pointer-events-none' : ''
              ]"
            >
              Check Out
            </NuxtLink>
          </div>
        </div>

      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import GuestLayout from '../../components/layout/GuestLayout.vue'

const cartStore = useCartStore()

onMounted(() => {
  cartStore.loadFromStorage()
})

const toggleSellerAll = (sellerId: number) => {
  const group = cartStore.groupedBySeller.find(g => g.id === sellerId)
  if (!group) return
  const allSel = group.items.every(i => i.selected)
  group.items.forEach(i => cartStore.toggleSelected(i.id))
  // Force sync if needed
  if (!allSel) {
    group.items.forEach(i => { if (!i.selected) cartStore.toggleSelected(i.id) })
  }
}
</script>