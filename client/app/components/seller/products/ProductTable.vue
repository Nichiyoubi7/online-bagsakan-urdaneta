<template>
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <!-- Table Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
      <h3 class="text-base font-black text-gray-800">My Products</h3>
      <div class="flex items-center gap-3">
        <!-- Search -->
        <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
          <span class="text-gray-400 text-sm">🔍</span>
          <input
            v-model="search"
            type="text"
            placeholder="Search products..."
            class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-36"
          />
        </div>
        <!-- Add Product Button -->
        <button
          @click="$emit('add')"
          class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors"
        >
          <span>+</span>
          Add Product
        </button>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-1 px-6 py-3 border-b border-gray-100">
      <button
        v-for="tab in tabs"
        :key="tab"
        @click="activeTab = tab"
        :class="[
          'px-4 py-1.5 rounded-full text-xs font-semibold transition-colors',
          activeTab === tab
            ? 'bg-green-500 text-white'
            : 'bg-gray-100 text-gray-500 hover:bg-gray-200'
        ]"
      >
        {{ tab }}
      </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Product</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Category</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Price</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Stock</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
            <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr
            v-for="product in filteredProducts"
            :key="product.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <!-- Product -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                  <img
                    :src="product.image"
                    :alt="product.name"
                    class="w-full h-full object-contain p-1"
                  />
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">{{ product.name }}</p>
                  <p class="text-xs text-gray-400">SKU: {{ product.sku }}</p>
                </div>
              </div>
            </td>

            <!-- Category -->
            <td class="px-6 py-4">
              <span class="text-xs bg-green-100 text-green-700 font-semibold px-2.5 py-1 rounded-full">
                {{ product.category }}
              </span>
            </td>

            <!-- Price -->
            <td class="px-6 py-4">
              <p class="text-sm font-bold text-gray-800">₱{{ product.price.toFixed(2) }}</p>
              <p v-if="product.originalPrice" class="text-xs text-gray-400 line-through">
                ₱{{ product.originalPrice.toFixed(2) }}
              </p>
            </td>

            <!-- Stock -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <span
                  :class="[
                    'text-sm font-semibold',
                    product.stock <= 5 ? 'text-red-500' :
                    product.stock <= 20 ? 'text-yellow-500' :
                    'text-gray-800'
                  ]"
                >
                  {{ product.stock }}
                </span>
                <span class="text-xs text-gray-400">units</span>
              </div>
            </td>

            <!-- Status -->
            <td class="px-6 py-4">
              <span
                :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                  product.status === 'Active'   ? 'bg-green-100 text-green-700' :
                  product.status === 'Inactive' ? 'bg-gray-100 text-gray-500' :
                  'bg-yellow-100 text-yellow-700'
                ]"
              >
                {{ product.status }}
              </span>
            </td>

            <!-- Actions -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  @click="$emit('edit', product)"
                  class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                >
                  Edit
                </button>
                <button
                  @click="$emit('toggle', product)"
                  :class="[
                    'text-xs font-semibold px-3 py-1.5 rounded-full transition-colors',
                    product.status === 'Active'
                      ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-600'
                      : 'bg-green-100 hover:bg-green-200 text-green-600'
                  ]"
                >
                  {{ product.status === 'Active' ? 'Deactivate' : 'Activate' }}
                </button>
                <button
                  @click="$emit('delete', product.id)"
                  class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                >
                  Delete
                </button>
              </div>
            </td>

          </tr>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <div
      v-if="filteredProducts.length === 0"
      class="flex flex-col items-center justify-center py-16 text-center"
    >
      <span class="text-5xl mb-3">🛍️</span>
      <h3 class="text-base font-bold text-gray-700 mb-1">No products found</h3>
      <p class="text-sm text-gray-400">Try a different search or add a new product</p>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
      <p class="text-sm text-gray-500">
        Showing <span class="font-semibold text-gray-800">{{ filteredProducts.length }}</span> products
      </p>
      <div class="flex items-center gap-2">
        <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-green-500 hover:text-green-500 transition-colors text-sm">
          ←
        </button>
        <button class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold">
          1
        </button>
        <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:border-green-500 hover:text-green-500 transition-colors text-sm">
          →
        </button>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps<{
  products: {
    id: number
    name: string
    sku: string
    category: string
    price: number
    originalPrice?: number
    stock: number
    status: string
    image: string
  }[]
}>()

defineEmits<{
  (e: 'add'): void
  (e: 'edit', product: object): void
  (e: 'toggle', product: object): void
  (e: 'delete', id: number): void
}>()

const search = ref('')
const activeTab = ref('All')
const tabs = ['All', 'Active', 'Inactive', 'Low Stock']

const filteredProducts = computed(() => {
  let result = [...props.products]

  if (search.value) {
    result = result.filter(p =>
      p.name.toLowerCase().includes(search.value.toLowerCase())
    )
  }

  if (activeTab.value === 'Active')    result = result.filter(p => p.status === 'Active')
  if (activeTab.value === 'Inactive')  result = result.filter(p => p.status === 'Inactive')
  if (activeTab.value === 'Low Stock') result = result.filter(p => p.stock <= 10)

  return result
})
</script>