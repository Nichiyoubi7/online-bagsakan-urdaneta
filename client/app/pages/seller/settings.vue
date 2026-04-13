<template>
  <SellerLayout title="Settings" subtitle="Manage your account preferences">

    <div class="flex flex-col gap-6 max-w-2xl">

      <!-- Change Password -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-black text-gray-800 mb-1">Change Password</h3>
        <p class="text-xs text-gray-400 mb-4">Make sure your account is secure</p>
        <div class="flex flex-col gap-4">

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
            <input
              v-model="passwordForm.current"
              type="password"
              placeholder="Enter current password"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
            <input
              v-model="passwordForm.new"
              type="password"
              placeholder="At least 8 characters"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
            <input
              v-model="passwordForm.confirm"
              type="password"
              placeholder="Repeat new password"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 text-gray-800 text-sm outline-none focus:border-green-500 transition-colors"
            />
          </div>

          <div v-if="passwordSuccess" class="text-sm text-green-600 bg-green-50 border border-green-200 px-4 py-3 rounded-xl">
            ✅ Password changed successfully!
          </div>
          <div v-if="passwordError" class="text-sm text-red-600 bg-red-50 border border-red-200 px-4 py-3 rounded-xl">
            {{ passwordError }}
          </div>

          <button
            @click="changePassword"
            :disabled="savingPassword"
            class="w-fit bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors"
          >
            {{ savingPassword ? 'Saving...' : 'Change Password' }}
          </button>
        </div>
      </div>

      <!-- Notification Preferences -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-black text-gray-800 mb-1">Notifications</h3>
        <p class="text-xs text-gray-400 mb-4">Choose what you want to be notified about</p>
        <div class="flex flex-col gap-4">
          <div v-for="notif in notifications" :key="notif.key" class="flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-gray-800">{{ notif.label }}</p>
              <p class="text-xs text-gray-400">{{ notif.description }}</p>
            </div>
            <button
              @click="notif.enabled = !notif.enabled"
              :class="['relative w-11 h-6 rounded-full transition-colors duration-200', notif.enabled ? 'bg-green-500' : 'bg-gray-200']"
            >
              <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200', notif.enabled ? 'translate-x-5' : 'translate-x-0']" />
            </button>
          </div>
        </div>
      </div>

      <!-- Danger Zone -->
      <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-6">
        <h3 class="text-base font-black text-red-600 mb-1">Danger Zone</h3>
        <p class="text-xs text-gray-400 mb-4">These actions are irreversible. Please be careful.</p>
        <div class="flex flex-col gap-3">
          <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
            <div>
              <p class="text-sm font-semibold text-gray-800">Deactivate Store</p>
              <p class="text-xs text-gray-400">Your store will be hidden from customers</p>
            </div>
            <button class="text-xs font-bold px-4 py-2 rounded-xl border border-red-300 text-red-600 hover:bg-red-100 transition-colors">
              Deactivate
            </button>
          </div>
        </div>
      </div>

    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import SellerLayout from '../../components/seller/layout/SellerLayout.vue'

const { put } = useApi()

const passwordForm = ref({ current: '', new: '', confirm: '' })
const savingPassword = ref(false)
const passwordSuccess = ref(false)
const passwordError = ref('')

const notifications = reactive([
  { key: 'new_order',   label: 'New Orders',       description: 'Get notified when a customer places an order', enabled: true  },
  { key: 'cancelled',   label: 'Cancelled Orders',  description: 'Get notified when an order is cancelled',      enabled: true  },
  { key: 'review',      label: 'New Reviews',       description: 'Get notified when a customer leaves a review', enabled: true  },
  { key: 'promotions',  label: 'Promotions',        description: 'Receive tips and promotional announcements',   enabled: false },
])

const changePassword = async () => {
  passwordError.value = ''
  passwordSuccess.value = false

  if (!passwordForm.value.current) { passwordError.value = 'Please enter your current password.'; return }
  if (passwordForm.value.new.length < 8) { passwordError.value = 'New password must be at least 8 characters.'; return }
  if (passwordForm.value.new !== passwordForm.value.confirm) { passwordError.value = 'Passwords do not match.'; return }

  savingPassword.value = true
  try {
    await put('/profile', {
      current_password:      passwordForm.value.current,
      password:              passwordForm.value.new,
      password_confirmation: passwordForm.value.confirm,
    })
    passwordSuccess.value = true
    passwordForm.value = { current: '', new: '', confirm: '' }
    setTimeout(() => { passwordSuccess.value = false }, 3000)
  } catch (e: any) {
    passwordError.value = e?.data?.message || 'Failed to change password. Check your current password.'
  } finally {
    savingPassword.value = false
  }
}
</script>