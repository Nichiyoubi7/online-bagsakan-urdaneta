<template>
  <div
    :class="[
      'bg-white rounded-2xl border shadow-sm mx-4 mb-3 overflow-hidden transition-all duration-200',
      isNew ? 'border-green-400 shadow-green-100' : 'border-gray-100'
    ]"
  >

    <!-- New Badge -->
    <div v-if="isNew" class="bg-green-500 px-4 py-1.5 flex items-center gap-2">
      <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
      <span class="text-white text-xs font-bold">New Delivery Request!</span>
    </div>

    <!-- Card Content -->
    <div class="p-4">

      <!-- Header: Order ID + Time -->
      <div class="flex items-center justify-between mb-3">
        <span class="text-sm font-black text-gray-800">Order #{{ delivery.id }}</span>
        <span class="text-xs text-gray-400">{{ delivery.time }}</span>
      </div>

      <!-- Route: Pickup → Dropoff -->
      <div class="flex flex-col gap-2 mb-4">

        <!-- Pickup -->
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
            <span class="text-sm">🏪</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pick Up</p>
            <p class="text-sm font-semibold text-gray-800 truncate">{{ delivery.seller }}</p>
            <p class="text-xs text-gray-400 truncate">{{ delivery.sellerAddress }}</p>
          </div>
        </div>

        <!-- Dashed Line -->
        <div class="ml-4 w-0.5 h-4 bg-dashed border-l-2 border-dashed border-gray-200"></div>

        <!-- Dropoff -->
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
            <span class="text-sm">📍</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Drop Off</p>
            <p class="text-sm font-semibold text-gray-800 truncate">{{ delivery.customer }}</p>
            <p class="text-xs text-gray-400 truncate">{{ delivery.customerAddress }}</p>
          </div>
        </div>

      </div>

      <!-- Order Details -->
      <div class="flex items-center gap-3 mb-4 bg-gray-50 rounded-xl px-3 py-2">
        <div class="flex-1">
          <p class="text-xs text-gray-400">Items</p>
          <p class="text-sm font-semibold text-gray-800">{{ delivery.items }} items</p>
        </div>
        <div class="w-px h-8 bg-gray-200"></div>
        <div class="flex-1">
          <p class="text-xs text-gray-400">Distance</p>
          <p class="text-sm font-semibold text-gray-800">{{ delivery.distance }}</p>
        </div>
        <div class="w-px h-8 bg-gray-200"></div>
        <div class="flex-1">
          <p class="text-xs text-gray-400">Earnings</p>
          <p class="text-sm font-bold text-green-600">₱{{ delivery.earnings }}</p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div v-if="delivery.status === 'pending'" class="flex gap-3">
        <button
          @click="$emit('reject', delivery.id)"
          class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors"
        >
          Decline
        </button>
        <button
          @click="$emit('accept', delivery.id)"
          class="flex-2 flex-1 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-bold transition-colors"
        >
          Accept Delivery
        </button>
      </div>

      <!-- Active Delivery Actions -->
      <div v-else-if="delivery.status === 'accepted'" class="flex gap-3">
        <button
          @click="$emit('pickedUp', delivery.id)"
          class="w-full py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold transition-colors"
        >
          Picked Up 📦
        </button>
      </div>

      <div v-else-if="delivery.status === 'picked_up'" class="flex gap-3">
        <button
          @click="$emit('delivered', delivery.id)"
          class="w-full py-2.5 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-bold transition-colors"
        >
          Mark as Delivered ✅
        </button>
      </div>

      <!-- Delivered -->
      <div v-else-if="delivery.status === 'delivered'" class="text-center py-2">
        <span class="text-sm font-semibold text-green-600">✅ Delivered Successfully!</span>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  delivery: {
    id: string
    time: string
    seller: string
    sellerAddress: string
    customer: string
    customerAddress: string
    items: number
    distance: string
    earnings: number
    status: string
  }
  isNew?: boolean
}>()

defineEmits<{
  (e: 'accept', id: string): void
  (e: 'reject', id: string): void
  (e: 'pickedUp', id: string): void
  (e: 'delivered', id: string): void
}>()
</script>