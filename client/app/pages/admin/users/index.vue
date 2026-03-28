<template>
  <AdminLayout title="All Users" subtitle="Manage all registered users">

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

      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h3 class="text-base font-black text-gray-800">All Users</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input
              v-model="search"
              type="text"
              placeholder="Search users..."
              class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-36"
            />
          </div>
          <!-- Role Filter -->
          <select
            v-model="roleFilter"
            class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none focus:border-green-500 bg-white"
          >
            <option value="all">All Roles</option>
            <option value="customer">Customer</option>
            <option value="seller">Seller</option>
            <option value="driver">Driver</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>

      <!-- Table Content -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">User</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Role</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Phone</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Joined</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr
              v-for="user in filteredUsers"
              :key="user.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <!-- User -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
                    :style="{ backgroundColor: avatarColor(user.name) }"
                  >
                    {{ user.name.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">{{ user.name }}</p>
                    <p class="text-xs text-gray-400">{{ user.email }}</p>
                  </div>
                </div>
              </td>

              <!-- Role -->
              <td class="px-6 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', roleClass(user.role)]">
                  {{ user.role }}
                </span>
              </td>

              <!-- Phone -->
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.phone }}</td>

              <!-- Joined -->
              <td class="px-6 py-4 text-xs text-gray-500">{{ user.joined }}</td>

              <!-- Status -->
              <td class="px-6 py-4">
                <span
                  :class="[
                    'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                    user.active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                  ]"
                >
                  {{ user.active ? 'Active' : 'Suspended' }}
                </span>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <button
                    @click="toggleUser(user.id)"
                    :class="[
                      'text-xs font-semibold px-3 py-1.5 rounded-full transition-colors',
                      user.active
                        ? 'bg-red-100 hover:bg-red-200 text-red-600'
                        : 'bg-green-100 hover:bg-green-200 text-green-600'
                    ]"
                  >
                    {{ user.active ? 'Suspend' : 'Activate' }}
                  </button>
                  <button class="text-xs text-green-500 hover:text-green-600 font-semibold">
                    View
                  </button>
                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty -->
      <div v-if="filteredUsers.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
        <span class="text-5xl mb-3">👥</span>
        <p class="text-base font-bold text-gray-700">No users found</p>
      </div>

    </div>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const search = ref('')
const roleFilter = ref('all')

const users = ref([
  { id: 1,  name: 'Maria Santos',   email: 'maria@gmail.com',   phone: '+63 912 345 6789', role: 'customer', joined: 'Jan 15, 2026', active: true  },
  { id: 2,  name: 'Juan dela Cruz', email: 'juan@gmail.com',    phone: '+63 923 456 7890', role: 'customer', joined: 'Jan 20, 2026', active: true  },
  { id: 3,  name: 'Mang Bert',      email: 'bert@gmail.com',    phone: '+63 934 567 8901', role: 'seller',   joined: 'Dec 10, 2025', active: true  },
  { id: 4,  name: 'Aling Nena',     email: 'nena@gmail.com',    phone: '+63 945 678 9012', role: 'seller',   joined: 'Dec 15, 2025', active: true  },
  { id: 5,  name: 'Ramon Dela Cruz',email: 'ramon@gmail.com',   phone: '+63 956 789 0123', role: 'driver',   joined: 'Jan 5, 2026',  active: true  },
  { id: 6,  name: 'Jose Mendoza',   email: 'jose@gmail.com',    phone: '+63 967 890 1234', role: 'driver',   joined: 'Jan 8, 2026',  active: true  },
  { id: 7,  name: 'Ana Reyes',      email: 'ana@gmail.com',     phone: '+63 978 901 2345', role: 'customer', joined: 'Feb 1, 2026',  active: false },
  { id: 8,  name: 'Super Admin',    email: 'admin@bagsakan.com',phone: '+63 989 012 3456', role: 'admin',    joined: 'Nov 1, 2025',  active: true  },
])

const stats = computed(() => [
  { icon: '👥', label: 'Total Users',    value: users.value.length                                    },
  { icon: '🛒', label: 'Customers',      value: users.value.filter(u => u.role === 'customer').length },
  { icon: '🏪', label: 'Sellers',        value: users.value.filter(u => u.role === 'seller').length   },
  { icon: '🛵', label: 'Drivers',        value: users.value.filter(u => u.role === 'driver').length   },
])

const filteredUsers = computed(() => {
  let result = [...users.value]
  if (roleFilter.value !== 'all') result = result.filter(u => u.role === roleFilter.value)
  if (search.value) result = result.filter(u =>
    u.name.toLowerCase().includes(search.value.toLowerCase()) ||
    u.email.toLowerCase().includes(search.value.toLowerCase())
  )
  return result
})

const roleClass = (role: string) => {
  const classes: Record<string, string> = {
    customer: 'bg-blue-100 text-blue-700',
    seller:   'bg-green-100 text-green-700',
    driver:   'bg-orange-100 text-orange-700',
    admin:    'bg-red-100 text-red-700',
  }
  return classes[role] ?? 'bg-gray-100 text-gray-700'
}

const avatarColor = (name: string) => {
  const colors = ['#22c55e', '#3b82f6', '#f97316', '#8b5cf6', '#ec4899']
  return colors[name.charCodeAt(0) % colors.length]
}

const toggleUser = (id: number) => {
  const user = users.value.find(u => u.id === id)
  if (user) user.active = !user.active
}
</script>