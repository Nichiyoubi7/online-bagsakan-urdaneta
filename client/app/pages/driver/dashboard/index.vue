<template>
  <DriverLayout>

    <!-- Driver Profile Banner -->
    <div class="bg-gradient-to-br from-green-500 to-green-700 px-4 pt-5 pb-8">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-green-600 font-black text-xl shadow-md">
          R
        </div>
        <div>
          <p class="text-white font-black text-lg">Ramon Dela Cruz</p>
          <div class="flex items-center gap-1">
            <span class="text-yellow-300 text-sm">⭐</span>
            <span class="text-white text-sm font-semibold">4.9</span>
            <span class="text-green-200 text-xs">(124 reviews)</span>
          </div>
        </div>
      </div>

      <!-- Status Bar -->
      <div class="bg-white/20 rounded-2xl px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-2.5 h-2.5 bg-green-300 rounded-full animate-pulse"></div>
          <span class="text-white text-sm font-semibold">You are Online</span>
        </div>
        <span class="text-green-200 text-xs">Ready for deliveries</span>
      </div>
    </div>

    <!-- Stats Bar -->
    <div class="-mt-4">
      <DriverStatsBar />
    </div>

    <!-- Active Delivery -->
    <div v-if="activeDelivery" class="px-4 mb-2">
      <div class="flex items-center gap-2 mb-2">
        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
        <h2 class="text-sm font-black text-gray-800 uppercase tracking-wide">Active Delivery</h2>
      </div>
    </div>

    <DeliveryCard
      v-if="activeDelivery"
      :delivery="activeDelivery"
      :is-new="false"
      @picked-up="handlePickedUp"
      @delivered="handleDelivered"
    />

    <!-- New Requests -->
    <div class="px-4 mb-2 mt-2">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
          <h2 class="text-sm font-black text-gray-800 uppercase tracking-wide">
            New Requests ({{ pendingDeliveries.length }})
          </h2>
        </div>
        <span class="text-xs text-gray-400">Tap to accept</span>
      </div>
    </div>

    <!-- Pending Delivery Cards -->
    <DeliveryCard
      v-for="delivery in pendingDeliveries"
      :key="delivery.id"
      :delivery="delivery"
      :is-new="true"
      @accept="handleAccept"
      @reject="handleReject"
    />

    <!-- No Requests -->
    <div
      v-if="pendingDeliveries.length === 0 && !activeDelivery"
      class="flex flex-col items-center justify-center py-16 text-center px-8"
    >
      <span class="text-5xl mb-3">🛵</span>
      <h3 class="text-base font-bold text-gray-700 mb-1">No deliveries yet</h3>
      <p class="text-sm text-gray-400">Stay online to receive delivery requests</p>
    </div>

    <!-- Today's Completed -->
    <div class="px-4 mt-4 mb-2">
      <h2 class="text-sm font-black text-gray-800 uppercase tracking-wide">
        Completed Today ({{ completedDeliveries.length }})
      </h2>
    </div>

    <DeliveryCard
      v-for="delivery in completedDeliveries"
      :key="delivery.id"
      :delivery="delivery"
      :is-new="false"
    />

  </DriverLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import DriverLayout from '../../../components/driver/layout/DriverLayout.vue'
import DriverStatsBar from '../../../components/driver/dashboard/DriverStatsBar.vue'
import DeliveryCard from '../../../components/driver/dashboard/DeliveryCard.vue'

const deliveries = ref([
  {
    id: '3001',
    time: '2 mins ago',
    seller: "Mang Bert's Wet Market",
    sellerAddress: 'Bagsakan Market, Urdaneta City',
    customer: 'Maria Santos',
    customerAddress: 'Psu Executive Vlg, Urdaneta',
    items: 3,
    distance: '1.2 km',
    earnings: 45,
    status: 'pending',
  },
  {
    id: '3002',
    time: '5 mins ago',
    seller: "Aling Nena's Fish Hub",
    sellerAddress: 'Bagsakan Market, Urdaneta City',
    customer: 'Juan dela Cruz',
    customerAddress: 'Nancamaliran West, Urdaneta',
    items: 2,
    distance: '2.5 km',
    earnings: 65,
    status: 'pending',
  },
  {
    id: '2998',
    time: '1 hr ago',
    seller: "Kuya Jun's Vegetable Stand",
    sellerAddress: 'Bagsakan Market, Urdaneta City',
    customer: 'Rosa Mendoza',
    customerAddress: 'San Vicente, Urdaneta',
    items: 4,
    distance: '1.8 km',
    earnings: 55,
    status: 'delivered',
  },
  {
    id: '2997',
    time: '2 hrs ago',
    seller: "Lola Caring's Kakanin",
    sellerAddress: 'Bagsakan Market, Urdaneta City',
    customer: 'Pedro Cruz',
    customerAddress: 'Nancamaliran East, Urdaneta',
    items: 1,
    distance: '0.9 km',
    earnings: 35,
    status: 'delivered',
  },
])

const activeDelivery = computed(() =>
  deliveries.value.find(d => d.status === 'accepted' || d.status === 'picked_up') ?? null
)

const pendingDeliveries = computed(() =>
  deliveries.value.filter(d => d.status === 'pending')
)

const completedDeliveries = computed(() =>
  deliveries.value.filter(d => d.status === 'delivered')
)

const handleAccept = (id: string) => {
  // Can only accept one at a time
  if (activeDelivery.value) {
    alert('You already have an active delivery!')
    return
  }
  const delivery = deliveries.value.find(d => d.id === id)
  if (delivery) delivery.status = 'accepted'
}

const handleReject = (id: string) => {
  const delivery = deliveries.value.find(d => d.id === id)
  if (delivery) delivery.status = 'rejected'
}

const handlePickedUp = (id: string) => {
  const delivery = deliveries.value.find(d => d.id === id)
  if (delivery) delivery.status = 'picked_up'
}

const handleDelivered = (id: string) => {
  const delivery = deliveries.value.find(d => d.id === id)
  if (delivery) delivery.status = 'delivered'
}
</script>