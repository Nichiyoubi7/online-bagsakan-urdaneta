<template>
  <GuestLayout>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
      <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full">

        <!-- Step 1: Pay via GCash -->
        <div v-if="step === 1">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-3xl">📱</span>
            </div>
            <h1 class="text-xl font-black text-gray-900 mb-1">Pay via GCash</h1>
            <p class="text-sm text-gray-400">Order #{{ orderId }}</p>
          </div>

          <!-- GCash Details -->
          <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6">
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">GCash Number</span>
                <div class="flex items-center gap-2">
                  <span class="text-base font-black text-blue-700">09660818437</span>
                  <button @click="copyNumber" class="text-xs text-blue-500 hover:underline">Copy</button>
                </div>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Account Name</span>
                <span class="text-base font-semibold text-gray-800">OBRA Bagsakan</span>
              </div>
              <div class="flex items-center justify-between border-t border-blue-200 pt-3">
                <span class="text-sm font-semibold text-gray-700">Amount to Pay</span>
                <span class="text-2xl font-black text-blue-700">₱{{ orderTotal }}</span>
              </div>
            </div>
          </div>

          <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 mb-6">
            <p class="text-xs text-yellow-700">
              <span class="font-bold">Important:</span> Send the exact amount of
              <span class="font-bold">₱{{ orderTotal }}</span> to avoid verification failure.
              Take a screenshot of your GCash receipt after payment.
            </p>
          </div>

          <button
            @click="step = 2"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors"
          >
            I've Sent the Payment →
          </button>

          <button
            @click="$router.push('/customer/orders')"
            class="w-full mt-3 text-sm text-gray-400 hover:text-gray-600"
          >
            Pay later
          </button>
        </div>

        <!-- Step 2: Upload Receipt -->
        <div v-if="step === 2">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-3xl">📸</span>
            </div>
            <h1 class="text-xl font-black text-gray-900 mb-1">Upload GCash Receipt</h1>
            <p class="text-sm text-gray-400">Take a screenshot of your GCash receipt and upload it here</p>
          </div>

          <!-- Upload area -->
          <div
            class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center cursor-pointer hover:border-green-400 transition-colors mb-4"
            @click="triggerFileInput"
          >
            <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg" class="hidden" @change="handleFileSelect" />
            <div v-if="!selectedFile">
              <span class="text-4xl mb-2 block">📄</span>
              <p class="text-sm font-semibold text-gray-700">Tap to upload receipt screenshot</p>
              <p class="text-xs text-gray-400 mt-1">JPG or PNG — max 5MB</p>
            </div>
            <div v-else class="flex items-center justify-center gap-3">
              <img :src="previewUrl" class="w-24 h-24 object-cover rounded-xl" />
              <div class="text-left">
                <p class="text-sm font-semibold text-gray-800">{{ selectedFile.name }}</p>
                <p class="text-xs text-gray-400">{{ (selectedFile.size / 1024).toFixed(0) }} KB</p>
                <button @click.stop="selectedFile = null; previewUrl = null" class="text-xs text-red-400 mt-1">Remove</button>
              </div>
            </div>
          </div>

          <!-- Error message -->
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-4">
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <button
            @click="submitReceipt"
            :disabled="!selectedFile || verifying"
            class="w-full bg-green-500 hover:bg-green-600 disabled:bg-gray-300 text-white font-bold py-3 rounded-xl transition-colors"
          >
            {{ verifying ? 'AI is verifying your receipt...' : 'Submit Receipt for Verification' }}
          </button>

          <button @click="step = 1" class="w-full mt-3 text-sm text-gray-400 hover:text-gray-600">
            ← Back
          </button>
        </div>

        <!-- Step 3: Verifying -->
        <div v-if="step === 3" class="text-center py-8">
          <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
            <span class="text-4xl">🤖</span>
          </div>
          <h1 class="text-xl font-black text-gray-900 mb-2">AI Verifying Receipt...</h1>
          <p class="text-sm text-gray-400 mb-2">Our AI is reading your GCash receipt</p>
          <p class="text-xs text-gray-300">This usually takes 5-10 seconds</p>
          <div class="mt-6 flex justify-center gap-1">
            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0ms" />
            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 150ms" />
            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 300ms" />
          </div>
        </div>

        <!-- Step 4: Success -->
        <div v-if="step === 4" class="text-center py-4">
          <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
          <h1 class="text-2xl font-black text-gray-900 mb-2">Payment Verified! 🎉</h1>
          <p class="text-sm text-gray-400 mb-1">Your GCash payment has been verified by AI</p>
          <p class="text-sm text-gray-400 mb-6">The seller has been notified to prepare your order</p>

          <div v-if="verificationResult" class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 text-left">
            <p class="text-xs font-bold text-green-700 mb-2">Verification Details:</p>
            <div class="flex flex-col gap-1">
              <div class="flex justify-between text-xs">
                <span class="text-gray-500">Amount Found</span>
                <span class="font-semibold text-gray-800">{{ verificationResult.amount_found }}</span>
              </div>
              <div class="flex justify-between text-xs">
                <span class="text-gray-500">Reference No.</span>
                <span class="font-semibold text-gray-800">{{ verificationResult.ref_no }}</span>
              </div>
              <div class="flex justify-between text-xs">
                <span class="text-gray-500">Date</span>
                <span class="font-semibold text-gray-800">{{ verificationResult.transaction_date }}</span>
              </div>
            </div>
          </div>

          <NuxtLink
            to="/customer/orders"
            class="w-full block bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-colors"
          >
            View My Orders
          </NuxtLink>
        </div>

        <!-- Step 5: Failed -->
        <div v-if="step === 5" class="text-center py-4">
          <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </div>
          <h1 class="text-xl font-black text-gray-900 mb-2">Verification Failed</h1>
          <p class="text-sm text-gray-400 mb-2">{{ error }}</p>
          <div v-if="verificationResult" class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-left">
            <p class="text-xs font-bold text-red-700 mb-2">AI Analysis:</p>
            <p class="text-xs text-red-600">{{ verificationResult.reason }}</p>
          </div>
          <button
            @click="step = 2; selectedFile = null; previewUrl = null; error = ''"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors mb-3"
          >
            Try Again
          </button>
          <NuxtLink to="/customer/orders" class="w-full block text-sm text-gray-400 hover:text-gray-600">
            Go to My Orders
          </NuxtLink>
        </div>

      </div>
    </div>
  </GuestLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import GuestLayout from '../../components/layout/GuestLayout.vue'

