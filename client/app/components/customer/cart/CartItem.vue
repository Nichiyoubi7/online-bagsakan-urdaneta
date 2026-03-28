<template>
  <div class="flex items-center gap-4 py-4 border-b border-gray-100 last:border-0">

    <!-- Checkbox -->
    <input
      type="checkbox"
      :checked="item.selected"
      @change="$emit('toggle', item.id)"
      class="w-4 h-4 accent-green-500 cursor-pointer shrink-0"
    />

    <!-- Product Image -->
    <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden shrink-0">
      <img
        :src="item.image"
        :alt="item.name"
        class="w-full h-full object-contain p-2"
      />
    </div>

    <!-- Product Info -->
    <div class="flex-1 min-w-0">
      <h4 class="text-sm font-semibold text-gray-800 truncate mb-1">{{ item.name }}</h4>
      <p class="text-xs text-gray-400 mb-1">Variation: {{ item.variation }}</p>
      <div class="flex items-center gap-2">
        <span class="text-xs bg-red-100 text-red-500 font-semibold px-2 py-0.5 rounded-full">Sale</span>
        <span class="text-xs bg-green-100 text-green-600 font-semibold px-2 py-0.5 rounded-full">Local</span>
      </div>
    </div>

    <!-- Unit Price -->
    <div class="text-sm text-gray-600 w-24 text-center shrink-0">
      ₱{{ item.price.toFixed(2) }}
    </div>

    <!-- Quantity Controls -->
    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shrink-0">
      <button
        @click="$emit('decrease', item.id)"
        class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 transition-colors font-bold text-sm"
      >
        −
      </button>
      <span class="px-3 py-1.5 text-sm font-semibold text-gray-800 min-w-[32px] text-center border-x border-gray-200">
        {{ item.quantity }}
      </span>
      <button
        @click="$emit('increase', item.id)"
        class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 transition-colors font-bold text-sm"
      >
        +
      </button>
    </div>

    <!-- Total Price -->
    <div class="text-sm font-bold text-green-600 w-24 text-center shrink-0">
      ₱{{ (item.price * item.quantity).toFixed(2) }}
    </div>

    <!-- Actions -->
    <div class="flex flex-col gap-1 shrink-0 text-right">
      <button
        @click="$emit('remove', item.id)"
        class="text-xs text-gray-500 hover:text-red-500 transition-colors font-medium"
      >
        Delete
      </button>
      <button class="text-xs text-green-500 hover:text-green-600 transition-colors">
        Find Similar ▾
      </button>
    </div>

  </div>
</template>

<script setup lang="ts">
defineProps<{
  item: {
    id: number
    name: string
    image: string
    price: number
    quantity: number
    variation: string
    selected: boolean
  }
}>()

defineEmits<{
  (e: 'toggle', id: number): void
  (e: 'increase', id: number): void
  (e: 'decrease', id: number): void
  (e: 'remove', id: number): void
}>()
</script>