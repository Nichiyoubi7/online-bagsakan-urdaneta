<template>
  <div class="px-6 py-8">

    <button
      @click="$emit('back')"
      class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm mb-6 transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      Back
    </button>

    <div class="text-5xl mb-4">📧</div>

    <h2 class="text-2xl font-black text-gray-900 mb-1">Check your email</h2>
    <p class="text-gray-400 text-sm mb-1">We sent a 6-digit code to</p>
    <p class="text-green-600 font-semibold text-sm mb-6">{{ payload.email }}</p>

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

    <p v-if="error" class="text-xs text-red-500 bg-red-50 px-3 py-2 rounded-lg mb-4 text-center">{{ error }}</p>

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

    <div class="text-center">
      <p class="text-xs text-gray-400 mb-1">Didn't receive the code?</p>
      <button v-if="resendTimer === 0" @click="handleResend" class="text-sm text-green-500 font-semibold hover:underline">
        Resend Code
      </button>
      <p v-else class="text-sm text-gray-400">Resend in {{ resendTimer }} seconds</p>
    </div>

  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  payload: {
    email: string
    name: string
    phone: string
    password: string
    password_confirmation: string
    role: string
  }
}>()

const emit = defineEmits<{
  (e: 'back'): void
  (e: 'success', user: any): void
}>()

const config = useRuntimeConfig()
const authStore = useAuthStore()

const otp = ref(['', '', '', '', '', ''])
const inputs = ref<HTMLInputElement[]>([])
const loading = ref(false)
const error = ref('')
const resendTimer = ref(60)
let countdown: ReturnType<typeof setInterval>

onMounted(() => {
  startCountdown()
  setTimeout(() => inputs.value[0]?.focus(), 100)
})

onUnmounted(() => clearInterval(countdown))

const startCountdown = () => {
  resendTimer.value = 60
  countdown = setInterval(() => {
    if (resendTimer.value > 0) resendTimer.value--
    else clearInterval(countdown)
  }, 1000)
}

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

const handleResend = async () => {
  try {
    await $fetch('/otp/send', {
      baseURL: config.public.apiBase,
      method: 'POST',
      body: { email: props.payload.email },
      headers: { Accept: 'application/json' },
    })
    startCountdown()
  } catch (e: any) {
    error.value = 'Failed to resend OTP. Please try again.'
  }
}

const handleVerify = async () => {
  loading.value = true
  error.value = ''
  try {
    // Step 1: Verify OTP
    await $fetch('/otp/verify', {
      baseURL: config.public.apiBase,
      method: 'POST',
      body: { email: props.payload.email, code: otp.value.join('') },
      headers: { Accept: 'application/json' },
    })

    // Step 2: Register user with correct role
    const res: any = await $fetch('/register', {
      baseURL: config.public.apiBase,
      method: 'POST',
      body: {
        name: props.payload.name,
        email: props.payload.email,
        phone: props.payload.phone ? '+63' + props.payload.phone : null,
        password: props.payload.password,
        password_confirmation: props.payload.password_confirmation,
        role: props.payload.role,
      },
      headers: { Accept: 'application/json' },
    })

    // Step 3: Save to store and localStorage
    authStore.token = res.token
    authStore.user = res.user
    authStore.role = res.role
    if (import.meta.client) {
      localStorage.setItem('obra_token', res.token)
      localStorage.setItem('obra_user', JSON.stringify(res.user))
      localStorage.setItem('obra_role', res.role)
    }

    emit('success', res)
  } catch (e: any) {
    error.value = e?.data?.message || 'Invalid or expired code. Please try again.'
    otp.value = ['', '', '', '', '', '']
    inputs.value[0]?.focus()
  } finally {
    loading.value = false
  }
}
</script>