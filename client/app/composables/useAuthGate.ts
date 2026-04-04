import { ref } from 'vue'

const showAuthModal = ref(false)
const pendingAction = ref<(() => void) | null>(null)

export const useAuthGate = () => {
  const authStore = useAuthStore()

  const requireAuth = (action?: () => void) => {
    if (authStore.isLoggedIn) {
      action?.()
    } else {
      pendingAction.value = action ?? null
      showAuthModal.value = true
    }
  }

  const completePendingAction = () => {
    if (pendingAction.value) {
      pendingAction.value()
      pendingAction.value = null
    }
  }

  return { requireAuth, showAuthModal, completePendingAction }
}