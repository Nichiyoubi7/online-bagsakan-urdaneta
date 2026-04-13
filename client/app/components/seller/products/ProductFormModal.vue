<template>
  <Transition name="fade">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h2 class="text-lg font-black text-gray-900">{{ product ? 'Edit Product' : 'Add Product' }}</h2>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 text-xl font-bold">✕</button>
        </div>

        <div class="px-6 py-5 flex flex-col gap-4">

          <!-- Image Upload -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
              Product Images <span class="text-xs text-gray-400 font-normal">(max 5)</span>
            </label>

            <!-- Preview uploaded images -->
            <div v-if="previewImages.length > 0" class="flex gap-2 flex-wrap mb-3">
              <div
                v-for="(img, index) in previewImages"
                :key="index"
                class="relative w-20 h-20 rounded-xl overflow-hidden border border-gray-200"
              >
                <img :src="img" class="w-full h-full object-cover" />
                <button
                  @click="removeImage(index)"
                  class="absolute top-0.5 right-0.5 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center"
                >✕</button>
              </div>
            </div>

            <!-- Upload button -->
            <label
              v-if="previewImages.length < 5"
              class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-colors"
            >
              <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <span class="text-xs text-gray-400">Click to upload images</span>
              <input
                type="file"
                multiple
                accept="image/*"
                class="hidden"
                @change="handleImageUpload"
              />
            </label>
          </div>

          <!-- Name -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Product Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="e.g. Fresh Tomatoes"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
            />
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
            <select
              v-model="form.category_id"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all bg-white"
            >
              <option value="">Select a category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>

          <!-- Price + Original Price -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Price (₱)</label>
              <input
                v-model="form.price"
                type="number"
                min="0"
                placeholder="0.00"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Original Price (₱)</label>
              <input
                v-model="form.original_price"
                type="number"
                min="0"
                placeholder="Optional"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
          </div>

          <!-- Stock + Weight -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stock</label>
              <input
                v-model="form.stock"
                type="number"
                min="0"
                placeholder="0"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Weight (kg)</label>
              <input
                v-model="form.weight_kg"
                type="number"
                min="0"
                step="0.01"
                placeholder="0.00"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
              />
            </div>
          </div>

          <!-- SKU -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">SKU <span class="text-xs text-gray-400 font-normal">(optional)</span></label>
            <input
              v-model="form.sku"
              type="text"
              placeholder="SKU-001"
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

          <!-- Error -->
          <p v-if="error" class="text-xs text-red-600 bg-red-50 border border-red-200 px-3 py-2 rounded-lg">
            {{ error }}
          </p>

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

const config = useRuntimeConfig()
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

// Image handling
const selectedFiles = ref<File[]>([])
const previewImages = ref<string[]>([])

const handleImageUpload = (e: Event) => {
  const input = e.target as HTMLInputElement
  if (!input.files) return

  const newFiles = Array.from(input.files)
  const remaining = 5 - selectedFiles.value.length
  const toAdd = newFiles.slice(0, remaining)

  toAdd.forEach(file => {
    selectedFiles.value.push(file)
    previewImages.value.push(URL.createObjectURL(file))
  })

  // Reset input so same file can be re-selected
  input.value = ''
}

const removeImage = (index: number) => {
  selectedFiles.value.splice(index, 1)
  previewImages.value.splice(index, 1)
}

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
    // Show existing images as previews
    previewImages.value = (p.images || []).map((img: any) =>
      `${config.public.apiBase.replace('/api', '')}/storage/${img.path}`
    )
    selectedFiles.value = []
  } else {
    form.value = {
      name: '', category_id: '', price: '', original_price: '',
      stock: '', weight_kg: '', sku: '', description: '', status: 'active',
    }
    previewImages.value = []
    selectedFiles.value = []
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
    // Use FormData because we're uploading files
    const formData = new FormData()
    formData.append('name',        form.value.name)
    formData.append('category_id', String(form.value.category_id))
    formData.append('price',       String(form.value.price))
    formData.append('stock',       String(form.value.stock))
    formData.append('status',      form.value.status)

    if (form.value.original_price) formData.append('original_price', String(form.value.original_price))
    if (form.value.weight_kg)      formData.append('weight_kg',      String(form.value.weight_kg))
    if (form.value.sku)            formData.append('sku',            form.value.sku)
    if (form.value.description)    formData.append('description',    form.value.description)

    // Append new image files
    selectedFiles.value.forEach(file => {
      formData.append('images[]', file)
    })

    const token = localStorage.getItem('obra_token')
    const baseURL = config.public.apiBase

    let res: any
    if (props.product) {
      // Laravel needs _method spoofing for PUT with FormData
      formData.append('_method', 'PUT')
      res = await fetch(`${baseURL}/products/${props.product.id}`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
      }).then(r => r.json())
    } else {
      res = await fetch(`${baseURL}/products`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
      }).then(r => r.json())
    }

    if (res.errors || res.message?.toLowerCase().includes('fail')) {
      error.value = res.message || 'Something went wrong.'
      return
    }

    emit('saved', res.product)
  } catch (e: any) {
    error.value = 'Failed to save product. Please try again.'
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>