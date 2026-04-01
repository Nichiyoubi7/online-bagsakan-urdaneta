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
        v-if="cartGroups.length === 0"
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
      <CartSellerGroup
        v-for="group in cartGroups"
        :key="group.id"
        :seller="group"
        @toggle-all="toggleSellerAll(group.id)"
        @toggle-item="toggleItem"
        @increase="increaseQty"
        @decrease="decreaseQty"
        @remove="removeItem"
      />

    </div>

    <!-- Bottom Summary Bar -->
    <CartSummary
      :all-selected="allSelected"
      :total-items="totalItems"
      :selected-items="selectedItemsCount"
      :total="selectedTotal"
      @toggle-all="toggleAll"
      @delete-selected="deleteSelected"
    />

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import GuestLayout from '../../components/layout/GuestLayout.vue'
import CartSellerGroup from '../../components/customer/cart/CartSellerGroup.vue'
import CartSummary from '../../components/customer/cart/CartSummary.vue'

const cartGroups = ref([
  {
    id: 1,
    name: "Mang Bert's Wet Market",
    items: [
      { id: 1, name: 'Tomato',   image: '/images/products/vegetables/Tomato.png',   price: 20.00, quantity: 1, variation: '500g', selected: true  },
      { id: 2, name: 'Eggplant', image: '/images/products/vegetables/eggplant.png', price: 15.00, quantity: 2, variation: '500g', selected: true  },
      { id: 3, name: 'Kangkong', image: '/images/products/vegetables/kangkong.png', price: 10.00, quantity: 1, variation: '250g', selected: false },
    ],
  },
  {
    id: 2,
    name: "Aling Nena's Fish Hub",
    items: [
      { id: 4, name: 'Chicken',  image: '/images/products/meat/Chicken.png',   price: 180.00, quantity: 1, variation: '1kg', selected: true  },
      { id: 5, name: 'Pork Meat',image: '/images/products/meat/pork_meat.png', price: 220.00, quantity: 1, variation: '1kg', selected: false },
    ],
  },
])

const allItems       = computed(() => cartGroups.value.flatMap(g => g.items))
const totalItems     = computed(() => allItems.value.length)
const allSelected    = computed(() => allItems.value.every(i => i.selected))
const selectedItemsCount = computed(() => allItems.value.filter(i => i.selected).length)
const selectedTotal  = computed(() =>
  allItems.value.filter(i => i.selected).reduce((sum, i) => sum + i.price * i.quantity, 0)
)

const toggleItem = (itemId: number) => {
  cartGroups.value.forEach(g => {
    const item = g.items.find(i => i.id === itemId)
    if (item) item.selected = !item.selected
  })
}

const toggleSellerAll = (sellerId: number) => {
  const group = cartGroups.value.find(g => g.id === sellerId)
  if (!group) return
  const allSel = group.items.every(i => i.selected)
  group.items.forEach(i => i.selected = !allSel)
}

const toggleAll = () => {
  const allSel = allSelected.value
  cartGroups.value.forEach(g => g.items.forEach(i => i.selected = !allSel))
}

const increaseQty = (itemId: number) => {
  cartGroups.value.forEach(g => {
    const item = g.items.find(i => i.id === itemId)
    if (item) item.quantity++
  })
}

const decreaseQty = (itemId: number) => {
  cartGroups.value.forEach(g => {
    const item = g.items.find(i => i.id === itemId)
    if (item && item.quantity > 1) item.quantity--
  })
}

const removeItem = (itemId: number) => {
  cartGroups.value.forEach(g => {
    g.items = g.items.filter(i => i.id !== itemId)
  })
  cartGroups.value = cartGroups.value.filter(g => g.items.length > 0)
}

const deleteSelected = () => {
  cartGroups.value.forEach(g => {
    g.items = g.items.filter(i => !i.selected)
  })
  cartGroups.value = cartGroups.value.filter(g => g.items.length > 0)
}
</script>