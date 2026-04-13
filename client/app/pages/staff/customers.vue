<template>
  <StaffLayout>

    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">Customers</h1>
        <p class="text-sm text-gray-400 mt-0.5">View and manage customer accounts</p>
      </div>
      <input
        v-model="search"
        placeholder="Search customer..."
        class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white text-gray-600 w-56 outline-none focus:border-green-500"
      />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex flex-col gap-3">
      <div v-for="n in 5" :key="n" class="bg-white border border-gray-100 rounded-xl h-12 animate-pulse" />
    </div>

    <!-- Table -->
    <div v-else class="bg-white border border-gray-100 rounded-xl p-5 overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Name</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Email</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Phone</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Joined</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Status</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in filteredCustomers" :key="c.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-green-50 text-green-700 rounded-full flex items-center justify-center text-xs font-semibold shrink-0">
                  {{ c.name?.charAt(0) }}
                </div>
                <span class="text-sm text-gray-700">{{ c.name }}</span>
              </div>
            </td>
            <td class="py-3 text-sm text-gray-400">{{ c.email }}</td>
            <td class="py-3 text-sm text-gray-400">{{ c.phone || '—' }}</td>
            <td class="py-3 text-sm text-gray-400">{{ formatDate(c.created_at) }}</td>
            <td class="py-3">
              <span :class="['text-[11px] font-semibold px-2 py-0.5 rounded-full', c.status === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600']">
                {{ c.status }}
              </span>
            </td>
            <td class="py-3">
              <button class="text-[11px] font-semibold px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700">
                {{ c.status === 'active' ? 'Suspend' : 'Activate' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="filteredCustomers.length === 0" class="text-center py-10 text-sm text-gray-400">No customers found</div>
    </div>

  </StaffLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'

const { get } = useApi()
const search = ref('')
const loading = ref(true)
const customers = ref([])

onMounted(async () => {
  try {
    const res = await get('/users', { role: 'customer' })
    customers.value = res.data || []
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

const filteredCustomers = computed(() =>
  customers.value.filter(c =>
    c.name?.toLowerCase().includes(search.value.toLowerCase()) ||
    c.email?.toLowerCase().includes(search.value.toLowerCase())
  )
)

const formatDate = (dateStr) => {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric'
  })
}
</script>