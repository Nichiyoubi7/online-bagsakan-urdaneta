<template>
  <AdminLayout title="Drivers" subtitle="Manage and approve delivery drivers">

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
        <h3 class="text-base font-black text-gray-800">All Drivers</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input v-model="search" type="text" placeholder="Search drivers..." class="bg-transparent text-sm outline-none text-gray-700 w-36"/>
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
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Driver</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Vehicle</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Deliveries</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Rating</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Online</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="driver in filteredDrivers" :key="driver.id" class="hover:bg-gray-50 transition-colors">

              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold text-sm shrink-0">
                    {{ driver.name.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">{{ driver.name }}</p>
                    <p class="text-xs text-gray-400">{{ driver.phone }}</p>
                  </div>
                </div>
              </td>

              <td class="px-6 py-4 text-sm text-gray-700">{{ driver.vehicle }}</td>
              <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ driver.deliveries }}</td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-1">
                  <span class="text-yellow-400 text-sm">⭐</span>
                  <span class="text-sm font-semibold text-gray-800">{{ driver.rating }}</span>
                </div>
              </td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-1.5">
                  <div :class="['w-2 h-2 rounded-full', driver.online ? 'bg-green-500' : 'bg-gray-300']"></div>
                  <span class="text-xs text-gray-500">{{ driver.online ? 'Online' : 'Offline' }}</span>
                </div>
              </td>

              <td class="px-6 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', driverStatusClass(driver.status)]">
                  {{ driver.status }}
                </span>
              </td>

              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <button
                    v-if="driver.status === 'Pending'"
                    @click="approveDriver(driver.id)"
                    class="text-xs bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1.5 rounded-full transition-colors"
                  >
                    Approve
                  </button>
                  <button
                    v-if="driver.status === 'Pending'"
                    @click="rejectDriver(driver.id)"
                    class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                  >
                    Reject
                  </button>
                  <button
                    v-if="driver.status === 'Active'"
                    @click="suspendDriver(driver.id)"
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

const drivers = ref([
  { id: 1, name: 'Ramon Dela Cruz', phone: '+63 956 789 0123', vehicle: 'Motorcycle',  deliveries: 124, rating: 4.9, online: true,  status: 'Active'    },
  { id: 2, name: 'Jose Mendoza',    phone: '+63 967 890 1234', vehicle: 'Motorcycle',  deliveries: 98,  rating: 4.8, online: true,  status: 'Active'    },
  { id: 3, name: 'Carlos Reyes',    phone: '+63 978 901 2345', vehicle: 'Bicycle',     deliveries: 87,  rating: 4.7, online: false, status: 'Active'    },
  { id: 4, name: 'Pedro Santos',    phone: '+63 989 012 3456', vehicle: 'Motorcycle',  deliveries: 76,  rating: 4.6, online: true,  status: 'Active'    },
  { id: 5, name: 'Mario Cruz',      phone: '+63 990 123 4567', vehicle: 'Motorcycle',  deliveries: 65,  rating: 4.5, online: false, status: 'Active'    },
  { id: 6, name: 'Rodel Bautista',  phone: '+63 901 234 5678', vehicle: 'Motorcycle',  deliveries: 0,   rating: 0,   online: false, status: 'Pending'   },
  { id: 7, name: 'Dennis Garcia',   phone: '+63 912 345 6789', vehicle: 'Bicycle',     deliveries: 0,   rating: 0,   online: false, status: 'Pending'   },
  { id: 8, name: 'Edwin Torres',    phone: '+63 923 456 7890', vehicle: 'Motorcycle',  deliveries: 23,  rating: 3.1, online: false, status: 'Suspended' },
])

const stats = computed(() => [
  { icon: '🛵', label: 'Total Drivers',   value: drivers.value.length,                                       highlight: false },
  { icon: '🟢', label: 'Online Now',      value: drivers.value.filter(d => d.online).length,                 highlight: false },
  { icon: '⏳', label: 'Pending Approval',value: drivers.value.filter(d => d.status === 'Pending').length,   highlight: true  },
  { icon: '🚫', label: 'Suspended',       value: drivers.value.filter(d => d.status === 'Suspended').length, highlight: false },
])

const filteredDrivers = computed(() => {
  let result = [...drivers.value]
  if (statusFilter.value !== 'all') result = result.filter(d => d.status === statusFilter.value)
  if (search.value) result = result.filter(d =>
    d.name.toLowerCase().includes(search.value.toLowerCase())
  )
  return result
})

const driverStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    Active:    'bg-green-100 text-green-700',
    Pending:   'bg-yellow-100 text-yellow-700',
    Suspended: 'bg-red-100 text-red-700',
  }
  return classes[status] ?? 'bg-gray-100 text-gray-700'
}

const approveDriver = (id: number) => { const d = drivers.value.find(d => d.id === id); if (d) d.status = 'Active'    }
const rejectDriver  = (id: number) => { const d = drivers.value.find(d => d.id === id); if (d) d.status = 'Suspended' }
const suspendDriver = (id: number) => { const d = drivers.value.find(d => d.id === id); if (d) d.status = 'Suspended' }
</script>