<template>
  <StaffLayout>

    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">Sellers & Drivers</h1>
        <p class="text-sm text-gray-400 mt-0.5">Approve accounts and flag issues</p>
      </div>
      <div class="flex gap-1 bg-white border border-gray-200 rounded-xl p-1">
        <button @click="view = 'sellers'"
          :class="view === 'sellers' ? 'bg-[#0f2d1f] text-white' : 'text-gray-400'"
          class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all">Sellers</button>
        <button @click="view = 'drivers'"
          :class="view === 'drivers' ? 'bg-[#0f2d1f] text-white' : 'text-gray-400'"
          class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all">Drivers</button>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex flex-col gap-3">
      <div v-for="n in 4" :key="n" class="bg-white border border-gray-100 rounded-xl h-12 animate-pulse" />
    </div>

    <!-- Table -->
    <div v-else class="bg-white border border-gray-100 rounded-xl p-5 overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Name</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Email</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Phone</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Status</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Joined</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in currentList" :key="user.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-green-50 text-green-700 rounded-full flex items-center justify-center text-xs font-semibold shrink-0">
                  {{ user.name?.charAt(0) }}
                </div>
                <span class="text-sm text-gray-700">{{ user.name }}</span>
              </div>
            </td>
            <td class="py-3 text-sm text-gray-400">{{ user.email }}</td>
            <td class="py-3 text-sm text-gray-400">{{ user.phone || '—' }}</td>
            <td class="py-3">
              <span :class="[
                'text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize',
                user.status === 'active' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700'
              ]">{{ user.status }}</span>
            </td>
            <td class="py-3 text-sm text-gray-400">{{ formatDate(user.created_at) }}</td>
            <td class="py-3">
              <button v-if="user.status !== 'active'"
                class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-green-50 text-green-700">
                Approve
              </button>
              <button v-else
                class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700">
                Flag
              </button>
            </td>
          </tr>
          <tr v-if="currentList.length === 0">
            <td colspan="6" class="py-10 text-center text-sm text-gray-400">No {{ view }} found</td>
          </tr>
        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'

const { get } = useApi()
const view = ref('sellers')
const loading = ref(true)
const sellers = ref([])
const drivers = ref([])

const loadData = async () => {
  loading.value = true
  try {
    const [sellersRes, driversRes] = await Promise.all([
      get('/users', { role: 'seller' }),
      get('/users', { role: 'driver' }),
    ])
    sellers.value = sellersRes.data || []
    drivers.value = driversRes.data || []
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadData())

const currentList = computed(() =>
  view.value === 'sellers' ? sellers.value : drivers.value
)

const formatDate = (dateStr) => {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric'
  })
}
</script>