const route  = useRoute()
const config = useRuntimeConfig()

const orderId          = route.query.order_id
const orderTotal       = route.query.total
const step             = ref(1)
const fileInput        = ref<HTMLInputElement | null>(null)
const selectedFile     = ref<File | null>(null)
const previewUrl       = ref<string | null>(null)
const verifying        = ref(false)
const error            = ref('')
const verificationResult = ref<any>(null)

const triggerFileInput = () => fileInput.value?.click()

const handleFileSelect = (e: Event) => {
  const input = e.target as HTMLInputElement
  if (input.files?.[0]) {
    selectedFile.value = input.files[0]
    previewUrl.value   = URL.createObjectURL(input.files[0])
  }
}

const copyNumber = () => {
  navigator.clipboard.writeText('09660818437')
  alert('GCash number copied!')
}

const submitReceipt = async () => {
  if (!selectedFile.value) return
  verifying.value = true
  error.value     = ''
  step.value      = 3

  try {
    const formData = new FormData()
    formData.append('receipt', selectedFile.value)

    const token = localStorage.getItem('obra_token')
    const res   = await fetch(`${config.public.apiBase}/orders/${orderId}/upload-receipt`, {
      method:  'POST',
      headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
      body:    formData,
    })

    const data = await res.json()

    if (res.ok && data.verified) {
      verificationResult.value = data.result
      step.value = 4
    } else {
      error.value              = data.message || 'Verification failed.'
      verificationResult.value = data.result
      step.value               = 5
    }
  } catch (e: any) {
    error.value = 'Network error. Please try again.'
    step.value  = 5
  } finally {
    verifying.value = false
  }
}
</script>