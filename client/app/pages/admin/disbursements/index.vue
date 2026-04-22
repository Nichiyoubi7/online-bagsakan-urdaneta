<template>
  <AdminLayout title="Disbursements" subtitle="Manage seller payment releases">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div v-for="stat in stats" :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <span class="text-2xl">{{ stat.icon }}</span>
        <div>
          <p class="text-xl font-black text-gray-800">{{ stat.value }}</p>
          <p class="text-xs text-gray-400">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Release All Button -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-base font-black text-gray-800">Pending Disbursements</h3>
          <p class="text-sm text-gray-400 mt-0.5">
            {{ pendingCount }} payment(s) waiting to be released to sellers
          </p>
        </div>
        <button
          @click="releaseAll"
          :disabled="pendingCount === 0 || releasing"
          class="flex items-center gap-2 bg-green-500 hover:bg-green-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold px-5 py-2.5 rounded-xl transition-colors text-sm"
        >
          <svg v-if="releasing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
          </svg>
          <span>{{ releasing ? 'Releasing...' : '💸 Release All Payments' }}</span>
        </button>
      </div>

      <!-- Success message -->
      <div v-if="releaseSuccess" class="mt-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
        <p class="text-sm text-green-700 font-semibold">{{ releaseSuccess }}</p>
        <p class="text-xs text-green-500 mt-0.5">All sellers have been notified via their dashboard.</p>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-black text-gray-800">Disbursement Log</h3>
        <div class="flex gap-2">
          <button
            v-for="f in filters"
            :key="f.value"
            @click="activeFilter = f.value"
            :class="['text-xs font-semibold px-3 py-1.5 rounded-full transition-colors',
              activeFilter === f.value
                ? 'bg-green-500 text-white'
                : 'bg-gray-100 text-gray-500 hover:bg-gray-200']"
          >
            {{ f.label }}
          </button>
        </div>
      </div>

      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 5" :key="n" class="h-14 bg-gray-100 rounded-xl animate-pulse"/>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Order</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Seller</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">GCash Number</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Amount</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Released At</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="filteredDisbursements.length === 0">
              <td colspan="7" class="text-center py-10 text-sm text-gray-400">No disbursements found</td>
            </tr>
            <tr v-for="d in filteredDisbursements" :key="d.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm font-semibold text-gray-800">#{{ d.order_id }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ d.seller?.name || '—' }}</td>
              <td class="px-5 py-4 text-sm font-semibold text-blue-600">{{ d.seller_gcash || 'Not registered' }}</td>
              <td class="px-5 py-4 text-sm font-black text-green-600">₱{{ Number(d.amount).toLocaleString() }}</td>
              <td class="px-5 py-4">
                <span :class="['px-2.5 py-1 rounded-full text-xs font-semibold',
                  d.status === 'released'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700']">
                  {{ d.status === 'released' ? 'Released' : 'Pending' }}
                </span>
              </td>
              <td class="px-5 py-4 text-xs text-gray-500">
                {{ d.released_at ? formatDate(d.released_at) : '—' }}
              </td>
              <td class="px-5 py-4">
                <button
                  v-if="d.status === 'pending'"
                  @click="releaseSingle(d.id)"
                  class="text-xs font-semibold text-green-600 hover:text-green-700 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors"
                >
                  Release
                </button>
                <span v-else class="text-xs text-gray-400">Done</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const { get, post } = useApi()

const loading        = ref(true)
const releasing      = ref(false)
const releaseSuccess = ref('')
const disbursements  = ref<any[]>([])
const activeFilter   = ref('all')

const filters = [
  { label: 'All',      value: 'all' },
  { label: 'Pending',  value: 'pending' },
  { label: 'Released', value: 'released' },
]

onMounted(async () => {
  await loadDisbursements()
})

const loadDisbursements = async () => {
  loading.value = true
  try {
    const res: any = await get('/disbursements')
    disbursements.value = res.data || []
  } catch (e) {
    console.error('Failed to load disbursements', e)
  } finally {
    loading.value = false
  }
}

const filteredDisbursements = computed(() => {
  if (activeFilter.value === 'all') return disbursements.value
  return disbursements.value.filter(d => d.status === activeFilter.value)
})

const pendingCount = computed(() =>
  disbursements.value.filter(d => d.status === 'pending').length
)

const stats = computed(() => [
  { icon: '🕐', label: 'Pending', value: disbursements.value.filter(d => d.status === 'pending').length },
  { icon: '✅', label: 'Released', value: disbursements.value.filter(d => d.status === 'released').length },
  { icon: '💸', label: 'Total Released', value: '₱' + disbursements.value.filter(d => d.status === 'released').reduce((s, d) => s + Number(d.amount), 0).toLocaleString() },
  { icon: '🕐', label: 'Pending Amount', value: '₱' + disbursements.value.filter(d => d.status === 'pending').reduce((s, d) => s + Number(d.amount), 0).toLocaleString() },
])

const releaseAll = async () => {
  releasing.value      = true
  releaseSuccess.value = ''
  try {
    const res: any = await post('/disbursements/release-all', {})
    releaseSuccess.value = res.message
    await loadDisbursements()
  } catch (e) {
    console.error('Failed to release disbursements', e)
  } finally {
    releasing.value = false
  }
}

const releaseSingle = async (id: number) => {
  try {
    await post(`/disbursements/${id}/release`, {})
    await loadDisbursements()
  } catch (e) {
    console.error('Failed to release disbursement', e)
  }
}

const formatDate = (d: string) => {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  })
}
</script>