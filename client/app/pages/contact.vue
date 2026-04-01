<template>
  <GuestLayout>

    <!-- Hero -->
    <div class="bg-[#0f2d1f] text-white py-16">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-green-400 text-xs font-semibold uppercase tracking-widest mb-2">Get In Touch</p>
        <h1 class="text-4xl font-black mb-4">Contact Us</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Have questions or concerns? We'd love to hear from you.</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

        <!-- Contact Info -->
        <div>
          <h2 class="text-xl font-black text-gray-900 mb-6">Reach Us At</h2>
          <div class="flex flex-col gap-5">
            <div v-for="info in contactInfo" :key="info.label" class="flex items-start gap-4">
              <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <span v-html="info.icon"></span>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-800">{{ info.label }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ info.value }}</p>
              </div>
            </div>
          </div>

          <!-- Map placeholder -->
          <div class="mt-8 bg-green-50 rounded-2xl h-52 flex items-center justify-center border border-green-100">
            <div class="text-center">
              <svg class="w-10 h-10 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <p class="text-sm text-green-600 font-medium">Urdaneta City, Pangasinan</p>
              <p class="text-xs text-gray-400 mt-1">Philippines</p>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
          <h2 class="text-xl font-black text-gray-900 mb-6">Send a Message</h2>
          <div class="flex flex-col gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Full Name</label>
              <input v-model="form.name" type="text" placeholder="Juan dela Cruz" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-green-500 bg-gray-50" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Email Address</label>
              <input v-model="form.email" type="email" placeholder="juan@email.com" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-green-500 bg-gray-50" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Subject</label>
              <input v-model="form.subject" type="text" placeholder="How can we help?" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-green-500 bg-gray-50" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Message</label>
              <textarea v-model="form.message" rows="4" placeholder="Write your message here..." class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-green-500 bg-gray-50 resize-none"></textarea>
            </div>
            <button @click="submitForm" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition-all text-sm">
              Send Message
            </button>
            <p v-if="submitted" class="text-center text-sm text-green-600 font-medium">✅ Message sent! We'll get back to you soon.</p>
          </div>
        </div>

      </div>
    </div>

  </GuestLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import GuestLayout from '~/components/layout/GuestLayout.vue'

const submitted = ref(false)
const form = ref({ name: '', email: '', subject: '', message: '' })

const contactInfo = [
  {
    label: 'Address',
    value: 'Urdaneta City, Pangasinan, Philippines',
    icon: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
  },
  {
    label: 'Phone',
    value: '+63 966 081 8437',
    icon: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>'
  },
  {
    label: 'Email',
    value: 'support@bagsakan.ph',
    icon: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
  },
  {
    label: 'Business Hours',
    value: 'Monday - Saturday, 6:00 AM - 8:00 PM',
    icon: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>'
  },
]

function submitForm() {
  if (!form.value.name || !form.value.email || !form.value.message) return
  submitted.value = true
  form.value = { name: '', email: '', subject: '', message: '' }
  setTimeout(() => { submitted.value = false }, 4000)
}
</script>