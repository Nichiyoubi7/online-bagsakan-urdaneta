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
      <div class="flex flex-col md:flex-row md:items-center justify-between px-5 py-4 border-b border-gray-100 gap-3">
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
          <select
            v-model="roleFilter"
            class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white"
          >
            <option value="all">All Roles</option>
            <option value="customer">Customer</option>
            <option value="seller">Seller</option>
            <option value="driver">Driver</option>
            <option value="staff">Staff</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 6" :key="n" class="h-14 bg-gray-100 rounded-xl animate-pulse" />
      </div>

      <!-- Table Content -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">User</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Role</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Phone</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Joined</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr
              v-for="user in filteredUsers"
              :key="user.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <!-- User -->
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
                    :style="{ backgroundColor: avatarColor(user.name) }"
                  >
                    {{ user.name?.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">{{ user.name }}</p>
                    <p class="text-xs text-gray-400">{{ user.email }}</p>
                  </div>
                </div>
              </td>

              <!-- Role -->
              <td class="px-5 py-4">
                <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', roleClass(userRole(user))]">
                  {{ userRole(user) }}
                </span>
              </td>

              <!-- Phone -->
              <td class="px-5 py-4 text-sm text-gray-600">{{ user.phone || '—' }}</td>

              <!-- Joined -->
              <td class="px-5 py-4 text-xs text-gray-500">{{ formatDate(user.created_at) }}</td>

              <!-- Status -->
              <td class="px-5 py-4">
                <span :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                  user.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                ]">
                  {{ user.status === 'active' ? 'Active' : 'Suspended' }}
                </span>
              </td>

              <!-- Actions -->
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <button
                    @click="toggleUserStatus(user)"
                    :class="[
                      'text-xs font-semibold px-3 py-1.5 rounded-full transition-colors',
                      user.status === 'active'
                        ? 'bg-red-100 hover:bg-red-200 text-red-600'
                        : 'bg-green-100 hover:bg-green-200 text-green-600'
                    ]"
                  >
                    {{ user.status === 'active' ? 'Suspend' : 'Activate' }}
                  </button>
                  <button
                    @click="selectedUser = user"
                    class="text-xs text-green-500 hover:text-green-600 font-semibold"
                  >
                    View
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty -->
      <div v-if="!loading && filteredUsers.length === 0"
        class="flex flex-col items-center justify-center py-16 text-center">
        <span class="text-5xl mb-3">👥</span>
        <p class="text-base font-bold text-gray-700">No users found</p>
      </div>

    </div>

    <!-- User Detail Modal -->
    <Transition name="fade">
      <div v-if="selectedUser" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" @click.self="selectedUser = null">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-black text-gray-800">User Details</h3>
            <button @click="selectedUser = null" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-500">✕</button>
          </div>
          <div class="px-6 py-5 flex flex-col gap-3">
            <div class="flex items-center gap-4 mb-2">
              <div
                class="w-14 h-14 rounded-full flex items-center justify-center text-white font-black text-xl shrink-0"
                :style="{ backgroundColor: avatarColor(selectedUser.name) }"
              >
                {{ selectedUser.name?.charAt(0) }}
              </div>
              <div>
                <p class="font-black text-gray-800 text-base">{{ selectedUser.name }}</p>
                <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold', roleClass(userRole(selectedUser))]">
                  {{ userRole(selectedUser) }}
                </span>
              </div>
            </div>
            <div class="flex flex-col gap-2 text-sm">
              <div class="flex justify-between"><span class="text-gray-500">Email</span><span class="font-semibold text-gray-800">{{ selectedUser.email }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Phone</span><span class="font-semibold text-gray-800">{{ selectedUser.phone || '—' }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Status</span>
                <span :class="['px-2 py-0.5 rounded-full text-xs font-bold', selectedUser.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700']">
                  {{ selectedUser.status }}
                </span>
              </div>
              <div class="flex justify-between"><span class="text-gray-500">Joined</span><span class="font-semibold text-gray-800">{{ formatDate(selectedUser.created_at) }}</span></div>
            </div>
          </div>
        </div>
      </div>
    </Transition>

  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import AdminLayout from '../../../components/admin/layout/AdminLayout.vue'

const { get, put } = useApi()

const search = ref('')
const roleFilter = ref('all')
const loading = ref(true)
const users = ref<any[]>([])
const selectedUser = ref<any>(null)

const loadUsers = async () => {
  loading.value = true
  try {
    const res: any = await get('/users')
    users.value = res.data || []
  } catch (e) {
    console.error('Failed to load users', e)
  } finally {
    loading.value = false
  }
}

onMounted(() => loadUsers())

const userRole = (user: any) =>
  user.roles?.[0]?.name || 'customer'

const stats = computed(() => [
  { icon: '👥', label: 'Total Users',  value: users.value.length },
  { icon: '🛒', label: 'Customers',    value: users.value.filter(u => userRole(u) === 'customer').length },
  { icon: '🏪', label: 'Sellers',      value: users.value.filter(u => userRole(u) === 'seller').length   },
  { icon: '🛵', label: 'Drivers',      value: users.value.filter(u => userRole(u) === 'driver').length   },
])

const filteredUsers = computed(() => {
  let result = [...users.value]
  if (roleFilter.value !== 'all') result = result.filter(u => userRole(u) === roleFilter.value)
  if (search.value) result = result.filter(u =>
    u.name?.toLowerCase().includes(search.value.toLowerCase()) ||
    u.email?.toLowerCase().includes(search.value.toLowerCase())
  )
  return result
})

const roleClass = (role: string) => {
  const map: Record<string, string> = {
    customer: 'bg-blue-100 text-blue-700',
    seller:   'bg-green-100 text-green-700',
    driver:   'bg-orange-100 text-orange-700',
    staff:    'bg-purple-100 text-purple-700',
    admin:    'bg-red-100 text-red-700',
  }
  return map[role] ?? 'bg-gray-100 text-gray-700'
}

const avatarColor = (name: string) => {
  const colors = ['#22c55e', '#3b82f6', '#f97316', '#8b5cf6', '#ec4899']
  return colors[(name?.charCodeAt(0) || 0) % colors.length]
}

const formatDate = (dateStr: string) => {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('en-PH', {
    year: 'numeric', month: 'short', day: 'numeric'
  })
}

const toggleUserStatus = async (user: any) => {
  const newStatus = user.status === 'active' ? 'suspended' : 'active'
  try {
    await put(`/users/${user.id}`, { status: newStatus })
    user.status = newStatus
  } catch (e) {
    console.error('Failed to update user status', e)
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>