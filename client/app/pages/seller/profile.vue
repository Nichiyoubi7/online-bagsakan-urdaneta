<template>
  <SellerLayout title="Profile" subtitle="Manage your seller profile and store info">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Left: Avatar + Quick Info -->
      <div class="flex flex-col gap-4">

        <!-- Avatar Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col items-center text-center">
          <div class="w-24 h-24 bg-green-500 rounded-2xl flex items-center justify-center text-white font-black text-4xl mb-4 shadow-md">
            {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'S' }}
          </div>
          <h2 class="text-lg font-black text-gray-800">{{ authStore.user?.name }}</h2>
          <p class="text-sm text-gray-400">{{ authStore.user?.email }}</p>
          <span class="mt-2 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
            Seller Account
          </span>
        </div>

        <!-- Store Status Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h3 class="text-sm font-black text-gray-800 mb-3">Store Info</h3>
          <div class="flex flex-col gap-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-gray-500">Store Name</span>
              <span class="font-semibold text-gray-800 text-right max-w-[140px] truncate">
                {{ authStore.user?.store_name || '—' }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-500">Phone</span>
              <span class="font-semibold text-gray-800">{{ authStore.user?.phone || '—' }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-500">Location</span>
              <span class="font-semibold text-gray-800">Urdaneta City</span>
            </div>
          </div>
        </div>

      </div>

      <!-- Right: Edit Forms -->
      <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Personal Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h3 class="text-base font-black text-gray-800 mb-4">Personal Information</h3>
          <div class="flex flex-col gap-4">

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
              <input
                v-model="form.name"
                type="text"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
              <input
                v-model="form.email"
                type="email"
                disabled
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 text-gray-400 text-sm outline-none bg-gray-50 cursor-not-allowed"
              />
              <p class="text-xs text-gray-400 mt-1">Email cannot be changed</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
              <input
                v-model="form.phone"
                type="text"
                placeholder="+63 9XX XXX XXXX"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
              />
            </div>

            <div v-if="personalSuccess" class="text-sm text-green-600 bg-green-50 border border-green-200 px-4 py-3 rounded-xl">
              ✅ Personal info updated successfully!
            </div>
            <div v-if="personalError" class="text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-xl">
              {{ personalError }}
            </div>

            <button
              @click="savePersonal"
              :disabled="savingPersonal"
              class="w-fit bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors"
            >
              {{ savingPersonal ? 'Saving...' : 'Save Personal Info' }}
            </button>
          </div>
        </div>

        <!-- Store Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h3 class="text-base font-black text-gray-800 mb-4">Store Information</h3>
          <div class="flex flex-col gap-4">

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Store Name</label>
              <input
                v-model="form.store_name"
                type="text"
                placeholder="e.g. Mang Bert's Wet Market"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1.5">Store Description</label>
              <textarea
                v-model="form.store_description"
                rows="3"
                placeholder="Tell customers what you sell..."
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors resize-none"
              />
            </div>

            <div v-if="storeSuccess" class="text-sm text-green-600 bg-green-50 border border-green-200 px-4 py-3 rounded-xl">
              ✅ Store info updated successfully!
            </div>
            <div v-if="storeError" class="text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-xl">
              {{ storeError }}
            </div>

            <button
              @click="saveStore"
              :disabled="savingStore"
              class="w-fit bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors"
            >
              {{ savingStore ? 'Saving...' : 'Save Store Info' }}
            </button>
          </div>
        </div>

      </div>
    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import SellerLayout from '../../components/seller/layout/SellerLayout.vue'

const { put } = useApi()
const authStore = useAuthStore()

const form = ref({
  name:              '',
  email:             '',
  phone:             '',
  store_name:        '',
  store_description: '',
})

const savingPersonal = ref(false)
const personalSuccess = ref(false)
const personalError = ref('')

const savingStore = ref(false)
const storeSuccess = ref(false)
const storeError = ref('')

onMounted(() => {
  form.value.name              = authStore.user?.name || ''
  form.value.email             = authStore.user?.email || ''
  form.value.phone             = authStore.user?.phone || ''
  form.value.store_name        = authStore.user?.store_name || ''
  form.value.store_description = authStore.user?.store_description || ''
})

const savePersonal = async () => {
  savingPersonal.value = true
  personalSuccess.value = false
  personalError.value = ''
  try {
    const res: any = await put('/profile', {
      name:  form.value.name,
      phone: form.value.phone,
    })
    authStore.user = res.user
    localStorage.setItem('obra_user', JSON.stringify(res.user))
    personalSuccess.value = true
    setTimeout(() => { personalSuccess.value = false }, 3000)
  } catch (e: any) {
    personalError.value = e?.data?.message || 'Failed to update. Please try again.'
  } finally {
    savingPersonal.value = false
  }
}

const saveStore = async () => {
  savingStore.value = true
  storeSuccess.value = false
  storeError.value = ''
  try {
    const res: any = await put('/profile', {
      store_name:        form.value.store_name,
      store_description: form.value.store_description,
    })
    authStore.user = res.user
    localStorage.setItem('obra_user', JSON.stringify(res.user))
    storeSuccess.value = true
    setTimeout(() => { storeSuccess.value = false }, 3000)
  } catch (e: any) {
    storeError.value = e?.data?.message || 'Failed to update. Please try again.'
  } finally {
    savingStore.value = false
  }
}
</script>