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

    <h2 class="text-2xl font-black text-gray-900 mb-1">Create Account</h2>
    <p class="text-gray-400 text-sm mb-6">Join OBRA Urdaneta today</p>

    <form @submit.prevent="handleRegister" class="flex flex-col gap-4">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Enter your full name"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Enter your email"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">
          Phone Number
          <span class="text-xs text-gray-400 font-normal ml-1">(optional)</span>
        </label>
        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:border-green-500 focus-within:ring-2 focus-within:ring-green-100 transition-all">
          <span class="px-3 py-3 bg-gray-50 text-sm text-gray-500 border-r border-gray-200">🇵🇭 +63</span>
          <input
            v-model="form.phone"
            type="tel"
            placeholder="9XX XXX XXXX"
            class="flex-1 px-3 py-3 text-sm outline-none bg-white"
            maxlength="10"
          />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
        <div class="relative">
          <input
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="At least 8 characters"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all pr-10"
            required
            minlength="8"
          />
          <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
            <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            </svg>
          </button>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
        <input
          v-model="form.password_confirmation"
          :type="showPassword ? 'text' : 'password'"
          placeholder="Repeat your password"
          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all"
          required
          minlength="8"
        />
      </div>

      <p v-if="error" class="text-xs text-red-500 bg-red-50 px-3 py-2 rounded-lg">{{ error }}</p>

      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-semibold py-3 rounded-xl transition-colors duration-200 flex items-center justify-center gap-2"
      >
        <svg v-if="loading" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        {{ loading ? 'Sending verification code...' : 'Continue' }}
      </button>

    </form>

    <p class="text-center text-xs text-gray-400 mt-4">
      Already have an account?
      <button @click="$emit('goto', 'login')" class="text-green-500 font-semibold hover:underline">Log In</button>
    </p>

  </div>
</template>

<script setup lang="ts">
const emit = defineEmits<{
  (e: 'back'): void
  (e: 'goto', step: string): void
  (e: 'otp', payload: { email: string, name: string, phone: string, password: string, password_confirmation: string }): void
}>()

const config = useRuntimeConfig()

const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
})
const showPassword = ref(false)
const loading = ref(false)
const error = ref('')

const handleRegister = async () => {
  loading.value = true
  error.value = ''

  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Passwords do not match.'
    loading.value = false
    return
  }

  try {
    await $fetch('/otp/send', {
      baseURL: config.public.apiBase,
      method: 'POST',
      body: { email: form.value.email },
      headers: { Accept: 'application/json' },
    })

    emit('otp', {
      email: form.value.email,
      name: form.value.name,
      phone: form.value.phone,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
    })
  } catch (e: any) {
    const errors = e?.data?.errors
    if (errors) {
      const first = Object.values(errors)[0] as string[]
      error.value = first[0]
    } else {
      error.value = e?.data?.message || 'Something went wrong. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>