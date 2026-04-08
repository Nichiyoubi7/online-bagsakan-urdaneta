<template>
  <GuestLayout>

    <!-- Header -->
    <div class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center gap-2 text-sm text-gray-300">
          <NuxtLink to="/" class="hover:text-green-400 transition-colors">Home</NuxtLink>
          <span>/</span>
          <span class="text-white">My Profile</span>
        </div>
        <h1 class="text-white text-2xl font-black mt-1">My Profile</h1>
      </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">

      <!-- Profile Card -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="h-24 bg-gradient-to-r from-green-500 to-green-700" />
        <div class="px-6 pb-6">
          <div class="-mt-10 mb-4 flex items-end justify-between">
            <div class="w-20 h-20 rounded-full bg-green-500 border-4 border-white flex items-center justify-center text-white font-black text-3xl shadow-md">
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <button
              @click="editing = !editing"
              class="px-4 py-2 text-sm font-semibold border border-gray-200 rounded-xl hover:border-green-500 hover:text-green-600 transition-colors"
            >
              {{ editing ? 'Cancel' : '✏️ Edit Profile' }}
            </button>
          </div>
          <h2 class="text-xl font-black text-gray-800">{{ authStore.user?.name }}</h2>
          <p class="text-sm text-gray-400">{{ authStore.user?.email }}</p>
          <span class="inline-flex items-center mt-2 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full capitalize">
            {{ authStore.role || 'customer' }}
          </span>
        </div>
      </div>

      <!-- Edit Form -->
      <div v-if="editing" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h3 class="text-base font-black text-gray-800 mb-4">Edit Profile</h3>
        <div class="flex flex-col gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
            <input
              v-model="form.phone"
              type="text"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
              placeholder="+63 9XX XXX XXXX"
            />
          </div>
          <div v-if="error" class="text-sm text-red-500 bg-red-50 px-4 py-3 rounded-xl">{{ error }}</div>
          <div v-if="success" class="text-sm text-green-600 bg-green-50 px-4 py-3 rounded-xl">✅ Profile updated successfully!</div>
          <button
            @click="handleSave"
            :disabled="saving"
            class="bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-semibold py-3 rounded-xl transition-colors"
          >
            {{ saving ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
      </div>

      <!-- Info Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <p class="text-xs text-gray-400 mb-1">Full Name</p>
          <p class="text-sm font-semibold text-gray-800">{{ authStore.user?.name || '—' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <p class="text-xs text-gray-400 mb-1">Email Address</p>
          <p class="text-sm font-semibold text-gray-800">{{ authStore.user?.email || '—' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <p class="text-xs text-gray-400 mb-1">Phone Number</p>
          <p class="text-sm font-semibold text-gray-800">{{ authStore.user?.phone || 'Not set' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <p class="text-xs text-gray-400 mb-1">Account Type</p>
          <p class="text-sm font-semibold text-gray-800 capitalize">{{ authStore.role || 'Customer' }}</p>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
          <h3 class="text-base font-black text-gray-800">Account</h3>
        </div>
        <div class="flex flex-col divide-y divide-gray-100">
          <NuxtLink to="/customer/orders"
            class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-3">
              <span class="text-xl">📦</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">My Orders</p>
                <p class="text-xs text-gray-400">View and track your orders</p>
              </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </NuxtLink>
          <NuxtLink to="/customer/cart"
            class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-3">
              <span class="text-xl">🛒</span>
              <div>
                <p class="text-sm font-semibold text-gray-800">My Cart</p>
                <p class="text-xs text-gray-400">{{ cartStore.totalCount }} item(s) in cart</p>
              </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </NuxtLink>
          <button
            @click="handleLogout"
            class="flex items-center justify-between px-5 py-4 hover:bg-red-50 transition-colors w-full text-left"
          >
            <div class="flex items-center gap-3">
              <span class="text-xl">🚪</span>
              <div>
                <p class="text-sm font-semibold text-red-500">Log Out</p>
                <p class="text-xs text-gray-400">Sign out of your account</p>
              </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>
      </div>

    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import GuestLayout from '../../../components/layout/GuestLayout.vue'

const authStore = useAuthStore()
const cartStore = useCartStore()
const { put } = useApi()

const editing = ref(false)
const saving  = ref(false)
const error   = ref('')
const success = ref(false)

const form = ref({
  name:  '',
  email: '',
  phone: '',
})

onMounted(() => {
  cartStore.loadFromStorage()
  form.value.name  = authStore.user?.name  || ''
  form.value.email = authStore.user?.email || ''
  form.value.phone = authStore.user?.phone || ''
})

const handleSave = async () => {
  saving.value  = true
  error.value   = ''
  success.value = false
  try {
    await put(`/users/${authStore.user?.id}`, {
      name:  form.value.name,
      email: form.value.email,
      phone: form.value.phone,
    })
    if (authStore.user) {
      authStore.user.name  = form.value.name
      authStore.user.email = form.value.email
      authStore.user.phone = form.value.phone
      localStorage.setItem('obra_user', JSON.stringify(authStore.user))
    }
    success.value = true
    editing.value = false
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
</script>