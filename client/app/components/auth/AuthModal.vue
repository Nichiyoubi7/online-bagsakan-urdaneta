<template>
  <Transition name="fade">
    <div
      v-if="modelValue"
      class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
      @click.self="closeModal"
    >
      <Transition name="slide-up">
        <div
          v-if="modelValue"
          class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden"
        >

          <!-- Close Button -->
          <button
            @click="closeModal"
            class="absolute top-4 right-4 z-10 w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

          <!-- Steps -->
          <Transition name="fade" mode="out-in">

            <AuthWelcome
              v-if="step === 'welcome'"
              @goto="step = $event"
              @close="closeModal"
            />

            <AuthLogin
              v-else-if="step === 'login'"
              @back="step = 'welcome'"
              @goto="step = $event"
              @close="closeModal"
              @success="handleLoginSuccess"
            />

            <AuthRegister
              v-else-if="step === 'register'"
              @back="step = 'welcome'"
              @goto="step = $event"
              @otp="handleOtp"
              @success="handleRegisterSuccess"
            />

            <AuthOtpVerify
              v-else-if="step === 'otp'"
              :payload="otpPayload"
              @back="step = 'register'"
              @success="handleRegisterSuccess"
            />
            <!-- Success Step -->
            <div v-else-if="step === 'success'" class="px-6 py-12 flex flex-col items-center text-center">
              <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <h2 class="text-2xl font-black text-gray-900 mb-2">Account Created!</h2>
              <p class="text-gray-400 text-sm mb-6">Welcome to OBRA! Your account has been successfully created. You are now logged in.</p>
              <button
                @click="closeAndRedirect"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-colors duration-200"
              >
                Start Shopping
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

defineProps<{ modelValue: boolean }>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const authStore = useAuthStore()
const step = ref('welcome')
const otpPhone = ref('')

const closeModal = () => {
  emit('update:modelValue', false)
  setTimeout(() => { step.value = 'welcome' }, 300)
}

const closeAndRedirect = () => {
  closeModal()
  navigateTo('/')
}

const otpPayload = ref<any>(null)

const handleOtp = (payload: any) => {
  otpPayload.value = payload
  step.value = 'otp'
}

const handleLoginSuccess = () => {
  closeModal()
  const role = authStore.role
  if (role === 'admin') {
    navigateTo('/admin/dashboard')
  } else if (role === 'seller') {
    navigateTo('/seller/dashboard')
  } else if (role === 'driver') {
    navigateTo('/driver/dashboard')
  } else if (role === 'staff') {
    navigateTo('/staff/dashboard')
  } else {
    navigateTo('/')
  }
}

const handleRegisterSuccess = () => {
  step.value = 'success'
}
</script>