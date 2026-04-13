<template>
  <div class="grid grid-cols-3 gap-3 px-4 py-4">
    <div
      v-for="stat in stats"
      :key="stat.label"
      class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 flex flex-col items-center text-center"
    >
      <span class="text-2xl mb-1">{{ stat.icon }}</span>
      <p class="text-lg font-black text-gray-800">{{ stat.value }}</p>
      <p class="text-[10px] text-gray-400 font-medium">{{ stat.label }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  todayEarnings: number
  totalDeliveries: number
  rating: number
}>()

const stats = [
  { icon: '💰', label: "Today's Earnings", value: '₱' + props.todayEarnings.toFixed(0) },
  { icon: '🛵', label: 'Deliveries',       value: String(props.totalDeliveries) },
  { icon: '⭐', label: 'Rating',            value: props.rating.toFixed(1) },
]
</script>