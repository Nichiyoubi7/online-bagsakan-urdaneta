<template>
  <div>

    <!-- Tab Headers -->
    <div class="flex items-center border-b border-gray-200 mb-6">
      <button
        v-for="tab in tabs"
        :key="tab.key"
        @click="activeTab = tab.key"
        :class="[
          'px-6 py-3 text-sm font-medium transition-colors border-b-2 -mb-px',
          activeTab === tab.key
            ? 'border-green-500 text-green-600'
            : 'border-transparent text-gray-500 hover:text-gray-700'
        ]"
      >
        {{ tab.label }}
      </button>
    </div>

    <!-- Description Tab -->
    <div v-if="activeTab === 'description'" class="text-sm text-gray-600 leading-relaxed">
      <p class="mb-4">{{ product.description }}</p>
      <ul class="flex flex-col gap-2">
        <li
          v-for="point in product.highlights"
          :key="point"
          class="flex items-start gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 mt-0.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
          </svg>
          <span>{{ point }}</span>
        </li>
      </ul>
    </div>

    <!-- Additional Information Tab -->
    <div v-else-if="activeTab === 'additional'" class="text-sm">
      <table class="w-full">
        <tbody>
          <tr
            v-for="info in product.additionalInfo"
            :key="info.label"
            class="border-b border-gray-100"
          >
            <td class="py-3 pr-6 font-medium text-gray-700 w-32">{{ info.label }}</td>
            <td class="py-3 text-gray-600">{{ info.value }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Customer Feedback Tab -->
    <div v-else-if="activeTab === 'feedback'" class="flex flex-col gap-4">
      <div
        v-for="review in product.reviews"
        :key="review.id"
        class="flex gap-3 pb-4 border-b border-gray-100"
      >
        <!-- Avatar -->
        <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
          {{ review.name.charAt(0) }}
        </div>
        <div class="flex-1">
          <div class="flex items-center justify-between mb-1">
            <p class="text-sm font-semibold text-gray-800">{{ review.name }}</p>
            <p class="text-xs text-gray-400">{{ review.date }}</p>
          </div>
          <!-- Stars -->
          <div class="flex items-center gap-0.5 mb-1">
            <svg
              v-for="star in 5"
              :key="star"
              xmlns="http://www.w3.org/2000/svg"
              class="w-3.5 h-3.5"
              :class="star <= review.rating ? 'text-yellow-400' : 'text-gray-200'"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </div>
          <p class="text-sm text-gray-600">{{ review.comment }}</p>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

defineProps<{
  product: {
    description: string
    highlights: string[]
    additionalInfo: { label: string; value: string }[]
    reviews: {
      id: number
      name: string
      rating: number
      comment: string
      date: string
    }[]
  }
}>()

const activeTab = ref('description')

const tabs = [
  { key: 'description', label: 'Descriptions' },
  { key: 'additional',  label: 'Additional Information' },
  { key: 'feedback',    label: 'Customer Feedback' },
]
</script>