<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">Shopping Cart</span>
        </div>
        <h1 class="text-white text-2xl font-black mt-1">Shopping Cart</h1>
      </div>
    </div>

    <!-- Cart Content -->
    <div class="max-w-7xl mx-auto px-6 py-8 pb-32">

      <!-- Column Headers -->
      <div class="hidden md:flex items-center gap-4 px-5 py-3 bg-white rounded-xl border border-gray-100 shadow-sm mb-4 text-xs font-semibold text-gray-500 uppercase tracking-wide">
        <div class="w-4"></div>
        <div class="w-20"></div>
        <div class="flex-1">Product</div>
        <div class="w-24 text-center">Unit Price</div>
        <div class="w-32 text-center">Quantity</div>
        <div class="w-24 text-center">Total Price</div>
        <div class="w-24 text-center">Actions</div>
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

      <!-- Cart Items -->
      <div v-else>
        <div
          v-for="item in cartStore.items"
          :key="item.id"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-3 px-5 py-4 flex items-center gap-4"
        >
          <!-- Checkbox -->
          <input
            type="checkbox"
            v-model="selectedIds"
            :value="item.id"
            class="w-4 h-4 accent-green-500 cursor-pointer shrink-0"
          />

          <!-- Image -->
          <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden shrink-0">
            <img :src="item.image" :alt="item.name" class="w-full h-full object-contain p-1" />
          </div>

          <!-- Name -->
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800 truncate">{{ item.name }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ item.category }}</p>
          </div>

          <!-- Unit Price -->
          <div class="w-24 text-center">
            <p class="text-sm font-semibold text-gray-700">₱{{ item.price.toFixed(2) }}</p>
          </div>

          <!-- Quantity -->
          <div class="w-32 flex items-center justify-center">
            <div class="flex items-center border border-gray-200 rounded-full overflow-hidden">
              <button
                @click="cartStore.decreaseQty(item.id)"
                class="px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-sm"
              >-</button>
              <span class="px-3 py-1.5 text-sm font-semibold text-gray-800 min-w-[32px] text-center">
                {{ item.quantity }}
              </span>
              <button
                @click="cartStore.increaseQty(item.id)"
                class="px-3 py-1.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-sm"
              >+</button>
            </div>
          </div>

          <!-- Total Price -->
          <div class="w-24 text-center">
            <p class="text-sm font-bold text-green-600">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
          </div>

          <!-- Remove -->
          <div class="w-24 text-center">
            <button
              @click="cartStore.removeItem(item.id)"
              class="text-red-400 hover:text-red-600 transition-colors text-xs font-semibold"
            >
              Remove
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- Bottom Summary Bar -->
    <div v-if="cartStore.items.length > 0" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-30">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">

        <!-- Left: Select All + Delete -->
        <div class="flex items-center gap-4">
          <label class="flex items-center gap-2 cursor-pointer">
            <input
              type="checkbox"
              :checked="allSelected"
              @change="toggleAll"
              class="w-4 h-4 accent-green-500"
            />
            <span class="text-sm font-medium text-gray-700">
              Select All ({{ cartStore.items.length }})
            </span>
          </label>
          <button
            @click="deleteSelected"
            class="text-sm text-gray-500 hover:text-red-500 transition-colors"
          >
            Delete
          </button>
        </div>

        <!-- Right: Total + Checkout -->
        <div class="flex items-center gap-6">
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">
              Total ({{ selectedIds.length }} item{{ selectedIds.length !== 1 ? 's' : '' }}):
            </span>
            <span class="text-xl font-black text-green-600">
              ₱{{ selectedTotal.toFixed(2) }}
            </span>
          </div>

          <NuxtLink
            to="/customer/checkout"
            :class="[
              'bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-3 rounded-xl transition-colors shadow-md',
              selectedIds.length === 0 ? 'opacity-50 pointer-events-none' : ''
            ]"
          >
            Check Out
          </NuxtLink>
        </div>
      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import GuestLayout from '../../components/layout/GuestLayout.vue'

const cartStore = useCartStore()

const selectedIds = ref<number[]>(cartStore.items.map(i => i.id))

// Keep selectedIds in sync when items change
watch(() => cartStore.items, (newItems) => {
  selectedIds.value = selectedIds.value.filter(id => newItems.some(i => i.id === id))
  newItems.forEach(item => {
    if (!selectedIds.value.includes(item.id)) {
      selectedIds.value.push(item.id)
    }
  })
}, { deep: true })

const allSelected = computed(() =>
  cartStore.items.length > 0 && cartStore.items.every(i => selectedIds.value.includes(i.id))
)

const selectedTotal = computed(() =>
  cartStore.items
    .filter(i => selectedIds.value.includes(i.id))
    .reduce((sum, i) => sum + i.price * i.quantity, 0)
)

const toggleAll = () => {
  if (allSelected.value) {
    selectedIds.value = []
  } else {
    selectedIds.value = cartStore.items.map(i => i.id)
  }
}

const deleteSelected = () => {
  selectedIds.value.forEach(id => cartStore.removeItem(id))
  selectedIds.value = []
}

onMounted(() => {
  cartStore.loadFromStorage()
  selectedIds.value = cartStore.items.map(i => i.id)
})
</script>