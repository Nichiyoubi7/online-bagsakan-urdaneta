<template>
  <div class="px-6 py-8">

    <!-- Back -->
    <button
      @click="$emit('back')"
      class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm mb-6 transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      Back
    </button>

    <!-- Icon -->
    <div class="text-5xl mb-4">📱</div>

    <h2 class="text-2xl font-black text-gray-900 mb-1">Enter OTP Code</h2>
    <p class="text-gray-400 text-sm mb-1">We sent a 6-digit code to</p>
    <p class="text-green-600 font-semibold text-sm mb-6">{{ phone }}</p>

    <!-- OTP Boxes -->
    <div class="flex items-center gap-2 justify-center mb-6">
      <input
        v-for="(digit, index) in otp"
        :key="index"
        :ref="el => { if (el) inputs[index] = el as HTMLInputElement }"
        v-model="otp[index]"
        type="text"
        maxlength="1"
        class="w-12 h-12 text-center text-lg font-black border-2 border-gray-200 rounded-xl outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
        @input="handleInput(index)"
        @keydown.backspace="handleBackspace(index)"
      />
    </div>

    <!-- Error -->
    <p v-if="error" class="text-xs text-red-500 bg-red-50 px-3 py-2 rounded-lg mb-4 text-center">
      {{ error }}
    </p>

    <!-- Verify Button -->
    <button
      @click="handleVerify"
      :disabled="otp.join('').length < 6 || loading"
      class="w-full bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-semibold py-3 rounded-xl transition-colors duration-200 flex items-center justify-center gap-2 mb-4"
    >
      <svg v-if="loading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
      </svg>
      {{ loading ? 'Verifying...' : 'Verify & Create Account' }}
    </button>

    <!-- Resend -->
    <div class="text-center">
      <p class="text-xs text-gray-400 mb-1">Didn't receive the code?</p>
      <button
        v-if="resendTimer === 0"
        @click="handleResend"
        class="text-sm text-green-500 font-semibold hover:underline"
      >
        Resend OTP
      </button>
      <p v-else class="text-sm text-gray-400">
        Resend in {{ resendTimer }} seconds
      </p>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

defineProps<{ phone: string }>()

const emit = defineEmits<{
  (e: 'back'): void
  (e: 'success'): void
}>()

const otp = ref(['', '', '', '', '', ''])
const inputs = ref<HTMLInputElement[]>([])
const loading = ref(false)
const error = ref('')
const resendTimer = ref(60)
let countdown: ReturnType<typeof setInterval>

onMounted(() => {
  countdown = setInterval(() => {
    if (resendTimer.value > 0) resendTimer.value--
    else clearInterval(countdown)
  }, 1000)
  setTimeout(() => inputs.value[0]?.focus(), 100)
})

onUnmounted(() => clearInterval(countdown))

const handleInput = (index: number) => {
  if (otp.value[index].length === 1 && index < 5) {
    inputs.value[index + 1]?.focus()
  }
}

const handleBackspace = (index: number) => {
  if (!otp.value[index] && index > 0) {
    inputs.value[index - 1]?.focus()
  }
}

const handleResend = () => {
  resendTimer.value = 60
  countdown = setInterval(() => {
    if (resendTimer.value > 0) resendTimer.value--
    else clearInterval(countdown)
  }, 1000)
}

const handleVerify = async () => {
  loading.value = true
  error.value = ''
  try {
    await new Promise(resolve => setTimeout(resolve, 1000))
    emit('success')
  } catch (e) {
    error.value = 'Invalid OTP code. Please try again.'
    otp.value = ['', '', '', '', '', '']
    inputs.value[0]?.focus()
  } finally {
    loading.value = false
  }
}
</script>