<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-6 py-8">
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

    <!-- Checkout Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Forms -->
        <div class="lg:col-span-2">

          <!-- Delivery Address -->
          <CheckoutAddress />

          <!-- Delivery Options -->
          <CheckoutDelivery :is-over-weight="isOverWeight" @change="handleDeliveryChange" />

          <!-- Personal Details -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-base font-black text-gray-800">Personal Details</h3>
              <button class="text-sm text-green-500 hover:text-green-600 font-semibold">Edit</button>
            </div>
            <div class="text-sm text-gray-600">
              <p class="font-semibold text-gray-800">Sunday Carolino</p>
              <p>sundaycarolino0826@gmail.com</p>
              <p>+63 9660813437</p>
            </div>
          </div>

          <!-- Payment -->
          <CheckoutPayment />

          <!-- Tip Your Rider -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
            <h3 class="text-base font-black text-gray-800 mb-1">Tip your rider</h3>
            <p class="text-xs text-gray-400 mb-4">100% of the tips go to your rider. We don't deduct anything from it.</p>
            <div class="flex items-center gap-2 flex-wrap mb-3">
              <button
                v-for="tip in tipOptions"
                :key="tip"
                @click="selectedTip = tip"
                :class="[
                  'px-4 py-2 rounded-full text-sm font-medium border transition-colors',
                  selectedTip === tip
                    ? 'bg-green-500 text-white border-green-500'
                    : 'border-gray-200 text-gray-600 hover:border-green-400'
                ]"
              >
                {{ tip === 0 ? 'Not now' : '₱' + tip.toFixed(2) }}
              </button>
            </div>
            <p class="text-xs text-gray-400">Most common</p>
            <label class="flex items-center gap-2 mt-3 cursor-pointer">
              <input type="checkbox" v-model="saveForNext" class="accent-green-500" />
              <span class="text-sm text-gray-600">Save for your next order</span>
            </label>
          </div>

          <!-- Place Order Button (mobile) -->
          <button
            @click="handlePlaceOrder"
            class="w-full lg:hidden bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-xl transition-colors shadow-md"
          >
            Place Order — ₱{{ orderTotal.toFixed(2) }}
          </button>

        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-1">
          <CheckoutOrderSummary
            :seller-name="orderData.sellerName"
            :items="orderData.items"
            @place-order="handlePlaceOrder"
          />
        </div>

      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import GuestLayout from '../../components/layout/GuestLayout.vue'
import CheckoutAddress from '../../components/customer/checkout/CheckoutAddress.vue'
import CheckoutDelivery from '../../components/customer/checkout/CheckoutDelivery.vue'
import CheckoutPayment from '../../components/customer/checkout/CheckoutPayment.vue'
import CheckoutOrderSummary from '../../components/customer/checkout/CheckoutOrderSummary.vue'
import { navigateTo } from '#app'

const selectedTip = ref(0)
const saveForNext = ref(false)
const tipOptions = [0, 5.00, 20.00, 40.00, 60.00]

const orderData = ref({
  sellerName: "Mang Bert's Wet Market",
  totalWeight: 3.5,
  items: [
    { id: 1, name: 'Tomato',   price: 20.00, quantity: 1 },
    { id: 2, name: 'Eggplant', price: 15.00, quantity: 2 },
    { id: 3, name: 'Kangkong', price: 10.00, quantity: 1 },
  ],
})

const isOverWeight = computed(() => orderData.value.totalWeight > 10)

const orderTotal = computed(() =>
  orderData.value.items.reduce((sum, i) => sum + i.price * i.quantity, 0)
)

const handleDeliveryChange = (option: object) => {
  console.log('Delivery option changed:', option)
}

const handlePlaceOrder = () => {
  alert('Order placed successfully! 🎉')
  navigateTo('/customer/orders')
}
</script>