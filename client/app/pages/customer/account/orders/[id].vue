<template>
  <AccountLayout page-title="Order History">

    <div v-if="loading" class="flex flex-col gap-4">
      <div v-for="n in 3" :key="n" class="bg-white rounded-2xl h-32 animate-pulse" />
    </div>

    <template v-else-if="order">

      <!-- Header -->
      <div class="flex items-center justify-between mb-5">
        <div>
          <h3 class="text-base font-black text-gray-800">
            Order Details
            <span class="text-gray-400 font-normal text-sm ml-2">
              · {{ formatDate(order.created_at) }} · {{ order.items?.length || 0 }} Products
            </span>
          </h3>
        </div>
        <NuxtLink to="/customer/account/orders"
          class="text-sm text-green-500 font-semibold hover:underline">
          ← Back to List
        </NuxtLink>
      </div>

      <!-- Billing + Order Summary -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

          <!-- Billing Address -->
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Billing Address</p>
            <p class="text-sm font-semibold text-gray-800">{{ order.customer?.name }}</p>
            <p class="text-sm text-gray-500">{{ order.delivery_address || 'Urdaneta City, Pangasinan' }}</p>
            <p class="text-sm text-gray-500">{{ order.customer?.email }}</p>
            <p class="text-sm text-gray-500">{{ order.customer?.phone || '—' }}</p>
          </div>

          <!-- Shipping Address -->
          <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Shipping Address</p>
            <p class="text-sm font-semibold text-gray-800">{{ order.customer?.name }}</p>
            <p class="text-sm text-gray-500">{{ order.delivery_address || 'Urdaneta City, Pangasinan' }}</p>
            <p class="text-sm text-gray-500">{{ order.customer?.email }}</p>
          </div>

          <!-- Order Summary -->
          <div class="bg-gray-50 rounded-xl p-4">
            <div class="flex justify-between mb-2 text-sm">
              <span class="text-gray-500">Order ID:</span>
              <span class="font-semibold text-gray-800">#{{ order.id }}</span>
            </div>
            <div class="flex justify-between mb-2 text-sm">
              <span class="text-gray-500">Payment:</span>
              <span class="font-semibold text-gray-800 capitalize">{{ order.payment_method || 'COD' }}</span>
            </div>
            <div class="flex justify-between mb-2 text-sm">
              <span class="text-gray-500">Subtotal:</span>
              <span class="font-semibold text-gray-800">₱{{ Number(order.total).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between mb-2 text-sm">
              <span class="text-gray-500">Shipping:</span>
              <span class="font-semibold text-gray-800">
                {{ order.delivery_type === 'pickup' ? 'Free' : '₱50.00' }}
              </span>
            </div>
            <div class="flex justify-between mt-3 pt-3 border-t border-gray-200">
              <span class="text-sm font-bold text-gray-800">Total</span>
              <span class="text-base font-black text-green-600">₱{{ Number(order.total).toFixed(2) }}</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Progress Tracker -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-8 mb-5">
        <div class="flex items-center justify-between relative">
          <!-- Line -->
          <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 z-0">
            <div class="h-full bg-green-500 transition-all duration-500"
              :style="{ width: progressWidth }" />
          </div>
          <!-- Steps -->
          <div v-for="(step, index) in steps" :key="step.key"
            class="flex flex-col items-center gap-2 z-10 relative">
            <div :class="[
              'w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all',
              isCompleted(step.key)
                ? 'bg-green-500 border-green-500 text-white'
                : isActive(step.key)
                  ? 'bg-white border-green-500 text-green-500'
                  : 'bg-white border-gray-300 text-gray-400'
            ]">
              <svg v-if="isCompleted(step.key)" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              <span v-else>{{ String(index + 1).padStart(2, '0') }}</span>
            </div>
            <span :class="['text-xs font-semibold', isActive(step.key) ? 'text-green-600' : 'text-gray-400']">
              {{ step.label }}
            </span>
          </div>
        </div>
      </div>

      <!-- Items Table -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Product</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Price</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Quantity</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Subtotal</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="item in order.items" :key="item.id" class="hover:bg-gray-50">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                    <img :src="imageMap[item.product_name] || '/images/products/vegetables/Tomato.png'"
                      :alt="item.product_name" class="w-full h-full object-contain p-1" />
                  </div>
                  <span class="text-sm font-semibold text-gray-800">{{ item.product_name }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600">₱{{ Number(item.price).toFixed(2) }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">x{{ item.quantity }}</td>
              <td class="px-6 py-4 text-sm font-bold text-gray-800">₱{{ Number(item.subtotal).toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </template>

    <div v-else class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center py-20 text-center">
      <span class="text-5xl mb-4">📦</span>
      <p class="text-gray-500">Order not found.</p>
      <NuxtLink to="/customer/account/orders" class="mt-4 text-sm text-green-500 font-semibold hover:underline">
        Back to Orders
      </NuxtLink>
    </div>

  </AccountLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import AccountLayout from '../../../../components/customer/account/AccountLayout.vue'

const route = useRoute()
const { get } = useApi()

const loading = ref(true)
const order = ref<any>(null)

const steps = [
  { key: 'pending',   label: 'Order received' },
  { key: 'preparing', label: 'Processing'      },
  { key: 'in_transit',label: 'On the way'      },
  { key: 'delivered', label: 'Delivered'       },
]

const statusOrder = ['pending', 'confirmed', 'preparing', 'ready', 'in_transit', 'delivered']

const currentStepIndex = computed(() =>
  statusOrder.indexOf(order.value?.status || 'pending')
)

const isCompleted = (key: string) =>
  statusOrder.indexOf(key) < currentStepIndex.value

const isActive = (key: string) =>
  key === order.value?.status ||
  (key === 'pending' && ['pending','confirmed'].includes(order.value?.status)) ||
  (key === 'preparing' && order.value?.status === 'preparing') ||
  (key === 'in_transit' && ['ready','in_transit'].includes(order.value?.status))

const progressWidth = computed(() => {
  const idx = currentStepIndex.value
  const pcts = ['0%', '33%', '66%', '100%']
  return pcts[Math.min(idx, 3)] || '0%'
})

const imageMap: Record<string, string> = {
  'Tomato':       '/images/products/vegetables/Tomato.png',
  'Eggplant':     '/images/products/vegetables/eggplant.png',
  'Bitter Gourd': '/images/products/vegetables/bitter_gourd.png',
  'Okra':         '/images/products/vegetables/okra.png',
  'Sitaw':        '/images/products/vegetables/sitaw.png',
  'Kangkong':     '/images/products/vegetables/kangkong.png',
  'Repolyo':      '/images/products/vegetables/repolyo.png',
  'Carrot':       '/images/products/vegetables/carrot.png',
  'Potato':       '/images/products/vegetables/potato.png',
  'Sibuyas':      '/images/products/vegetables/sibuyas.png',
  'Bawang':       '/images/products/vegetables/bawang.png',
  'Banana':       '/images/products/fruits/saging.png',
  'Mango':        '/images/products/fruits/mango.png',
  'Papaya':       '/images/products/fruits/papaya.png',
  'Chicken':      '/images/products/meat/Chicken.png',
  'Pork Meat':    '/images/products/meat/pork_meat.png',
  'Egg':          '/images/products/meat/Egg.png',
  'Rice':         '/images/products/meat/rice.png',
}

onMounted(async () => {
  try {
    const res: any = await get(`/orders/${route.params.id}`)
    order.value = res
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>