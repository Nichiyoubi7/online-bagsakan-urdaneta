<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <NuxtLink to="/customer/cart" class="hover:text-green-400 transition-colors">Cart</NuxtLink>
          <span>/</span>
          <span class="text-white">Checkout</span>
        </div>
        <h1 class="text-white text-2xl font-black mt-1">Review and place your order</h1>
      </div>
    </div>

    <!-- Empty cart guard -->
    <div v-if="selectedItems.length === 0" class="max-w-7xl mx-auto px-4 py-20 text-center">
      <span class="text-6xl mb-4 block">🛒</span>
      <h3 class="text-lg font-bold text-gray-700 mb-2">No items selected</h3>
      <p class="text-sm text-gray-400 mb-6">Go back to your cart and select items to checkout.</p>
      <NuxtLink to="/customer/cart" class="bg-green-500 text-white font-semibold px-6 py-3 rounded-full hover:bg-green-600 transition-colors">
        Back to Cart
      </NuxtLink>
    </div>

    <!-- Checkout Content -->
    <div v-else class="max-w-7xl mx-auto px-4 py-6 md:py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

        <!-- Left: Forms -->
        <div class="lg:col-span-2 flex flex-col gap-4">

          <!-- Delivery Address -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
            <h3 class="text-base font-black text-gray-800 mb-4">Delivery Address</h3>
            <div class="flex flex-col gap-3">
              <input
                v-model="form.address"
                type="text"
                placeholder="House/Unit No., Street Name"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
              />
              <input
                v-model="form.barangay"
                type="text"
                placeholder="Barangay"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
              />
              <div class="grid grid-cols-2 gap-3">
                <input
                  v-model="form.city"
                  type="text"
                  placeholder="City"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
                />
                <input
                  v-model="form.province"
                  type="text"
                  placeholder="Province"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
                />
              </div>
              <textarea
                v-model="form.note"
                placeholder="Delivery note (optional) — e.g. Leave at gate"
                rows="2"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors resize-none"
              />
            </div>
          </div>

          <!-- Delivery Type -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
            <h3 class="text-base font-black text-gray-800 mb-4">Delivery Option</h3>
            <div class="flex flex-col gap-3">
              <label
                :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.deliveryType === 'delivery' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']"
              >
                <input type="radio" v-model="form.deliveryType" value="delivery" class="accent-green-500" />
                <div>
                  <p class="text-sm font-semibold text-gray-800">Home Delivery</p>
                  <p class="text-xs text-gray-400">Delivered to your address — ₱50.00 fee</p>
                </div>
                <span class="ml-auto text-sm font-bold text-gray-700">₱50.00</span>
              </label>
              <label
                :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.deliveryType === 'pickup' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']"
              >
                <input type="radio" v-model="form.deliveryType" value="pickup" class="accent-green-500" />
                <div>
                  <p class="text-sm font-semibold text-gray-800">Store Pickup</p>
                  <p class="text-xs text-gray-400">Pick up from the seller's store — Free</p>
                </div>
                <span class="ml-auto text-sm font-bold text-green-600">Free</span>
              </label>
            </div>
          </div>

          <!-- Personal Details -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
            <h3 class="text-base font-black text-gray-800 mb-4">Personal Details</h3>
            <div class="text-sm text-gray-600 flex flex-col gap-1">
              <p class="font-semibold text-gray-800">{{ authStore.user?.name }}</p>
              <p>{{ authStore.user?.email }}</p>
              <p>{{ authStore.user?.phone || 'No phone number on file' }}</p>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
            <h3 class="text-base font-black text-gray-800 mb-4">Payment Method</h3>
            <div class="flex flex-col gap-3">
              <label
                :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.paymentMethod === 'cod' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']"
              >
                <input type="radio" v-model="form.paymentMethod" value="cod" class="accent-green-500" />
                <div class="w-10 h-7 rounded flex items-center justify-center bg-green-500 text-white text-xs font-black shrink-0">₱</div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">Cash on Delivery</p>
                  <p class="text-xs text-gray-400">Pay when you receive your order</p>
                </div>
                <span class="ml-auto text-xs bg-green-100 text-green-600 font-semibold px-2 py-0.5 rounded-full">Recommended</span>
              </label>
              <label
                :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.paymentMethod === 'gcash' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']"
              >
                <input type="radio" v-model="form.paymentMethod" value="gcash" class="accent-green-500" />
                <div class="w-10 h-7 rounded flex items-center justify-center bg-blue-500 text-white text-xs font-black shrink-0">G</div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">GCash</p>
                  <p class="text-xs text-gray-400">Pay via GCash mobile wallet</p>
                </div>
              </label>
            </div>
          </div>

          <!-- Tip Your Rider -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
            <h3 class="text-base font-black text-gray-800 mb-1">Tip your rider</h3>
            <p class="text-xs text-gray-400 mb-4">100% of the tips go to your rider.</p>
            <div class="flex items-center gap-2 flex-wrap">
              <button
                v-for="tip in tipOptions"
                :key="tip"
                @click="form.tip = tip"
                :class="[
                  'px-4 py-2 rounded-full text-sm font-medium border transition-colors',
                  form.tip === tip
                    ? 'bg-green-500 text-white border-green-500'
                    : 'border-gray-200 text-gray-600 hover:border-green-400'
                ]"
              >
                {{ tip === 0 ? 'No tip' : '₱' + tip.toFixed(2) }}
              </button>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
            {{ error }}
          </div>

          <!-- Place Order Button (mobile) -->
          <button
            @click="handlePlaceOrder"
            :disabled="loading"
            class="lg:hidden w-full bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-bold py-4 rounded-xl transition-colors shadow-md flex items-center justify-center gap-2"
          >
            <svg v-if="loading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            {{ loading ? 'Placing Order...' : `Place Order — ₱${orderTotal.toFixed(2)}` }}
          </button>

        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6 lg:sticky lg:top-24">

            <h3 class="text-base font-black text-gray-800 mb-4">Order Summary</h3>

            <!-- Items -->
            <div class="flex flex-col gap-3 mb-4">
              <div
                v-for="item in selectedItems"
                :key="item.id"
                class="flex items-center gap-3"
              >
                <img :src="item.image" :alt="item.name" class="w-10 h-10 object-contain bg-gray-50 rounded-lg shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-800 truncate">{{ item.name }}</p>
                  <p class="text-xs text-gray-400">{{ item.quantity }}x ₱{{ item.price.toFixed(2) }}</p>
                </div>
                <span class="text-sm font-bold text-gray-800 shrink-0">₱{{ (item.price * item.quantity).toFixed(2) }}</span>
              </div>
            </div>

            <!-- Price Breakdown -->
            <div class="flex flex-col gap-2 py-3 border-t border-gray-100 text-sm">
              <div class="flex items-center justify-between text-gray-600">
                <span>Subtotal</span>
                <span>₱{{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex items-center justify-between text-gray-600">
                <span>Delivery fee</span>
                <span :class="deliveryFee === 0 ? 'text-green-500 font-semibold' : ''">
                  {{ deliveryFee === 0 ? 'Free' : '₱' + deliveryFee.toFixed(2) }}
                </span>
              </div>
              <div v-if="form.tip > 0" class="flex items-center justify-between text-gray-600">
                <span>Rider tip</span>
                <span>₱{{ form.tip.toFixed(2) }}</span>
              </div>
            </div>

            <!-- Total -->
            <div class="flex items-center justify-between py-3 border-t border-gray-100 mb-4">
              <p class="text-sm font-bold text-gray-800">Total</p>
              <p class="text-xl font-black text-green-600">₱{{ orderTotal.toFixed(2) }}</p>
            </div>

            <!-- Place Order (desktop) -->
            <button
              @click="handlePlaceOrder"
              :disabled="loading"
              class="hidden lg:flex w-full bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-bold py-4 rounded-xl transition-colors shadow-md items-center justify-center gap-2"
            >
              <svg v-if="loading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              {{ loading ? 'Placing Order...' : 'Place Order' }}
            </button>

            <p class="text-xs text-gray-400 text-center mt-3">
              By placing your order, you agree to our
              <NuxtLink to="/terms" class="text-green-500 hover:underline">Terms & Conditions</NuxtLink>
            </p>
          </div>
        </div>

      </div>
    </div>

    <!-- Success Modal -->
    <Transition name="fade">
      <div v-if="showSuccess" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 flex flex-col items-center text-center">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
          <h2 class="text-2xl font-black text-gray-900 mb-2">Order Placed! 🎉</h2>
          <p class="text-gray-400 text-sm mb-6">Your order has been successfully placed. The seller will confirm it shortly.</p>
          <NuxtLink
            to="/customer/orders"
            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-colors"
          >
            View My Orders
          </NuxtLink>
        </div>
      </div>
    </Transition>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import GuestLayout from '../../components/layout/GuestLayout.vue'

const cartStore = useCartStore()
const authStore = useAuthStore()
const { post } = useApi()

const loading = ref(false)
const error = ref('')
const showSuccess = ref(false)

const form = ref({
  address: '',
  barangay: '',
  city: 'Urdaneta City',
  province: 'Pangasinan',
  note: '',
  deliveryType: 'delivery' as 'delivery' | 'pickup',
  paymentMethod: 'cod' as 'cod' | 'gcash',
  tip: 0,
})

const tipOptions = [0, 5, 20, 40, 60]

onMounted(() => {
  cartStore.loadFromStorage()
})

// Only selected items go to checkout
const selectedItems = computed(() =>
  cartStore.items.filter(i => i.selected)
)

const subtotal = computed(() =>
  selectedItems.value.reduce((sum, i) => sum + i.price * i.quantity, 0)
)

const deliveryFee = computed(() =>
  form.value.deliveryType === 'delivery' ? 50 : 0
)

const orderTotal = computed(() =>
  subtotal.value + deliveryFee.value + form.value.tip
)

const handlePlaceOrder = async () => {
  error.value = ''

  if (!form.value.address && form.value.deliveryType === 'delivery') {
    error.value = 'Please enter your delivery address.'
    return
  }

  if (selectedItems.value.length === 0) {
    error.value = 'No items selected for checkout.'
    return
  }

  loading.value = true

  try {
    const deliveryAddress = form.value.deliveryType === 'delivery'
      ? `${form.value.address}, ${form.value.barangay}, ${form.value.city}, ${form.value.province}`
      : 'Store Pickup'

    await post('/orders', {
      seller_id: selectedItems.value[0].sellerId,
      delivery_type: form.value.deliveryType,
      payment_method: form.value.paymentMethod,
      delivery_address: deliveryAddress,
      delivery_note: form.value.note,
      tip: form.value.tip,
      items: selectedItems.value.map(i => ({
        product_id: i.productId,
        quantity: i.quantity,
      })),
    })

    // Remove only the checked-out items from cart
    selectedItems.value.forEach(i => cartStore.removeItem(i.id))
    showSuccess.value = true

  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to place order. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>