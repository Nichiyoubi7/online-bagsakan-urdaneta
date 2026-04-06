<template>
  <SellerLayout :title="'Dashboard'" :subtitle="'Welcome back, ' + (authStore.user?.name || 'Seller') + '!'">

    <!-- Shop Setup Banner -->
    <div v-if="!hasShop && !showSetupForm" class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 mb-6 flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <span class="text-3xl">🏪</span>
        <div>
          <p class="text-sm font-bold text-yellow-800">Your shop isn't set up yet!</p>
          <p class="text-xs text-yellow-600">Set up your shop so buyers can find your products.</p>
        </div>
      </div>
      <button
        @click="showSetupForm = true"
        class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 text-sm font-semibold rounded-xl transition-colors shrink-0"
      >
        Set Up Now
      </button>
    </div>

    <!-- Shop Setup Form -->
    <div v-if="showSetupForm" class="bg-white border border-gray-100 rounded-2xl p-6 mb-6 shadow-sm">
      <h3 class="text-base font-black text-gray-800 mb-4">🏪 Set Up Your Shop</h3>
      <div class="flex flex-col gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Shop Name</label>
          <input
            v-model="shopForm.name"
            type="text"
            placeholder="e.g. Aling Nena's Fresh Goods"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Shop Description</label>
          <textarea
            v-model="shopForm.description"
            placeholder="e.g. Fresh vegetables and fruits from our farm"
            rows="3"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all resize-none"
          />
        </div>
        <div class="flex gap-3">
          <button
            @click="saveShop"
            :disabled="shopLoading"
            class="px-5 py-2.5 bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white text-sm font-semibold rounded-xl transition-colors"
          >
            {{ shopLoading ? 'Saving...' : 'Save Shop' }}
          </button>
          <button
            @click="showSetupForm = false"
            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors"
          >
            Cancel
          </button>
        </div>
        <p v-if="shopError" class="text-xs text-red-500">{{ shopError }}</p>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <StatsCard
        v-for="stat in stats"
        :key="stat.label"
        :icon="stat.icon"
        :label="stat.label"
        :value="stat.value"
        :change="stat.change"
        :bg-color="stat.bgColor"
      />
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <NuxtLink
        v-for="action in quickActions"
        :key="action.label"
        :to="action.to"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col items-center gap-2 hover:border-green-400 hover:shadow-md transition-all duration-200 group"
      >
        <span class="text-3xl">{{ action.icon }}</span>
        <span class="text-sm font-semibold text-gray-700 group-hover:text-green-600 transition-colors">
          {{ action.label }}
        </span>
      </NuxtLink>
    </div>

    <!-- Recent Orders + Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Recent Orders -->
      <div class="lg:col-span-2">
        <RecentOrders />
      </div>

      <!-- Top Products + Store Status -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800">Top Products</h3>
            <NuxtLink to="/seller/products" class="text-sm text-green-500 font-semibold">View All →</NuxtLink>
          </div>
          <div class="p-4 flex flex-col gap-3">
            <div v-if="topProducts.length === 0" class="text-center py-6 text-gray-400 text-sm">
              No products yet
            </div>
            <div
              v-for="(product, index) in topProducts"
              :key="product.name"
              class="flex items-center gap-3"
            >
              <span
                :class="[
                  'w-6 h-6 rounded-full flex items-center justify-center text-xs font-black shrink-0',
                  index === 0 ? 'bg-yellow-400 text-white' :
                  index === 1 ? 'bg-gray-300 text-white' :
                  index === 2 ? 'bg-orange-400 text-white' :
                  'bg-gray-100 text-gray-500'
                ]"
              >
                {{ index + 1 }}
              </span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ product.name }}</p>
                <p class="text-xs text-gray-400">₱{{ product.price }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Store Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mt-4">
          <h3 class="text-base font-black text-gray-800 mb-4">Store Status</h3>
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div :class="['w-3 h-3 rounded-full', isOpen ? 'bg-green-500 animate-pulse' : 'bg-gray-300']"></div>
              <span class="text-sm font-semibold text-gray-700">
                {{ isOpen ? 'Store is Open' : 'Store is Closed' }}
              </span>
            </div>
            <button
              @click="isOpen = !isOpen"
              :class="['relative w-12 h-6 rounded-full transition-colors duration-200', isOpen ? 'bg-green-500' : 'bg-gray-200']"
            >
              <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200', isOpen ? 'translate-x-6' : 'translate-x-0']" />
            </button>
          </div>
          <div class="flex flex-col gap-2 text-sm text-gray-500">
            <div class="flex items-center justify-between">
              <span>Total Products</span>
              <span class="font-semibold text-gray-800">{{ topProducts.length }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Total Orders</span>
              <span class="font-semibold text-gray-800">{{ totalOrders }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Pending</span>
              <span class="font-semibold text-yellow-600">{{ pendingOrders }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'
import StatsCard from '../../../components/seller/dashboard/StatsCard.vue'
import RecentOrders from '../../../components/seller/dashboard/RecentOrders.vue'

const { get, post } = useApi()
const authStore = useAuthStore()

const isOpen = ref(true)
const hasShop = ref(false)
const showSetupForm = ref(false)
const shopLoading = ref(false)
const shopError = ref('')
const products = ref<any[]>([])
const orders = ref<any[]>([])

const shopForm = ref({
  name: '',
  description: '',
})

// Check if seller has shop
const checkShop = async () => {
  try {
    const res: any = await get('/seller/shop')
    if (res && res.store_name) {
      hasShop.value = true
    }
  } catch (e) {
    hasShop.value = false
  }
}

const saveShop = async () => {
  shopLoading.value = true
  shopError.value = ''
  try {
    await post('/seller/shop', {
      store_name: shopForm.value.name,
      store_description: shopForm.value.description,
    })
    hasShop.value = true
    showSetupForm.value = false
    // Update auth store user
    if (authStore.user) {
      authStore.user.store_name = shopForm.value.name
    }
  } catch (e: any) {
    shopError.value = e?.data?.message || 'Failed to save shop.'
  } finally {
    shopLoading.value = false
  }
}

const topProducts = computed(() => products.value.slice(0, 5))
const totalOrders = computed(() => orders.value.length)
const pendingOrders = computed(() => orders.value.filter((o: any) => o.status === 'pending').length)
const totalRevenue = computed(() => orders.value.filter((o: any) => o.status === 'delivered').reduce((sum: number, o: any) => sum + o.total, 0))

const stats = computed(() => [
  { icon: '💰', label: "Total Revenue",   value: '₱' + totalRevenue.value.toFixed(2), change: 0, bgColor: '#dcfce7' },
  { icon: '📦', label: 'Total Orders',    value: String(totalOrders.value),            change: 0, bgColor: '#dbeafe' },
  { icon: '🛍️', label: 'Products Listed', value: String(products.value.length),        change: 0, bgColor: '#fef9c3' },
  { icon: '⭐', label: 'Avg. Rating',     value: '4.8',                                change: 0, bgColor: '#fce7f3' },
])

const quickActions = [
  { icon: '➕', label: 'Add Product',  to: '/seller/products/create' },
  { icon: '📋', label: 'View Orders',  to: '/seller/orders' },
  { icon: '📊', label: 'Revenue',      to: '/seller/revenue' },
  { icon: '👤', label: 'Edit Profile', to: '/seller/profile' },
]

onMounted(async () => {
  await checkShop()
  try {
    const productRes: any = await get('/products')
    products.value = productRes.data ?? []
  } catch (e) { console.error(e) }
  try {
    const orderRes: any = await get('/orders')
    orders.value = orderRes.data ?? []
  } catch (e) { console.error(e) }
})
</script>