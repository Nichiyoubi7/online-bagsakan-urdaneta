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
            </div>
          </div>

          <!-- Delivery Type -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
            <h3 class="text-base font-black text-gray-800 mb-4">Delivery Option</h3>
            <div class="flex flex-col gap-3">
              <label :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.deliveryType === 'delivery' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']">
                <input type="radio" v-model="form.deliveryType" value="delivery" class="accent-green-500" />
                <div>
                  <p class="text-sm font-semibold text-gray-800">Home Delivery</p>
                  <p class="text-xs text-gray-400">Delivered to your address — ₱50 fee</p>
                </div>
                <span class="ml-auto text-sm font-bold text-gray-700">₱50.00</span>
              </label>
              <label :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.deliveryType === 'pickup' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']">
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
              <label :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.paymentMethod === 'cod' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']">
                <input type="radio" v-model="form.paymentMethod" value="cod" class="accent-green-500" />
                <div class="w-10 h-7 rounded flex items-center justify-center bg-green-500 text-white text-xs font-black shrink-0">₱</div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">Cash on Delivery</p>
                  <p class="text-xs text-gray-400">Pay when you receive your order</p>
                </div>
                <span class="ml-auto text-xs bg-green-100 text-green-600 font-semibold px-2 py-0.5 rounded-full">Recommended</span>
              </label>
              <label :class="['flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 cursor-pointer transition-all', form.paymentMethod === 'gcash' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-gray-200']">
                <input type="radio" v-model="form.paymentMethod" value="gcash" class="accent-green-500" />
                <div class="w-10 h-7 rounded flex items-center justify-center bg-blue-500 text-white text-xs font-black shrink-0">G</div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">GCash</p>
                  <p class="text-xs text-gray-400">Pay via GCash — AI-verified receipt</p>
                </div>
              </label>
            </div>
          </div>

          <!-- Order Note -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6">
            <h3 class="text-base font-black text-gray-800 mb-3">Order Note <span class="text-gray-400 font-normal text-sm">(optional)</span></h3>
            <textarea
              v-model="form.note"
              rows="3"
              placeholder="Special instructions for your order..."
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors resize-none"
            />
          </div>

        </div>

        <!-- Right: Order Summary -->
        <div class="flex flex-col gap-4">

          <!-- Items per Seller -->
          <div
            v-for="group in sellerGroups"
            :key="group.sellerId"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
          >
            <div class="flex items-center gap-2 px-5 py-3 bg-gray-50 border-b border-gray-100">
              <span class="text-base">🏪</span>
              <span class="text-sm font-bold text-gray-800">{{ group.sellerName }}</span>
            </div>
            <div class="p-4 flex flex-col gap-3">
              <div v-for="item in group.items" :key="item.id" class="flex items-center gap-3">
                <img :src="item.image" :alt="item.name" class="w-12 h-12 object-contain rounded-lg bg-gray-50 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-gray-800 truncate">{{ item.name }}</p>
                  <p class="text-xs text-gray-400">x{{ item.quantity }}</p>
                </div>
                <p class="text-sm font-bold text-green-600 shrink-0">₱{{ (item.price * item.quantity).toFixed(2) }}</p>
              </div>
            </div>
          </div>

          <!-- Tip -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-black text-gray-800 mb-3">Add a Tip for Driver</h3>
            <div class="flex gap-2 flex-wrap">
              <button
                v-for="t in tipOptions"
                :key="t"
                @click="form.tip = t"
                :class="[
                  'px-3 py-1.5 rounded-full text-xs font-semibold border transition-all',
                  form.tip === t ? 'bg-green-500 text-white border-green-500' : 'border-gray-200 text-gray-600 hover:border-green-400'
                ]"
              >
                {{ t === 0 ? 'No tip' : '₱' + t }}
              </button>
            </div>
          </div>

          <!-- Price Summary -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-base font-black text-gray-800 mb-4">Order Summary</h3>
            <div class="flex flex-col gap-2 text-sm">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal ({{ selectedItems.length }} items)</span>
                <span>₱{{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Delivery Fee</span>
                <span>{{ deliveryFee > 0 ? '₱' + deliveryFee.toFixed(2) : 'Free' }}</span>
              </div>
              <div v-if="form.tip > 0" class="flex justify-between text-gray-600">
                <span>Tip</span>
                <span>₱{{ form.tip.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between font-black text-gray-900 text-base pt-2 border-t border-gray-100 mt-1">
                <span>Total</span>
                <span class="text-green-600">₱{{ orderTotal.toFixed(2) }}</span>
              </div>
            </div>

            <p v-if="error" class="mt-3 text-xs text-red-500 bg-red-50 px-3 py-2 rounded-lg">{{ error }}</p>

            <button
              @click="handlePlaceOrder"
              :disabled="loading"
              class="mt-4 w-full bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2"
            >
              <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
              </svg>
              {{ loading ? 'Placing Order...' : 'Place Order' }}
            </button>
            <p class="text-center text-xs text-gray-400 mt-2">By placing your order, you agree to our terms of service.</p>
          </div>

        </div>
      </div>
    </div>

    <!-- Success Modal (COD only) -->
    <Transition name="fade">
      <div v-if="showSuccess" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
          <h2 class="text-xl font-black text-gray-900 mb-2">Order Placed! 🎉</h2>
          <p class="text-gray-400 text-sm mb-6">Your order has been successfully placed. The seller will confirm it shortly.</p>
          <NuxtLink
            to="/customer/orders"
            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-colors block"
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
const router = useRouter()

const loading = ref(false)
const error = ref('')
const showSuccess = ref(false)
let lastOrderId = 0

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

const selectedItems = computed(() =>
  cartStore.items.filter(i => i.selected)
)

const sellerGroups = computed(() => {
  const groups: Record<number, { sellerId: number; sellerName: string; items: typeof selectedItems.value }> = {}
  selectedItems.value.forEach(item => {
    if (!groups[item.sellerId]) {
      groups[item.sellerId] = { sellerId: item.sellerId, sellerName: item.sellerName, items: [] }
    }
    groups[item.sellerId].items.push(item)
  })
  return Object.values(groups)
})

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

  if (form.value.deliveryType === 'delivery' && !form.value.address.trim()) {
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

    for (const group of sellerGroups.value) {
      const res: any = await post('/orders', {
        seller_id:        group.sellerId,
        delivery_type:    form.value.deliveryType,
        payment_method:   form.value.paymentMethod,
        delivery_address: deliveryAddress,
        delivery_note:    form.value.note,
        tip:              form.value.tip,
        items: group.items.map(i => ({
          product_id: i.productId,
          quantity:   i.quantity,
        })),
      })
      lastOrderId = res.order?.id
    }

    selectedItems.value.forEach(i => cartStore.removeItem(i.id))

    if (form.value.paymentMethod === 'gcash') {
      router.push(`/payment/verify?order_id=${lastOrderId}&total=${orderTotal.value.toFixed(2)}`)
    } else {
      showSuccess.value = true
    }

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