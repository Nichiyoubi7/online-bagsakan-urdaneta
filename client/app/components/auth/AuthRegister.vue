<template>
  <div class="px-6 py-6 max-h-[80vh] overflow-y-auto">

    <button @click="$emit('back')" class="flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600 mb-4 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      Back
    </button>

    <h2 class="text-2xl font-black text-gray-900 mb-1">Create Account</h2>
    <p class="text-gray-400 text-sm mb-6">Join OBRA Urdaneta today</p>

    <p v-if="presetRole !== 'customer'" class="text-sm text-green-600 font-semibold mb-4 bg-green-50 rounded-xl px-4 py-2">
      Registering as: {{ presetRole === 'seller' ? '🏪 Seller' : '🛵 Driver' }}
    </p>

    <form @submit.prevent="handleRegister" class="flex flex-col gap-4">

      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">Full Name</label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Enter your full name"
          required
          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-400 transition-colors"
        />
      </div>

      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Enter your email"
          required
          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-400 transition-colors"
        />
      </div>

      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">Phone Number <span class="text-gray-400 font-normal">(optional)</span></label>
        <div class="flex gap-2">
          <span class="border border-gray-200 rounded-xl px-3 py-3 text-sm text-gray-500 bg-gray-50">PH +63</span>
          <input
            v-model="form.phone"
            type="tel"
            placeholder="9XX XXX XXXX"
            class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-400 transition-colors"
          />
        </div>
      </div>

      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">Password</label>
        <div class="relative">
          <input
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="At least 8 characters"
            required
            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-400 transition-colors pr-10"
          />
          <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </button>
        </div>
      </div>

      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">Confirm Password</label>
        <input
          v-model="form.password_confirmation"
          :type="showPassword ? 'text' : 'password'"
          placeholder="Repeat your password"
          required
          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-400 transition-colors"
        />
      </div>

      <p v-if="error" class="text-red-500 text-xs">{{ error }}</p>

      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-green-500 hover:bg-green-600 disabled:bg-green-300 text-white font-semibold py-3 rounded-xl transition-colors duration-200"
      >
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
const props = defineProps<{
  presetRole?: string
}>()

const emit = defineEmits<{
  (e: 'back'): void
  (e: 'goto', step: string): void
  (e: 'otp', payload: {
    email: string
    name: string
    phone: string
    password: string
    password_confirmation: string
    role: string
  }): void
}>()

const config = useRuntimeConfig()

const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  role: props.presetRole === 'seller' ? 'seller' : props.presetRole === 'driver' ? 'driver' : 'customer',
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
      role: form.value.role,
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