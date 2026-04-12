<template>
  <div class="relative">
    <button
      @click="toggleDropdown"
      class="relative w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      <span
        v-if="unreadCount > 0"
        class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <Transition name="fade">
      <div
        v-if="showDropdown"
        class="absolute right-0 top-11 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
      >
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
          <h3 class="text-sm font-black text-gray-800">Notifications</h3>
          <button
            v-if="unreadCount > 0"
            @click="markAllRead"
            class="text-xs text-green-500 font-semibold hover:text-green-600"
          >
            Mark all read
          </button>
        </div>

        <div class="max-h-80 overflow-y-auto">
          <div v-if="notifications.length === 0" class="flex flex-col items-center justify-center py-10 text-center">
            <span class="text-3xl mb-2">🔔</span>
            <p class="text-sm text-gray-400">No notifications yet</p>
          </div>

          <div
            v-for="notif in notifications"
            :key="notif.id"
            @click="markRead(notif.id)"
            :class="[
              'flex items-start gap-3 px-4 py-3 border-b border-gray-50 cursor-pointer hover:bg-gray-50 transition-colors',
              !notif.is_read ? 'bg-green-50/50' : ''
            ]"
          >
            <span class="text-xl shrink-0 mt-0.5">{{ notif.icon }}</span>
            <div class="flex-1 min-w-0">
              <p :class="['text-sm text-gray-800', !notif.is_read ? 'font-black' : 'font-semibold']">
                {{ notif.title }}
              </p>
              <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ notif.message }}</p>
              <p class="text-[10px] text-gray-400 mt-1">{{ formatTime(notif.created_at) }}</p>
            </div>
            <div v-if="!notif.is_read" class="w-2 h-2 bg-green-500 rounded-full shrink-0 mt-2"></div>
          </div>
        </div>
      </div>
    </Transition>

    <div v-if="showDropdown" class="fixed inset-0 z-40" @click="showDropdown = false" />
  </div>
</template>

<script setup lang="ts">
import { useNotifications } from '~/composables/useNotifications'

const { notifications, unreadCount, showDropdown, toggleDropdown, markRead, markAllRead } = useNotifications()

const formatTime = (dateStr: string) => {
  if (!dateStr) return ''
  const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 60000)
  if (diff < 1)    return 'Just now'
  if (diff < 60)   return `${diff}m ago`
  if (diff < 1440) return `${Math.floor(diff / 60)}h ago`
  return new Date(dateStr).toLocaleDateString('en-PH', { month: 'short', day: 'numeric' })
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>