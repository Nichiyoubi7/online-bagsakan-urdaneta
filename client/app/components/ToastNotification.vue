<template>
  <div class="fixed top-24 right-4 z-[200] flex flex-col gap-2 pointer-events-none">
    <TransitionGroup name="toast">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="pointer-events-auto flex items-center gap-3 bg-white border border-gray-100 shadow-xl rounded-2xl px-4 py-3 min-w-[280px] max-w-[340px]"
      >
        <!-- Icon / Image -->
        <div
          class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 overflow-hidden"
          :class="{
            'bg-green-100': toast.type === 'success',
            'bg-red-100': toast.type === 'error',
            'bg-blue-100': toast.type === 'info',
          }"
        >
          <img
            v-if="toast.image"
            :src="toast.image"
            class="w-full h-full object-contain p-1"
          />
          <span v-else-if="toast.type === 'success'" class="text-green-600 text-lg font-bold">✓</span>
          <span v-else-if="toast.type === 'error'" class="text-red-600 text-lg font-bold">✕</span>
          <span v-else class="text-blue-600 text-lg font-bold">i</span>
        </div>

        <!-- Message -->
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-gray-800">{{ toast.message }}</p>
          <p
            class="text-xs mt-0.5"
            :class="{
              'text-green-500': toast.type === 'success',
              'text-red-500': toast.type === 'error',
              'text-blue-500': toast.type === 'info',
            }"
          >
            {{ toast.type === 'success' ? 'Added to cart ✓' : toast.type === 'error' ? 'Something went wrong' : 'Notice' }}
          </p>
        </div>

        <!-- Close -->
        <button
          @click="remove(toast.id)"
          class="shrink-0 text-gray-300 hover:text-gray-500 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
const { toasts, remove } = useAppToast()
</script>

<style scoped>
.toast-enter-active { transition: all 0.3s ease; }
.toast-leave-active { transition: all 0.2s ease; }
.toast-enter-from { opacity: 0; transform: translateX(100%); }
.toast-leave-to { opacity: 0; transform: translateX(100%); }
</style>