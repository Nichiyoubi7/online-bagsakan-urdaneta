<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-full w-60 bg-[#0f2d1f] flex flex-col z-40">

      <!-- Brand -->
      <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
        <div class="w-9 h-9 bg-green-500 rounded-lg flex items-center justify-center font-bold text-white text-lg">B</div>
        <div>
          <div class="text-white font-semibold text-sm">Bagsakan</div>
          <div class="text-white/40 text-xs">Staff Panel</div>
        </div>
      </div>

      <!-- Nav -->
      <nav class="flex-1 px-3 py-4 overflow-y-auto">
        <p class="text-white/30 text-[10px] uppercase tracking-widest font-semibold px-2 mb-2">Main</p>
        <NuxtLink
          v-for="item in navItems" :key="item.key"
          :to="item.to"
          class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/60 hover:bg-white/10 hover:text-white transition-all text-sm mb-0.5"
          active-class="bg-green-600 text-white"
        >
          <component :is="item.icon" class="w-4 h-4 shrink-0" />
          <span>{{ item.label }}</span>
          <span v-if="item.badge" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ item.badge }}</span>
        </NuxtLink>

        <p class="text-white/30 text-[10px] uppercase tracking-widest font-semibold px-2 mb-2 mt-6">Account</p>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/60 hover:bg-white/10 hover:text-white transition-all text-sm mb-0.5 cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          <span>My Profile</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/60 hover:bg-red-500/20 hover:text-red-400 transition-all text-sm cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span>Logout</span>
        </a>
      </nav>

      <!-- User -->
      <div class="flex items-center gap-3 px-4 py-4 border-t border-white/10">
        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold">JS</div>
        <div>
          <div class="text-white text-xs font-medium">Juan Staff</div>
          <div class="text-white/40 text-[11px]">staff@bagsakan.ph</div>
        </div>
      </div>
    </aside>

    <!-- Page Content -->
    <div class="ml-60 flex-1 p-8">
      <slot />
    </div>
  </div>
</template>

<script setup>
const navItems = [
  { key: 'overview',   label: 'Overview',          to: '/staff/dashboard',  badge: null,
    icon: defineComponent({ template: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>` }) },
  { key: 'orders',     label: 'Orders',            to: '/staff/orders',     badge: 12,
    icon: defineComponent({ template: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>` }) },
  { key: 'customers',  label: 'Customers',         to: '/staff/customers',  badge: null,
    icon: defineComponent({ template: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>` }) },
  { key: 'sellers',    label: 'Sellers & Drivers', to: '/staff/sellers',    badge: 3,
    icon: defineComponent({ template: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>` }) },
  { key: 'reports',    label: 'Reports',           to: '/staff/reports',    badge: null,
    icon: defineComponent({ template: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>` }) },
  { key: 'bugs',       label: 'Bug Tracker',       to: '/staff/bugs',       badge: 5,
    icon: defineComponent({ template: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>` }) },
  { key: 'reviews',    label: 'Reviews',           to: '/staff/reviews',    badge: null,
    icon: defineComponent({ template: `<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>` }) },
]
</script>