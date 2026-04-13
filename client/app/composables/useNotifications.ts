import { ref, onMounted, onUnmounted } from 'vue'

export const useNotifications = () => {
  const { get, put } = useApi()
  const notifications = ref<any[]>([])
  const unreadCount   = ref(0)
  const showDropdown  = ref(false)
  let   pollInterval: ReturnType<typeof setInterval> | null = null

  const fetchNotifications = async () => {
    try {
      const res: any = await get('/notifications')
      notifications.value = Array.isArray(res) ? res : (res?.data || res || [])
      unreadCount.value   = notifications.value.filter((n: any) => !n.is_read).length
    } catch {}
  }

  const markRead = async (id: number) => {
    try {
      await put(`/notifications/${id}/read`, {})
      const n = notifications.value.find((n: any) => n.id === id)
      if (n) n.is_read = true
      unreadCount.value = notifications.value.filter((n: any) => !n.is_read).length
    } catch {}
  }

  const markAllRead = async () => {
    try {
      await put('/notifications/read-all', {})
      notifications.value.forEach((n: any) => { n.is_read = true })
      unreadCount.value = 0
    } catch {}
  }

  const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value
    if (showDropdown.value) fetchNotifications()
  }

  const startPolling = () => {
    fetchNotifications() 
    pollInterval = setInterval(fetchNotifications, 12000)
  }

  const stopPolling = () => {
    if (pollInterval) clearInterval(pollInterval)
  }

  onMounted(startPolling)
  onUnmounted(stopPolling)

  return { notifications, unreadCount, showDropdown, toggleDropdown, markRead, markAllRead }
}