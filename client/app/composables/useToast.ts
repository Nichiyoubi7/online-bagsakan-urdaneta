import { ref } from 'vue'

export type ToastType = 'success' | 'error' | 'info'

interface Toast {
  id: number
  message: string
  type: ToastType
  image?: string
}

const toasts = ref<Toast[]>([])
let nextId = 0

export const useAppToast = () => {
  const add = (message: string, type: ToastType = 'success', image?: string) => {
    const id = nextId++
    toasts.value.push({ id, message, type, image })
    setTimeout(() => {
      toasts.value = toasts.value.filter(t => t.id !== id)
    }, 3000)
  }

  const remove = (id: number) => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  return { toasts, add, remove }
}