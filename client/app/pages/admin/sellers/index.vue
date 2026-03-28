<template>
  <AdminLayout title="Sellers" subtitle="Manage and approve sellers">

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3"
        :class="stat.highlight ? 'border-yellow-300 bg-yellow-50' : ''"
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

      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-black text-gray-800">All Sellers</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input v-model="search" type="text" placeholder="Search sellers..." class="bg-transparent text-sm outline-none text-gray-700 w-36"/>
          </div>
          <select v-model="statusFilter" class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white">
            <option value="all">All Status</option>
            <option value="Active">Active</option>
            <option value="Pending">Pending</option>
            <option value="Suspended">Suspended</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Seller</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Store Name</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Products</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Orders</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Rating</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="seller in filteredSellers" :key="seller.id" class="hover:bg-gray-50 transition-colors">

              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-sm shrink-0">
                    {{ seller.name.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">{{ seller.name }}</p>
                    <p class="text-xs text-gray-400">{{ seller.email }}</p>
                  </div>
                </div>
              </td>

              <td class="px-6 py-4 text-sm text-gray-700">{{ seller.store }}</td>
              <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ seller.products }}</td>
              <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ seller.orders }}</td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <span class="text-yellow-400 text-sm">⭐</span>
                  <span class="text-sm font-semibold text-gray-800">{{ seller.rating }}</span>
                </div>
              </td>

              <td class="px-6 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', sellerStatusClass(seller.status)]">
                  {{ seller.status }}
                </span>
              </td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <button
                    v-if="seller.status === 'Pending'"
                    @click="approveSeller(seller.id)"
                    class="text-xs bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors"
                  >
                    Approve
                  </button>
                  <button
                    v-if="seller.status === 'Pending'"
                    @click="rejectSeller(seller.id)"
                    class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                  >
                    Reject
                  </button>
                  <button
                    v-if="seller.status === 'Active'"
                    @click="suspendSeller(seller.id)"
                    class="text-xs bg-yellow-100 hover:bg-yellow-200 text-yellow-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                  >
                    Suspend
                  </button>
                  <button class="text-xs text-green-500 hover:text-green-600 font-semibold">View</button>
                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const search = ref('')
const statusFilter = ref('all')

const sellers = ref([
  { id: 1, name: 'Mang Bert',    email: 'bert@gmail.com',   store: "Mang Bert's Wet Market",  products: 48, orders: 284, rating: 4.8, status: 'Active'    },
  { id: 2, name: 'Aling Nena',   email: 'nena@gmail.com',   store: "Aling Nena's Fish Hub",   products: 32, orders: 198, rating: 4.9, status: 'Active'    },
  { id: 3, name: 'Kuya Jun',     email: 'jun@gmail.com',    store: "Kuya Jun's Vegetable",    products: 25, orders: 165, rating: 4.7, status: 'Active'    },
  { id: 4, name: 'Lola Caring',  email: 'caring@gmail.com', store: "Lola Caring's Kakanin",   products: 15, orders: 142, rating: 4.9, status: 'Active'    },
  { id: 5, name: 'Tatay Ben',    email: 'ben@gmail.com',    store: "Tatay Ben's Fruit Stand", products: 20, orders: 118, rating: 4.5, status: 'Active'    },
  { id: 6, name: 'Pedro Reyes',  email: 'pedro@gmail.com',  store: "Pedro's Fresh Catch",     products: 0,  orders: 0,   rating: 0,   status: 'Pending'   },
  { id: 7, name: 'Rosa Cruz',    email: 'rosa@gmail.com',   store: "Rosa's Organic Farm",     products: 0,  orders: 0,   rating: 0,   status: 'Pending'   },
  { id: 8, name: 'Carlos Santos',email: 'carlos@gmail.com', store: "Carlos Meat Shop",        products: 10, orders: 45,  rating: 3.2, status: 'Suspended' },
])

const stats = computed(() => [
  { icon: '🏪', label: 'Total Sellers',  value: sellers.value.length,                                       highlight: false },
  { icon: '✅', label: 'Active',          value: sellers.value.filter(s => s.status === 'Active').length,    highlight: false },
  { icon: '⏳', label: 'Pending Approval',value: sellers.value.filter(s => s.status === 'Pending').length,   highlight: true  },
  { icon: '🚫', label: 'Suspended',       value: sellers.value.filter(s => s.status === 'Suspended').length, highlight: false },
])

const filteredSellers = computed(() => {
  let result = [...sellers.value]
  if (statusFilter.value !== 'all') result = result.filter(s => s.status === statusFilter.value)
  if (search.value) result = result.filter(s =>
    s.name.toLowerCase().includes(search.value.toLowerCase()) ||
    s.store.toLowerCase().includes(search.value.toLowerCase())
  )
  return result
})

const sellerStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    Active:    'bg-green-100 text-green-700',
    Pending:   'bg-yellow-100 text-yellow-700',
    Suspended: 'bg-red-100 text-red-700',
  }
  return classes[status] ?? 'bg-gray-100 text-gray-700'
}

const approveSeller  = (id: number) => { const s = sellers.value.find(s => s.id === id); if (s) s.status = 'Active'    }
const rejectSeller   = (id: number) => { const s = sellers.value.find(s => s.id === id); if (s) s.status = 'Suspended' }
const suspendSeller  = (id: number) => { const s = sellers.value.find(s => s.id === id); if (s) s.status = 'Suspended' }
</script>