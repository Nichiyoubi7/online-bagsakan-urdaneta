<template>
  <DriverLayout>
    <div class="p-4">

      <h2 class="text-lg font-black text-gray-800 mb-4">My Profile</h2>

      <!-- Profile Card -->
      <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white mb-5">
        <div class="flex items-center gap-4 mb-4">
          <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-green-600 font-black text-2xl shadow-md">
            {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'D' }}
          </div>
          <div>
            <h2 class="text-xl font-black">{{ authStore.user?.name || 'Driver' }}</h2>
            <p class="text-green-200 text-sm">{{ authStore.user?.email }}</p>
            <div class="flex items-center gap-1 mt-1">
              <span class="text-yellow-300 text-sm">★</span>
              <span class="text-sm font-semibold">4.9</span>
              <span class="text-green-200 text-xs">({{ totalDeliveries }} deliveries)</span>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
          <div class="bg-white/20 rounded-xl p-3 text-center">
            <p class="text-lg font-black">{{ totalDeliveries }}</p>
            <p class="text-xs text-green-200">Deliveries</p>
          </div>
          <div class="bg-white/20 rounded-xl p-3 text-center">
            <p class="text-lg font-black">₱{{ totalEarnings.toLocaleString() }}</p>
            <p class="text-xs text-green-200">Total Earned</p>
          </div>
          <div class="bg-white/20 rounded-xl p-3 text-center">
            <p class="text-lg font-black">4.9⭐</p>
            <p class="text-xs text-green-200">Rating</p>
          </div>
        </div>
      </div>

      <!-- Edit Personal Info -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
        <h3 class="text-sm font-black text-gray-800 mb-4">Personal Information</h3>
        <div class="flex flex-col gap-3">

          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Full Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
            <input
              :value="authStore.user?.email"
              type="email"
              disabled
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 text-gray-400 text-sm bg-gray-50 cursor-not-allowed"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone Number</label>
            <input
              v-model="form.phone"
              type="text"
              placeholder="+63 9XX XXX XXXX"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Vehicle Type</label>
            <select
              v-model="form.vehicle_type"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors bg-white"
            >
              <option value="Motorcycle">🏍️ Motorcycle</option>
              <option value="Bicycle">🚲 Bicycle</option>
              <option value="Car">🚗 Car</option>
              <option value="Tricycle">🛺 Tricycle</option>
            </select>
          </div>

          <div v-if="success" class="text-sm text-green-600 bg-green-50 border border-green-200 px-4 py-3 rounded-xl">
            ✅ Profile updated successfully!
          </div>
          <div v-if="error" class="text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-xl">
            {{ error }}
          </div>

          <button
            @click="saveProfile"
            :disabled="saving"
            class="w-full py-3 bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white text-sm font-bold rounded-xl transition-colors"
          >
            {{ saving ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
      </div>

      <!-- Logout -->
      <button
        @click="handleLogout"
        class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-bold rounded-xl transition-colors border border-red-200"
      >
        🚪 Logout
      </button>

    </div>
  </DriverLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import DriverLayout from '~/components/driver/layout/DriverLayout.vue'

const { get, put } = useApi()
const authStore = useAuthStore()

const loading = ref(true)
const saving = ref(false)
const success = ref(false)
const error = ref('')
const allOrders = ref<any[]>([])

const form = ref({
  name:         '',
  phone:        '',
  vehicle_type: 'Motorcycle',
})

const totalDeliveries = computed(() =>
  allOrders.value.filter(o => o.status === 'delivered').length
)

const totalEarnings = computed(() =>
  allOrders.value
    .filter(o => o.status === 'delivered')
    .reduce((s, o) => s + (Number(o.delivery_fee) || 50), 0)
)

const saveProfile = async () => {
  saving.value = true
  success.value = false
  error.value = ''
  try {
    const res: any = await put('/profile', {
      name:  form.value.name,
      phone: form.value.phone,
    })
    authStore.user = res.user
    localStorage.setItem('obra_user', JSON.stringify(res.user))
    success.value = true
    setTimeout(() => { success.value = false }, 3000)
  } catch (e: any) {
    error.value = e?.data?.message || 'Failed to update profile.'
  } finally {
    saving.value = false
  }
}

const handleLogout = async () => {
  await authStore.logout()
  navigateTo('/')
}

onMounted(async () => {
  form.value.name         = authStore.user?.name || ''
  form.value.phone        = authStore.user?.phone || ''
  form.value.vehicle_type = authStore.user?.vehicle_type || 'Motorcycle'
  loading.value = true
  try {
    const res: any = await get('/orders', { per_page: 100 })
    allOrders.value = res.data || []
  } catch (e) {
    console.error('Failed to load driver stats', e)
  } finally {
    loading.value = false
  }
})
</script>