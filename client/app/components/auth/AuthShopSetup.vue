<template>
  <div class="px-6 py-8">

    <!-- Header -->
    <div class="text-center mb-6">
      <div class="text-4xl mb-3">🏪</div>
      <h2 class="text-2xl font-black text-gray-900 mb-1">Set Up Your Shop</h2>
      <p class="text-gray-400 text-sm">Tell buyers about your store</p>
    </div>

    <form @submit.prevent="handleSubmit" class="flex flex-col gap-4">

      <!-- Shop Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Shop Name</label>
        <input
          v-model="form.name"
          type="text"
          placeholder="e.g. Aling Nena's Fresh Goods"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
          required
        />
      </div>

      <!-- Shop Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Shop Description</label>
        <textarea
          v-model="form.description"
          placeholder="e.g. Fresh vegetables and fruits from our farm"
          rows="3"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all resize-none"
        />
      </div>

      <!-- Address -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Shop Address</label>
        <input
          v-model="form.address"
          type="text"
          placeholder="e.g. Stall 12, Urdaneta City Public Market"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
          required
        />
      </div>

      <!-- What do you sell? -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">What do you sell?</label>
        <div class="grid grid-cols-3 gap-2">
          <button
            v-for="cat in categories"
            :key="cat"
            type="button"
            @click="toggleCategory(cat)"
            :class="[
              'py-2 px-1 rounded-xl border text-xs font-semibold transition-all',
              selectedCategories.includes(cat)
                ? 'border-green-500 bg-green-50 text-green-600'
                : 'border-gray-200 text-gray-500 hover:border-green-300'
            ]"
          >
            {{ cat }}
          </button>
        </div>
      </div>

      <p v-if="error" class="text-xs text-red-500 bg-red-50 px-3 py-2 rounded-lg">{{ error }}</p>

      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-semibold py-3 rounded-xl transition-colors duration-200 flex items-center justify-center gap-2"
      >
        <svg v-if="loading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        {{ loading ? 'Setting up your shop...' : 'Open My Shop 🚀' }}
      </button>

      <button
        type="button"
        @click="emit('success')"
        class="text-sm text-gray-400 hover:text-gray-600 text-center"
      >
        Skip for now
      </button>

    </form>

  </div>
</template>

<script setup lang="ts">
const emit = defineEmits<{
  (e: 'success'): void
}>()

const { post } = useApi()

const form = ref({
  name: '',
  description: '',
  address: '',
})

const categories = ['Vegetables', 'Fruits', 'Meat & Fish', 'Rice & Grains', 'Seafood', 'Others']
const selectedCategories = ref<string[]>([])
const loading = ref(false)
const error = ref('')

const toggleCategory = (cat: string) => {
  if (selectedCategories.value.includes(cat)) {
    selectedCategories.value = selectedCategories.value.filter(c => c !== cat)
  } else {
    selectedCategories.value.push(cat)
  }
}

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    await post('/seller/shop', {
      name: form.value.name,
      description: form.value.description + (selectedCategories.value.length > 0 ? ' | Sells: ' + selectedCategories.value.join(', ') : ''),
    })
    emit('success')
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to create shop. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>