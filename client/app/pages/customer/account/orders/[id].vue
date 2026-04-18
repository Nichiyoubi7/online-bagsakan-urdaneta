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

          <!-- ETA Banner -->
          <div
            v-if="['confirmed','preparing','ready','in_transit'].includes(order.status)"
            class="flex items-center gap-3 rounded-xl px-4 py-3 mb-5"
            :class="{
              'bg-blue-50 border border-blue-100':    order.status === 'in_transit',
              'bg-purple-50 border border-purple-100': order.status === 'ready',
              'bg-orange-50 border border-orange-100': order.status === 'preparing',
              'bg-green-50 border border-green-100':   order.status === 'confirmed',
            }"
          >
            <span class="text-2xl">{{ etaEmoji(order.status) }}</span>
            <div>
              <p class="text-sm font-bold" :class="{
                'text-blue-700':   order.status === 'in_transit',
                'text-purple-700': order.status === 'ready',
                'text-orange-700': order.status === 'preparing',
                'text-green-700':  order.status === 'confirmed',
              }">{{ etaLabel(order.status) }}</p>
              <p class="text-xs mt-0.5" :class="{
                'text-blue-500':   order.status === 'in_transit',
                'text-purple-500': order.status === 'ready',
                'text-orange-500': order.status === 'preparing',
                'text-green-600':  order.status === 'confirmed',
              }">Estimated delivery: <span class="font-semibold">{{ etaTime(order.status) }}</span></p>
            </div>
          </div>

          <!-- Delivered Banner -->
          <div
            v-if="order.status === 'delivered'"
            class="flex items-center gap-3 rounded-xl px-4 py-3 mb-5 bg-green-50 border border-green-100"
          >
            <span class="text-2xl">🎉</span>
            <div class="flex-1">
              <p class="text-sm font-bold text-green-700">Order delivered successfully!</p>
              <p class="text-xs text-green-600 mt-0.5">Thank you for your purchase. How was your experience?</p>
            </div>
            <button
              v-if="!allReviewed"
              @click="showRatingModal = true"
              class="shrink-0 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded-xl transition-colors"
            >
              ⭐ Rate Order
            </button>
            <span v-else class="shrink-0 text-xs font-semibold text-green-600 bg-green-100 px-3 py-1.5 rounded-xl">
              ✓ Reviewed
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
            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 px-5 py-4">
              <div class="w-14 h-14 bg-gray-50 rounded-xl shrink-0 flex items-center justify-center overflow-hidden">
                <img v-if="imageMap[item.product_name]" :src="imageMap[item.product_name]" :alt="item.product_name" class="w-full h-full object-contain p-1" />
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
          <NuxtLink to="/customer/orders" class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
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
            <button
              v-if="order.status === 'delivered'"
              @click="printInvoice"
              class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors"
            >
              🧾 Download Invoice
            </button>
            <NuxtLink to="/customer" class="px-5 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-bold transition-colors">
              Continue Shopping
            </NuxtLink>
          </div>
        </div>

        <!-- Printable Invoice -->
        <div id="obra-invoice" class="hidden">
          <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 32px;">
            <div style="text-align: center; margin-bottom: 24px;">
              <h1 style="font-size: 24px; font-weight: 900; color: #0f2d1f; margin: 0;">OBRA</h1>
              <p style="font-size: 12px; color: #6b7280; margin: 4px 0;">Online Bagsakan Urdaneta</p>
              <p style="font-size: 12px; color: #6b7280; margin: 0;">Urdaneta City, Pangasinan</p>
            </div>
            <div style="border-top: 2px solid #0f2d1f; border-bottom: 1px solid #e5e7eb; padding: 12px 0; margin-bottom: 20px;">
              <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 8px;">OFFICIAL RECEIPT</h2>
              <div style="display: flex; justify-content: space-between; font-size: 13px; color: #374151;">
                <span>Order #{{ order.id }}</span>
                <span>{{ formatDate(order.created_at) }}</span>
              </div>
            </div>
            <div style="margin-bottom: 20px; font-size: 13px;">
              <p style="font-weight: 700; margin: 0 0 4px;">Bill To:</p>
              <p style="margin: 0;">{{ order.customer?.name }}</p>
              <p style="margin: 0; color: #6b7280;">{{ order.customer?.email }}</p>
              <p style="margin: 0; color: #6b7280;">{{ order.delivery_address || 'Store Pickup' }}</p>
            </div>
            <div style="margin-bottom: 20px; font-size: 13px;">
              <p style="font-weight: 700; margin: 0 0 4px;">Seller:</p>
              <p style="margin: 0;">{{ order.seller?.name }}</p>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;">
              <thead>
                <tr style="background: #f3f4f6;">
                  <th style="text-align: left; padding: 8px; border: 1px solid #e5e7eb;">Item</th>
                  <th style="text-align: center; padding: 8px; border: 1px solid #e5e7eb;">Qty</th>
                  <th style="text-align: right; padding: 8px; border: 1px solid #e5e7eb;">Price</th>
                  <th style="text-align: right; padding: 8px; border: 1px solid #e5e7eb;">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in order.items" :key="item.id">
                  <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ item.product_name }}</td>
                  <td style="padding: 8px; border: 1px solid #e5e7eb; text-align: center;">{{ item.quantity }}</td>
                  <td style="padding: 8px; border: 1px solid #e5e7eb; text-align: right;">₱{{ Number(item.price).toFixed(2) }}</td>
                  <td style="padding: 8px; border: 1px solid #e5e7eb; text-align: right;">₱{{ Number(item.subtotal).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>
            <div style="text-align: right; font-size: 13px; margin-bottom: 20px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>Subtotal</span><span>₱{{ Number(order.subtotal).toFixed(2) }}</span></div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>Delivery Fee</span><span>₱{{ Number(order.delivery_fee).toFixed(2) }}</span></div>
              <div v-if="Number(order.tip) > 0" style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span>Tip</span><span>₱{{ Number(order.tip).toFixed(2) }}</span></div>
              <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 15px; border-top: 2px solid #0f2d1f; padding-top: 8px; margin-top: 8px;">
                <span>TOTAL</span><span>₱{{ Number(order.total).toFixed(2) }}</span>
              </div>
            </div>
            <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;"><span style="font-weight: 700;">Payment:</span> {{ order.payment_method?.toUpperCase() }}</div>
            <div style="font-size: 12px; color: #6b7280; margin-bottom: 20px;"><span style="font-weight: 700;">Delivery:</span> {{ order.delivery_type?.toUpperCase() }}</div>
            <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 16px; font-size: 12px; color: #6b7280;">
              <p style="margin: 0;">Thank you for shopping at OBRA!</p>
              <p style="margin: 4px 0 0;">obra-ur.xyz · Urdaneta City, Pangasinan</p>
            </div>
          </div>
        </div>

      </template>
    </div>

    <!-- Rating Modal -->
    <Transition name="fade">
      <div v-if="showRatingModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" @click.self="showRatingModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
            <div>
              <h3 class="text-base font-black text-gray-800">Rate Your Order</h3>
              <p class="text-xs text-gray-400">Order #{{ order?.id }}</p>
            </div>
            <button @click="showRatingModal = false" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500">✕</button>
          </div>
          <div class="px-6 py-5 flex flex-col gap-6">
            <div v-for="item in order?.items" :key="item.id" class="flex flex-col gap-3">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-50 rounded-xl shrink-0 flex items-center justify-center overflow-hidden">
                  <img v-if="imageMap[item.product_name]" :src="imageMap[item.product_name]" :alt="item.product_name" class="w-full h-full object-contain p-1" />
                  <span v-else class="text-xl">🥬</span>
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-800">{{ item.product_name }}</p>
                  <p class="text-xs text-gray-400">x{{ item.quantity }}</p>
                </div>
                <span v-if="reviewedProductIds.has(item.product_id)" class="ml-auto text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">✓ Reviewed</span>
              </div>
              <div v-if="!reviewedProductIds.has(item.product_id)">
                <div class="flex gap-1 mb-2">
                  <button v-for="star in 5" :key="star" @click="setRating(item.product_id, star)" :class="star <= (ratings[item.product_id] ?? 0) ? 'text-yellow-400' : 'text-gray-300'" class="text-2xl transition-colors hover:text-yellow-400">★</button>
                </div>
                <textarea v-model="comments[item.product_id]" rows="2" :placeholder="`Comment on ${item.product_name} (optional)`" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-green-400" />
              </div>
            </div>
          </div>
          <div class="px-6 pb-6 flex gap-3">
            <button @click="showRatingModal = false" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
            <button @click="submitAllReviews" :disabled="submittingReview || !hasAnyRating" class="flex-1 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 disabled:opacity-50 text-white text-sm font-bold transition-colors">
              {{ submittingReview ? 'Submitting...' : 'Submit Reviews' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import GuestLayout from '../../../../components/layout/GuestLayout.vue'

const route = useRoute()
const { get, post, put } = useApi()

const loading          = ref(true)
const order            = ref<any>(null)
const cancelling       = ref(false)
const showRatingModal  = ref(false)
const submittingReview = ref(false)
const ratings          = ref<Record<number, number>>({})
const comments         = ref<Record<number, string>>({})
const reviewedProductIds = ref<Set<number>>(new Set())

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
  { key: 'pending',    label: 'Order\nReceived' },
  { key: 'preparing',  label: 'Preparing' },
  { key: 'in_transit', label: 'On the\nWay' },
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
  const orderIdx = statusOrder.indexOf(order.value?.status)
  const stepIdx  = statusOrder.indexOf(key)
  return stepIdx < orderIdx
}

const isStepActive = (key: string) => {
  const s = order.value?.status
  if (key === 'pending')    return ['pending', 'confirmed'].includes(s)
  if (key === 'preparing')  return ['preparing', 'ready'].includes(s)
  if (key === 'in_transit') return s === 'in_transit'
  if (key === 'delivered')  return s === 'delivered'
  return false
}

const allReviewed = computed(() =>
  order.value?.items?.every((i: any) => reviewedProductIds.value.has(i.product_id))
)

const hasAnyRating = computed(() =>
  order.value?.items?.some((i: any) =>
    !reviewedProductIds.value.has(i.product_id) && (ratings.value[i.product_id] ?? 0) > 0
  )
)

const setRating = (productId: number, star: number) => {
  ratings.value = { ...ratings.value, [productId]: star }
}

const checkExistingReviews = async () => {
  if (!order.value) return
  try {
    for (const item of order.value.items) {
      const res: any = await get('/reviews', { product_id: item.product_id })
      const already = (res.data ?? []).some((r: any) => r.order_id === order.value.id)
      if (already) reviewedProductIds.value = new Set([...reviewedProductIds.value, item.product_id])
    }
  } catch (e) { console.error(e) }
}

const submitAllReviews = async () => {
  if (!order.value || submittingReview.value) return
  submittingReview.value = true
  try {
    for (const item of order.value.items) {
      if (reviewedProductIds.value.has(item.product_id)) continue
      const rating = ratings.value[item.product_id] ?? 0
      if (rating === 0) continue
      await post('/reviews', {
        product_id: item.product_id,
        order_id:   order.value.id,
        rating,
        comment: comments.value[item.product_id] ?? '',
      })
      reviewedProductIds.value = new Set([...reviewedProductIds.value, item.product_id])
    }
    showRatingModal.value = false
  } catch (e) { console.error(e) }
  finally { submittingReview.value = false }
}

const printInvoice = () => {
  const invoice = document.getElementById('obra-invoice')
  if (!invoice) return
  const original = document.body.innerHTML
  document.body.innerHTML = invoice.innerHTML
  window.print()
  document.body.innerHTML = original
  window.location.reload()
}

const etaEmoji = (status: string) => {
  const map: Record<string, string> = { confirmed: '✅', preparing: '🧑‍🍳', ready: '📦', in_transit: '🛵' }
  return map[status] ?? '📦'
}

const etaLabel = (status: string) => {
  const map: Record<string, string> = {
    confirmed: 'Order confirmed by seller!', preparing: 'Seller is preparing your order',
    ready: 'Order ready — waiting for a driver', in_transit: 'Driver is on the way!',
  }
  return map[status] ?? ''
}

const etaTime = (status: string) => {
  const map: Record<string, string> = {
    confirmed: '45 – 60 minutes', preparing: '45 – 60 minutes',
    ready: '30 – 45 minutes', in_transit: '30 – 45 minutes',
  }
  return map[status] ?? ''
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
    if (res?.status === 'delivered') await checkExistingReviews()
  } catch (e) {
    console.error(e)
    order.value = null
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
@media print {
  #obra-invoice { display: block !important; }
}
</style>