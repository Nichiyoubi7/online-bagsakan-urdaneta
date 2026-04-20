<template>
  <Transition name="fade">
    <div
      v-if="modelValue"
      class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" />

      <Transition name="slide-up">
        <div
          v-if="modelValue"
          class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10"
        >
          <!-- Close Button -->
          <button
            @click="closeModal"
            class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

          <Transition name="fade" mode="out-in">

            <AuthWelcome
              v-if="step === 'welcome'"
              :key="'welcome'"
              @goto="step = $event"
              @goto-register="(role) => { registerRole = role; step = 'register' }"
              @close="closeModal"
            />

            <AuthLogin
              v-else-if="step === 'login'"
              :key="'login'"
              @back="step = 'welcome'"
              @close="closeModal"
              @goto="step = $event"
              @success="handleLoginSuccess"
            />

            <AuthRegister
              v-else-if="step === 'register'"
              :key="'register'"
              :preset-role="registerRole"
              @back="step = 'welcome'"
              @close="closeModal"
              @goto="step = $event"
              @otp="handleOtp"
            />

            <AuthOtpVerify
              v-else-if="step === 'otp'"
              :key="'otp'"
              :payload="otpPayload"
              @back="step = 'register'"
              @success="handleOtpSuccess"
            />

            <AuthShopSetup
              v-else-if="step === 'shop'"
              :key="'shop'"
              :user-data="registeredUser"
              @success="handleShopSuccess"
            />

            <div v-else-if="step === 'success'" :key="'success'" class="p-8 text-center">
              <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <h3 class="text-xl font-black text-gray-900 mb-2">You're in! 🎉</h3>
              <p class="text-sm text-gray-500 mb-6">Your account has been successfully created.</p>
              <button
                @click="closeAndRedirect"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-colors duration-200"
              >
                {{ authStore.role === 'seller' ? 'Go to Dashboard' : 'Start Shopping' }}
              </button>
            </div>

          </Transition>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { navigateTo } from '#app'
import AuthWelcome from './AuthWelcome.vue'
import AuthLogin from './AuthLogin.vue'
import AuthRegister from './AuthRegister.vue'
import AuthOtpVerify from './AuthOtpVerify.vue'
import AuthShopSetup from './AuthShopSetup.vue'
import { useAuthGate } from '~/composables/useAuthGate'

defineProps<{ modelValue: boolean }>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const authStore = useAuthStore()
const { completePendingAction } = useAuthGate()
const step = ref('welcome')
const registerRole = ref('customer')
const otpPayload = ref<any>(null)
const registeredUser = ref<any>(null)

const closeModal = () => {
  emit('update:modelValue', false)
  setTimeout(() => { step.value = 'welcome' }, 300)
}

const closeAndRedirect = () => {
  closeModal()
  const role = authStore.role
  if (role === 'seller') navigateTo('/seller/dashboard')
  else if (role === 'driver') navigateTo('/driver/dashboard')
  else if (role === 'admin') navigateTo('/admin/dashboard')
  else navigateTo('/')
}

const handleOtp = (payload: any) => {
  otpPayload.value = payload
  step.value = 'otp'
}

const handleOtpSuccess = (user: any) => {
  registeredUser.value = user
  if (otpPayload.value?.role === 'seller') {
    step.value = 'shop'
  } else {
    step.value = 'success'
  }
}

const handleShopSuccess = () => {
  step.value = 'success'
}

const handleLoginSuccess = async () => {
  completePendingAction()
  const role = authStore.role
  closeModal()
  if (role === 'seller') navigateTo('/seller/dashboard')
  else if (role === 'admin') navigateTo('/admin/dashboard')
  else if (role === 'driver') navigateTo('/driver/dashboard')
  else navigateTo('/')
}
</script>