<template>
  <AdminLayout title="Sellers" subtitle="Manage and approve sellers">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div
        v-for="stat in stats"
        :key="stat.label"
        :class="[
          'bg-white rounded-2xl border shadow-sm p-4 flex items-center gap-3',
          stat.highlight ? 'border-yellow-300 bg-yellow-50' : 'border-gray-100'
        ]"
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
        <h3 class="text-base font-black text-gray-800">All Sellers</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input v-model="search" type="text" placeholder="Search sellers..."
              class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-36" />
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
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Seller</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Email</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Phone</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Joined</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="seller in filteredSellers" :key="seller.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
                    {{ seller.name?.charAt(0) }}
                  </div>
                  <p class="text-sm font-semibold text-gray-800">{{ seller.name }}</p>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ seller.email }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ seller.phone || '—' }}</td>
              <td class="px-5 py-4 text-xs text-gray-500">{{ formatDate(seller.created_at) }}</td>
              <td class="px-5 py-4">
                <span :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                  seller.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                ]">
                  {{ seller.status === 'active' ? 'Active' : 'Suspended' }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <button
                    @click="toggleStatus(seller)"
                    :class="[
                      'text-xs font-semibold px-3 py-1.5 rounded-full transition-colors',
                      seller.status === 'active'
                        ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-600'
                        : 'bg-green-100 hover:bg-green-200 text-green-600'
                    ]"
                  >
                    {{ seller.status === 'active' ? 'Suspend' : 'Activate' }}
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="!loading && filteredSellers.length === 0"
        class="flex flex-col items-center justify-center py-16 text-center">
        <span class="text-5xl mb-3">🏪</span>
        <p class="text-base font-bold text-gray-700">No sellers found</p>
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
const sellers = ref<any[]>([])

const loadSellers = async () => {
  loading.value = true
  try {
    const res: any = await get('/users', { role: 'seller' })
    sellers.value = res.data || []
  } catch (e) {
    console.error('Failed to load sellers', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadSellers())

const stats = computed(() => [
  { icon: '🏪', label: 'Total Sellers',   value: sellers.value.length,                                            highlight: false },
  { icon: '✅', label: 'Active',           value: sellers.value.filter(s => s.status === 'active').length,        highlight: false },
  { icon: '🚫', label: 'Suspended',        value: sellers.value.filter(s => s.status === 'suspended').length,     highlight: false },
  { icon: '📦', label: 'Total Products',   value: '—', highlight: false },
])

const filteredSellers = computed(() => {
  let result = [...sellers.value]
  if (statusFilter.value !== 'all') result = result.filter(s => s.status === statusFilter.value)
  if (search.value) result = result.filter(s =>
    s.name?.toLowerCase().includes(search.value.toLowerCase()) ||
    s.email?.toLowerCase().includes(search.value.toLowerCase())
  )
  return result
})

const formatDate = (dateStr: string) => {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric'
  })
}

const toggleStatus = async (seller: any) => {
  const newStatus = seller.status === 'active' ? 'suspended' : 'active'
  try {
    await put(`/users/${seller.id}`, { status: newStatus })
    seller.status = newStatus
  } catch (e) {
    console.error('Failed to update seller status', e)
  }
}
</script>