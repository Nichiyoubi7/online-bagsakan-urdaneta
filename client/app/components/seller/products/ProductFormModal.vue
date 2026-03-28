<template>
  <Transition name="fade">
    <div
      v-if="show"
      class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
      @click.self="$emit('close')"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h3 class="text-lg font-black text-gray-800">
            {{ product ? 'Edit Product' : 'Add New Product' }}
          </h3>
          <button
            @click="$emit('close')"
            class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors"
          >
            <span class="text-gray-600 text-sm">✕</span>
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="px-6 py-5 flex flex-col gap-4">

          <!-- Product Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="e.g. Green Apple"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              required
            />
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
            <select
              v-model="form.category"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all bg-white"
              required
            >
              <option value="">Select category</option>
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>

          <!-- Price + Original Price -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Price (₱)</label>
              <input
                v-model="form.price"
                type="number"
                placeholder="0.00"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
                required
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Original Price (₱)</label>
              <input
                v-model="form.originalPrice"
                type="number"
                placeholder="0.00"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
          </div>

          <!-- Stock -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stock Quantity</label>
            <input
              v-model="form.stock"
              type="number"
              placeholder="0"
              min="0"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              required
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
            <textarea
              v-model="form.description"
              placeholder="Describe your product..."
              rows="3"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all resize-none"
            />
          </div>

          <!-- Image URL -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Image URL</label>
            <input
              v-model="form.image"
              type="text"
              placeholder="/images/products/your-product.png"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
            />
          </div>

          <!-- Status -->
          <div class="flex items-center justify-between py-2">
            <div>
              <p class="text-sm font-semibold text-gray-700">Active Status</p>
              <p class="text-xs text-gray-400">Product will be visible to customers</p>
            </div>
            <button
              type="button"
              @click="form.active = !form.active"
              :class="[
                'relative w-11 h-6 rounded-full transition-colors duration-200',
                form.active ? 'bg-green-500' : 'bg-gray-200'
              ]"
            >
              <span
                :class="[
                  'absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200',
                  form.active ? 'translate-x-5' : 'translate-x-0'
                ]"
              />
            </button>
          </div>

          <!-- Error -->
          <p v-if="error" class="text-xs text-red-500 bg-red-50 px-3 py-2 rounded-xl">
            {{ error }}
          </p>

          <!-- Submit -->
          <div class="flex gap-3 pt-2">
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 py-3 rounded-xl bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white text-sm font-bold transition-colors flex items-center justify-center gap-2"
            >
              <svg v-if="loading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              {{ loading ? 'Saving...' : product ? 'Save Changes' : 'Add Product' }}
            </button>
          </div>

        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  show: boolean
  product?: any
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', data: object): void
}>()

const loading = ref(false)
const error = ref('')

const categories = [
  'Fresh Fruit', 'Vegetables', 'Meat & Fish',
  'Snacks', 'Beverages', 'Bread & Bakery',
  'Cooking', 'Diabetic Food', 'Beauty & Health',
]

const form = ref({
  name: '',
  category: '',
  price: 0,
  originalPrice: 0,
  stock: 0,
  description: '',
  image: '',
  active: true,
})

// Fill form when editing
watch(() => props.product, (val) => {
  if (val) {
    form.value = {
      name: val.name,
      category: val.category,
      price: val.price,
      originalPrice: val.originalPrice ?? 0,
      stock: val.stock,
      description: val.description ?? '',
      image: val.image,
      active: val.status === 'Active',
    }
  } else {
    form.value = { name: '', category: '', price: 0, originalPrice: 0, stock: 0, description: '', image: '', active: true }
  }
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    await new Promise(resolve => setTimeout(resolve, 800))
    emit('save', { ...form.value })
    emit('close')
  } catch (e) {
    error.value = 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>