<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300 mb-2">
          <NuxtLink to="/" class="hover:text-green-400">Home</NuxtLink>
          <span>/</span>
          <NuxtLink to="/customer/orders" class="hover:text-green-400">My Orders</NuxtLink>
          <span>/</span>
          <span class="text-white">Order #{{ route.params.id }}</span>
        </div>
        <h1 class="text-white text-2xl font-black">Order Details</h1>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-6 md:py-8">

      <!-- Loading -->
      <div v-if="loading" class="flex flex-col gap-4">
        <div v-for="n in 3" :key="n" class="bg-white rounded-2xl h-32 animate-pulse border border-gray-100" />
      </div>

      <!-- Not Found -->
      <div v-else-if="!order" class="flex flex-col items-center justify-center py-20 text-center">
        <span class="text-5xl mb-4">📦</span>
        <h3 class="text-lg font-bold text-gray-700 mb-2">Order not found</h3>
        <NuxtLink to="/customer/orders" class="text-green-500 hover:underline text-sm">Back to Orders</NuxtLink>
      </div>

      <template v-else>

        <!-- Status Tracker -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6 mb-4">
          <div class="flex items-center justify-between mb-5">
            <div>
              <h3 class="text-base font-black text-gray-800">Order #{{ order.id }}</h3>
              <p class="text-xs text-gray-400">Placed on {{ formatDate(order.created_at) }}</p>
            </div>
            <span :class="['px-3 py-1 rounded-full text-xs font-bold capitalize', statusClass(order.status)]">
              {{ statusLabel(order.status) }}
            </span>
          </div>

          <!-- Tracker -->
          <div class="flex items-center justify-between relative">
            <div class="absolute left-0 right-0 top-3.5 h-0.5 bg-gray-200 z-0" />
            <div
              class="absolute left-0 top-3.5 h-0.5 bg-green-500 z-0 transition-all duration-500"
              :style="{ width: progressWidth }"
            />
            <div v-for="(step, i) in statusSteps" :key="step.key" class="flex flex-col items-center z-10 relative">
              <div :class="[
                'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all',
                isStepDone(step.key) ? 'bg-green-500 text-white' :
                isStepActive(step.key) ? 'bg-green-500 text-white ring-4 ring-green-100' :
                'bg-gray-200 text-gray-400'
              ]">
                <svg v-if="isStepDone(step.key)" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
                <span v-else>{{ i + 1 }}</span>
              </div>
              <p :class="['text-[10px] mt-1.5 font-semibold text-center max-w-[60px] leading-tight', isStepActive(step.key) ? 'text-green-600' : 'text-gray-400']">
                {{ step.label }}
              </p>
            </div>
          </div>
        </div>

        <!-- Order Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

          <!-- Delivery Address -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Delivery Address</p>
            <p class="text-sm font-semibold text-gray-800">{{ order.customer?.name }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ order.delivery_address || 'Store Pickup' }}</p>
            <p class="text-sm text-gray-500">{{ order.customer?.phone || '—' }}</p>
          </div>

          <!-- Seller Info -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Seller</p>
            <div class="flex items-center gap-2">
              <span class="text-2xl">🏪</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">{{ order.seller?.name || 'OBRA Store' }}</p>
                <p class="text-xs text-gray-400">Official Store</p>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Summary</p>
            <div class="flex flex-col gap-1.5 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Payment</span>
                <span class="font-semibold text-gray-800 capitalize">{{ order.payment_method }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Delivery</span>
                <span class="font-semibold text-gray-800 capitalize">{{ order.delivery_type }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-semibold text-gray-800">₱{{ Number(order.subtotal).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Delivery Fee</span>
                <span class="font-semibold text-gray-800">₱{{ Number(order.delivery_fee).toFixed(2) }}</span>
              </div>
              <div v-if="Number(order.tip) > 0" class="flex justify-between">
                <span class="text-gray-500">Tip</span>
                <span class="font-semibold text-gray-800">₱{{ Number(order.tip).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between pt-2 border-t border-gray-100 mt-1">
                <span class="font-black text-gray-900">Total</span>
                <span class="font-black text-green-600">₱{{ Number(order.total).toFixed(2) }}</span>
              </div>
            </div>
          </div>

        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
          <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Items Ordered</h3>
          </div>
          <div class="divide-y divide-gray-50">
            <div
              v-for="item in order.items"
              :key="item.id"
              class="flex items-center gap-4 px-5 py-4"
            >
              <div class="w-14 h-14 bg-gray-50 rounded-xl shrink-0 flex items-center justify-center overflow-hidden">
                <img
                  v-if="imageMap[item.product_name]"
                  :src="imageMap[item.product_name]"
                  :alt="item.product_name"
                  class="w-full h-full object-contain p-1"
                />
                <span v-else class="text-2xl">🥬</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800">{{ item.product_name }}</p>
                <p class="text-xs text-gray-400">₱{{ Number(item.price).toFixed(2) }} each · x{{ item.quantity }}</p>
              </div>
              <p class="text-sm font-bold text-gray-800">₱{{ Number(item.subtotal).toFixed(2) }}</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
          <NuxtLink
            to="/customer/orders"
            class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
          >
            ← Back to Orders
          </NuxtLink>
          <div class="flex gap-3">
            <button
              v-if="order.status === 'pending'"
              @click="cancelOrder"
              :disabled="cancelling"
              class="px-5 py-2.5 rounded-xl border border-red-200 text-red-500 text-sm font-semibold hover:bg-red-50 disabled:opacity-50 transition-colors"
            >
              {{ cancelling ? 'Cancelling...' : 'Cancel Order' }}
            </button>
            <NuxtLink
              to="/customer"
              class="px-5 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-bold transition-colors"
            >
              Continue Shopping
            </NuxtLink>
          </div>
        </div>

      </template>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import GuestLayout from '../../../components/layout/GuestLayout.vue'

const route = useRoute()
const { get, put } = useApi()

const loading = ref(true)
const order = ref<any>(null)
const cancelling = ref(false)

const imageMap: Record<string, string> = {
  'Tomato':        '/images/products/vegetables/Tomato.png',
  'Eggplant':      '/images/products/vegetables/eggplant.png',
  'Bitter Gourd':  '/images/products/vegetables/bitter_gourd.png',
  'Okra':          '/images/products/vegetables/okra.png',
  'Sitaw':         '/images/products/vegetables/sitaw.png',
  'Kangkong':      '/images/products/vegetables/kangkong.png',
  'Repolyo':       '/images/products/vegetables/repolyo.png',
  'Carrot':        '/images/products/vegetables/carrot.png',
  'Potato':        '/images/products/vegetables/potato.png',
  'Sibuyas':       '/images/products/vegetables/sibuyas.png',
  'Bawang':        '/images/products/vegetables/bawang.png',
  'Luya':          '/images/products/vegetables/luya.png',
  'Mais':          '/images/products/vegetables/mais.png',
  'Siling Haba':   '/images/products/vegetables/siling_haba.png',
  'Siling Labuyo': '/images/products/vegetables/siling_labuyo.png',
  'Upo':           '/images/products/vegetables/upo.png',
  'Patola':        '/images/products/vegetables/patola.png',
  'Sigarilyas':    '/images/products/vegetables/sigarilyas.png',
  'Gabi':          '/images/products/vegetables/gabi.png',
  'Kamote':        '/images/products/vegetables/kamote.png',
  'Labanos':       '/images/products/vegetables/labanos.png',
  'Banana':        '/images/products/fruits/saging.png',
  'Mango':         '/images/products/fruits/mango.png',
  'Papaya':        '/images/products/fruits/papaya.png',
  'Pakwan':        '/images/products/fruits/pakwan.png',
  'Bayabas':       '/images/products/fruits/Guava.png',
  'Melon':         '/images/products/fruits/melon.png',
  'Pineapple':     '/images/products/fruits/Pineapple.png',
  'Avocado':       '/images/products/fruits/Avocado.png',
  'Guava':         '/images/products/fruits/Guava.png',
  'Rambutan':      '/images/products/fruits/Rambutan.png',
  'Lanzones':      '/images/products/fruits/Lanzones.jpg',
  'Calamansi':     '/images/products/fruits/Calamansi.png',
  'Orange':        '/images/products/fruits/Orange.png',
  'Apple':         '/images/products/fruits/Apple.png',
  'Grapes':        '/images/products/fruits/Grapes.png',
  'Chicken':       '/images/products/meat/Chicken.png',
  'Pork Meat':     '/images/products/meat/pork_meat.png',
  'Egg':           '/images/products/meat/Egg.png',
  'Rice':          '/images/products/meat/rice.png',
  'Bangus':        '/images/products/meat/Chicken.png',
  'Tilapia':       '/images/products/meat/Chicken.png',
  'Hipon':         '/images/products/meat/Chicken.png',
}

const statusSteps = [
  { key: 'pending',    label: 'Order Received' },
  { key: 'preparing',  label: 'Preparing' },
  { key: 'in_transit', label: 'On the Way' },
  { key: 'delivered',  label: 'Delivered' },
]

const statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'in_transit', 'delivered']

const progressWidth = computed(() => {
  const idx = statusOrder.indexOf(order.value?.status || 'pending')
  if (idx <= 0) return '0%'
  if (order.value?.status === 'delivered') return '100%'
  return `${(idx / (statusOrder.length - 1)) * 100}%`
})

const isStepDone = (key: string) => {
  if (order.value?.status === 'delivered' && key === 'delivered') return true
  const orderIdx = statusOrder.indexOf(order.value?.status)
  const stepIdx = statusOrder.indexOf(key)
  return stepIdx < orderIdx
}

const isStepActive = (key: string) => {
  const s = order.value?.status
  if (key === 'pending') return ['pending', 'confirmed'].includes(s)
  if (key === 'preparing') return ['preparing', 'ready'].includes(s)
  if (key === 'in_transit') return s === 'in_transit'
  if (key === 'delivered') return s === 'delivered'
  return false
}

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}

const statusLabel = (s: string) => {
  const map: Record<string, string> = {
    pending: 'Pending', confirmed: 'Confirmed', preparing: 'Preparing',
    ready: 'Ready', in_transit: 'In Transit', delivered: 'Delivered', cancelled: 'Cancelled',
  }
  return map[s] ?? s
}

const statusClass = (s: string) => {
  const map: Record<string, string> = {
    pending:    'bg-yellow-100 text-yellow-700',
    confirmed:  'bg-blue-100 text-blue-700',
    preparing:  'bg-orange-100 text-orange-700',
    ready:      'bg-purple-100 text-purple-700',
    in_transit: 'bg-blue-100 text-blue-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
  }
  return map[s] ?? 'bg-gray-100 text-gray-700'
}

const cancelOrder = async () => {
  if (!confirm('Are you sure you want to cancel this order?')) return
  cancelling.value = true
  try {
    await put(`/orders/${order.value.id}/status`, { status: 'cancelled' })
    order.value.status = 'cancelled'
  } catch (e: any) {
    alert(e?.data?.message || 'Failed to cancel order.')
  } finally {
    cancelling.value = false
  }
}

onMounted(async () => {
  loading.value = true
  try {
    const res: any = await get(`/orders/${route.params.id}`)
    order.value = res
  } catch (e) {
    console.error(e)
    order.value = null
  } finally {
    loading.value = false
  }
})
</script>