<template>
  <AccountLayout page-title="Settings">

    <!-- Account Settings -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
      <h3 class="text-base font-black text-gray-800 mb-5">Account Settings</h3>
      <div class="flex flex-col md:flex-row gap-6">
        <div class="flex-1 flex flex-col gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">First Name</label>
            <input v-model="form.firstName" type="text"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Last Name</label>
            <input v-model="form.lastName" type="text"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
            <input v-model="form.email" type="email"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Phone Number</label>
            <input v-model="form.phone" type="text"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors"
              placeholder="+63 9XX XXX XXXX" />
          </div>
          <div v-if="profileSuccess" class="text-sm text-green-600 bg-green-50 px-4 py-3 rounded-xl">✅ Profile updated!</div>
          <div v-if="profileError" class="text-sm text-red-500 bg-red-50 px-4 py-3 rounded-xl">{{ profileError }}</div>
          <button @click="saveProfile" :disabled="savingProfile"
            class="w-fit bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white text-sm font-semibold px-6 py-3 rounded-xl transition-colors">
            {{ savingProfile ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
        <!-- Avatar -->
        <div class="flex flex-col items-center gap-3 shrink-0">
          <div class="w-24 h-24 rounded-full bg-green-500 flex items-center justify-center text-white font-black text-4xl shadow-md">
            {{ authStore.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
          </div>
          <button class="text-xs border border-gray-200 rounded-xl px-4 py-2 text-gray-500 hover:border-green-500 hover:text-green-600 transition-colors">
            Choose Image
          </button>
        </div>
      </div>
    </div>

    <!-- Billing Address -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
      <h3 class="text-base font-black text-gray-800 mb-5">Billing Address</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">First Name</label>
          <input v-model="address.firstName" type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">Last Name</label>
          <input v-model="address.lastName" type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">Company (optional)</label>
          <input v-model="address.company" type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Street Address</label>
        <input v-model="address.street" type="text"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">City</label>
          <input v-model="address.city" type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">Province</label>
          <input v-model="address.province" type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">Zip Code</label>
          <input v-model="address.zip" type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">Email</label>
          <input v-model="address.email" type="email"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">Phone</label>
          <input v-model="address.phone" type="text"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
      </div>
      <button class="w-fit bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-6 py-3 rounded-xl transition-colors">
        Save Changes
      </button>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h3 class="text-base font-black text-gray-800 mb-5">Change Password</h3>
      <div class="flex flex-col gap-4 max-w-md">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1.5">Current Password</label>
          <input v-model="passwords.current" type="password" placeholder="Password"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">New Password</label>
            <input v-model="passwords.new" type="password" placeholder="Password"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1.5">Confirm Password</label>
            <input v-model="passwords.confirm" type="password" placeholder="Password"
              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 transition-colors" />
          </div>
        </div>
        <div v-if="pwError" class="text-sm text-red-500 bg-red-50 px-4 py-3 rounded-xl">{{ pwError }}</div>
        <div v-if="pwSuccess" class="text-sm text-green-600 bg-green-50 px-4 py-3 rounded-xl">✅ Password changed!</div>
        <button @click="changePassword"
          class="w-fit bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-6 py-3 rounded-xl transition-colors">
          Change Password
        </button>
      </div>
    </div>

  </AccountLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AccountLayout from '../../../../components/customer/account/AccountLayout.vue'

const authStore = useAuthStore()
const { put } = useApi()

const savingProfile = ref(false)
const profileSuccess = ref(false)
const profileError = ref('')
const pwError = ref('')
const pwSuccess = ref(false)

const nameParts = (authStore.user?.name || '').split(' ')

const form = ref({
  firstName: nameParts[0] || '',
  lastName:  nameParts.slice(1).join(' ') || '',
  email:     authStore.user?.email || '',
  phone:     authStore.user?.phone || '',
})

const address = ref({
  firstName: nameParts[0] || '',
  lastName:  nameParts.slice(1).join(' ') || '',
  company:   '',
  street:    '',
  city:      'Urdaneta City',
  province:  'Pangasinan',
  zip:       '2428',
  email:     authStore.user?.email || '',
  phone:     authStore.user?.phone || '',
})

const passwords = ref({ current: '', new: '', confirm: '' })

const saveProfile = async () => {
  savingProfile.value = true
  profileError.value = ''
  profileSuccess.value = false
  try {
    const fullName = `${form.value.firstName} ${form.value.lastName}`.trim()
    await put(`/users/${authStore.user?.id}`, {
      name:  fullName,
      email: form.value.email,
      phone: form.value.phone,
    })
    if (authStore.user) {
      authStore.user.name  = fullName
      authStore.user.email = form.value.email
      authStore.user.phone = form.value.phone
      localStorage.setItem('obra_user', JSON.stringify(authStore.user))
    }
    profileSuccess.value = true
  } catch (e: any) {
    profileError.value = e?.data?.message || 'Failed to update profile.'
  } finally {
    savingProfile.value = false
  }
}

const changePassword = () => {
  pwError.value = ''
  pwSuccess.value = false
  if (!passwords.value.current) { pwError.value = 'Enter your current password.'; return }
  if (passwords.value.new.length < 8) { pwError.value = 'New password must be at least 8 characters.'; return }
  if (passwords.value.new !== passwords.value.confirm) { pwError.value = 'Passwords do not match.'; return }
  pwSuccess.value = true
  passwords.value = { current: '', new: '', confirm: '' }
}
</script>