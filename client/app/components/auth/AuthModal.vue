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
            />

            <AuthOtpVerify
              v-else-if="step === 'otp'"
              :phone="otpPhone"
              @back="step = 'register'"
              @success="handleRegisterSuccess"
            />

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

const step = ref('welcome')
const otpPhone = ref('')

const closeModal = () => {
  emit('update:modelValue', false)
  setTimeout(() => { step.value = 'welcome' }, 300)
}

const handleOtp = (phone: string) => {
  otpPhone.value = phone
  step.value = 'otp'
}

const handleLoginSuccess = () => {
  closeModal()
  navigateTo('/customer')
}

const handleRegisterSuccess = () => {
  closeModal()
  navigateTo('/customer')
}
</script>