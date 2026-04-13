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
        <div class="px-6 py-5 flex flex-col gap-4">

          <!-- Error -->
          <div v-if="error" class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-xl">
            {{ error }}
          </div>

          <!-- Product Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Name <span class="text-red-400">*</span></label>
            <input
              v-model="form.name"
              type="text"
              placeholder="e.g. Fresh Tomato"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
            />
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category <span class="text-red-400">*</span></label>
            <select
              v-model="form.category_id"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all bg-white"
            >
              <option value="">Select category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>

          <!-- Price + Original Price -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Price (₱) <span class="text-red-400">*</span></label>
              <input
                v-model="form.price"
                type="number"
                placeholder="0.00"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Original Price (₱)</label>
              <input
                v-model="form.original_price"
                type="number"
                placeholder="0.00"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
          </div>

          <!-- Stock + Weight -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stock <span class="text-red-400">*</span></label>
              <input
                v-model="form.stock"
                type="number"
                placeholder="0"
                min="0"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Weight (kg)</label>
              <input
                v-model="form.weight_kg"
                type="number"
                placeholder="0.00"
                step="0.01"
                min="0"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
          </div>

          <!-- SKU -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">SKU</label>
            <input
              v-model="form.sku"
              type="text"
              placeholder="e.g. SKU-001"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Describe your product..."
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all resize-none"
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
            <select
              v-model="form.status"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all bg-white"
            >
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <!-- Buttons -->
          <div class="flex gap-3 pt-2">
            <button
              @click="$emit('close')"
              type="button"
              class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              @click="handleSubmit"
              :disabled="saving"
              class="flex-1 px-4 py-3 rounded-xl bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white text-sm font-bold transition-colors flex items-center justify-center gap-2"
            >
              <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
              </svg>
              {{ saving ? 'Saving...' : (product ? 'Save Changes' : 'Add Product') }}
            </button>
          </div>

        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  show: boolean
  product: any
  categories: { id: number; name: string }[]
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'saved', product: any): void
}>()

const { post, put } = useApi()
const saving = ref(false)
const error = ref('')

const form = ref({
  name: '',
  category_id: '',
  price: '',
  original_price: '',
  stock: '',
  weight_kg: '',
  sku: '',
  description: '',
  status: 'active',
})

// Populate form when editing
watch(() => props.product, (p) => {
  if (p) {
    form.value = {
      name:           p.name || '',
      category_id:    p.category_id || '',
      price:          p.price || '',
      original_price: p.original_price || '',
      stock:          p.stock || '',
      weight_kg:      p.weight_kg || '',
      sku:            p.sku || '',
      description:    p.description || '',
      status:         p.status || 'active',
    }
  } else {
    form.value = {
      name: '', category_id: '', price: '', original_price: '',
      stock: '', weight_kg: '', sku: '', description: '', status: 'active',
    }
  }
}, { immediate: true })

const handleSubmit = async () => {
  error.value = ''

  if (!form.value.name.trim()) { error.value = 'Product name is required.'; return }
  if (!form.value.category_id) { error.value = 'Please select a category.'; return }
  if (!form.value.price)       { error.value = 'Price is required.'; return }
  if (!form.value.stock)       { error.value = 'Stock is required.'; return }

  saving.value = true
  try {
    const payload = {
      name:           form.value.name,
      category_id:    Number(form.value.category_id),
      price:          Number(form.value.price),
      original_price: form.value.original_price ? Number(form.value.original_price) : null,
      stock:          Number(form.value.stock),
      weight_kg:      form.value.weight_kg ? Number(form.value.weight_kg) : 0,
      sku:            form.value.sku || undefined,
      description:    form.value.description || '',
      status:         form.value.status,
    }

    let savedProduct: any
    if (props.product) {
      const res: any = await put(`/products/${props.product.id}`, payload)
      savedProduct = res.product
    } else {
      const res: any = await post('/products', payload)
      savedProduct = res.product
    }

    emit('saved', savedProduct)
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to save product. Please try again.'
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>