<template>
  <StaffLayout>

    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
      <div>
        <h1 class="text-xl font-semibold text-[#0f2d1f]">Bug Tracker</h1>
        <p class="text-sm text-gray-400 mt-0.5">Log and track platform issues</p>
      </div>
      <button @click="showModal = true" class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition-all">+ Report Bug</button>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" @click.self="showModal = false">
      <div class="bg-white rounded-2xl p-6 w-[440px]">
        <div class="flex items-center justify-between mb-5">
          <span class="font-semibold text-[#0f2d1f]">Report a Bug</span>
          <button @click="showModal = false" class="text-gray-400 text-lg">✕</button>
        </div>
        <div class="mb-4">
          <label class="block text-[11px] uppercase tracking-wide text-gray-400 font-semibold mb-1.5">Title</label>
          <input v-model="newBug.title" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:border-green-500" placeholder="Short bug description" />
        </div>
        <div class="mb-4">
          <label class="block text-[11px] uppercase tracking-wide text-gray-400 font-semibold mb-1.5">Page / Area</label>
          <input v-model="newBug.page" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:border-green-500" placeholder="e.g. Customer Checkout" />
        </div>
        <div class="mb-4">
          <label class="block text-[11px] uppercase tracking-wide text-gray-400 font-semibold mb-1.5">Priority</label>
          <select v-model="newBug.priority" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
        </div>
        <div class="mb-5">
          <label class="block text-[11px] uppercase tracking-wide text-gray-400 font-semibold mb-1.5">Description</label>
          <textarea v-model="newBug.description" rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:border-green-500 resize-none" placeholder="Describe the bug..."></textarea>
        </div>
        <button @click="submitBug" class="w-full bg-green-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-green-700 transition-all">Submit Bug</button>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-xl p-5">
      <table class="w-full">
        <thead>
          <tr>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Priority</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Title</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Page</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Status</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Reported</th>
            <th class="text-left text-[11px] uppercase tracking-wide text-gray-400 font-semibold pb-3 border-b border-gray-50">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="bug in bugs" :key="bug.id" class="border-b border-gray-50 last:border-0">
            <td class="py-3">
              <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize"
                :class="{
                  'bg-red-50 text-red-600': bug.priority === 'high',
                  'bg-yellow-50 text-yellow-700': bug.priority === 'medium',
                  'bg-gray-100 text-gray-500': bug.priority === 'low',
                }">{{ bug.priority }}</span>
            </td>
            <td class="py-3 text-sm text-gray-700">{{ bug.title }}</td>
            <td class="py-3 text-sm text-gray-400">{{ bug.page }}</td>
            <td class="py-3">
              <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize"
                :class="{
                  'bg-red-50 text-red-600': bug.status === 'open',
                  'bg-yellow-50 text-yellow-700': bug.status === 'in-progress',
                  'bg-green-50 text-green-700': bug.status === 'resolved',
                }">{{ bug.status }}</span>
            </td>
            <td class="py-3 text-sm text-gray-400">{{ bug.date }}</td>
            <td class="py-3">
              <select class="text-[11px] border border-gray-200 rounded-lg px-2 py-1 bg-white text-gray-600" @change="updateStatus(bug, $event.target.value)">
                <option value="open"        :selected="bug.status === 'open'">Open</option>
                <option value="in-progress" :selected="bug.status === 'in-progress'">In Progress</option>
                <option value="resolved"    :selected="bug.status === 'resolved'">Resolved</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </StaffLayout>
</template>

<script setup>
import StaffLayout from '~/components/staff/layout/StaffLayout.vue'
import { ref } from 'vue'

const showModal = ref(false)
const newBug = ref({ title: '', page: '', priority: 'medium', description: '' })

const bugs = ref([
  { id: 1, title: 'Checkout fails on mobile',   page: 'Cart',            priority: 'high',   status: 'open',        date: 'Mar 27' },
  { id: 2, title: 'Product image not loading',  page: 'Product Page',    priority: 'medium', status: 'in-progress', date: 'Mar 26' },
  { id: 3, title: 'OTP delay on register',      page: 'Auth Modal',      priority: 'high',   status: 'open',        date: 'Mar 25' },
  { id: 4, title: 'Driver map not updating',    page: 'Driver Dashboard',priority: 'low',    status: 'open',        date: 'Mar 24' },
  { id: 5, title: 'Order total miscalculation', page: 'Checkout',        priority: 'high',   status: 'in-progress', date: 'Mar 23' },
  { id: 6, title: 'Search filter not working',  page: 'Shop',            priority: 'medium', status: 'resolved',    date: 'Mar 20' },
])

function submitBug() {
  if (!newBug.value.title) return
  bugs.value.unshift({
    id: Date.now(),
    title: newBug.value.title,
    page: newBug.value.page || 'General',
    priority: newBug.value.priority,
    status: 'open',
    date: 'Mar 27',
  })
  newBug.value = { title: '', page: '', priority: 'medium', description: '' }
  showModal.value = false
}

function updateStatus(bug, status) {
  bug.status = status
}
</script>