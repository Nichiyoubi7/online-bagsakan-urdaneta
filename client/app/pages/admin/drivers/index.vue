<template>
  <AdminLayout title="Drivers" subtitle="Manage delivery drivers">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3"
      >
        <span class="text-2xl">{{ stat.icon }}</span>
        <div>
          <p class="text-xl font-black text-gray-800">{{ stat.value }}</p>
          <p class="text-xs text-gray-400">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

      <div class="flex flex-col md:flex-row md:items-center justify-between px-5 py-4 border-b border-gray-100 gap-3">
        <h3 class="text-base font-black text-gray-800">All Drivers</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input v-model="search" type="text" placeholder="Search drivers..."
              class="bg-transparent text-sm outline-none text-gray-700 w-36" />
          </div>
          <select v-model="statusFilter" class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white">
            <option value="all">All Status</option>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
          </select>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 5" :key="n" class="h-14 bg-gray-100 rounded-xl animate-pulse" />
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Driver</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Email</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Phone</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Joined</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="driver in filteredDrivers" :key="driver.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm shrink-0">
                    {{ driver.name?.charAt(0) }}
                  </div>
                  <p class="text-sm font-semibold text-gray-800">{{ driver.name }}</p>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ driver.email }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ driver.phone || '—' }}</td>
              <td class="px-5 py-4 text-xs text-gray-500">{{ formatDate(driver.created_at) }}</td>
              <td class="px-5 py-4">
                <span :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                  driver.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                ]">
                  {{ driver.status === 'active' ? 'Active' : 'Suspended' }}
                </span>
              </td>
              <td class="px-5 py-4">
                <button
                  @click="toggleStatus(driver)"
                  :class="[
                    'text-xs font-semibold px-3 py-1.5 rounded-full transition-colors',
                    driver.status === 'active'
                      ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-600'
                      : 'bg-green-100 hover:bg-green-200 text-green-600'
                  ]"
                >
                  {{ driver.status === 'active' ? 'Suspend' : 'Activate' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="!loading && filteredDrivers.length === 0"
        class="flex flex-col items-center justify-center py-16 text-center">
        <span class="text-5xl mb-3">🛵</span>
        <p class="text-base font-bold text-gray-700">No drivers found</p>
      </div>

    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const { get, put } = useApi()

const search = ref('')
const statusFilter = ref('all')
const loading = ref(true)
const drivers = ref<any[]>([])

const loadDrivers = async () => {
  loading.value = true
  try {
    const res: any = await get('/users', { role: 'driver' })
    drivers.value = res.data || []
  } catch (e) {
    console.error('Failed to load drivers', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadDrivers())

const stats = computed(() => [
  { icon: '🛵', label: 'Total Drivers',  value: drivers.value.length },
  { icon: '✅', label: 'Active',          value: drivers.value.filter(d => d.status === 'active').length },
  { icon: '🚫', label: 'Suspended',       value: drivers.value.filter(d => d.status === 'suspended').length },
  { icon: '📦', label: 'Total Delivered', value: '—' },
])

const filteredDrivers = computed(() => {
  let result = [...drivers.value]
  if (statusFilter.value !== 'all') result = result.filter(d => d.status === statusFilter.value)
  if (search.value) result = result.filter(d =>
    d.name?.toLowerCase().includes(search.value.toLowerCase()) ||
    d.email?.toLowerCase().includes(search.value.toLowerCase())
  )
  return result
})

const formatDate = (dateStr: string) => {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric'
  })
}

const toggleStatus = async (driver: any) => {
  const newStatus = driver.status === 'active' ? 'suspended' : 'active'
  try {
    await put(`/users/${driver.id}`, { status: newStatus })
    driver.status = newStatus
  } catch (e) {
    console.error('Failed to update driver status', e)
  }
}
</script>