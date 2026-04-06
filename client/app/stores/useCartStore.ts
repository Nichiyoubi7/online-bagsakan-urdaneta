import { defineStore } from 'pinia'

export interface CartItem {
  id: number
  productId: number
  name: string
  price: number
  image: string
  quantity: number
  category: string
  sellerId: number
  sellerName: string
}

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])

  const totalItems = computed(() => items.value.reduce((sum, i) => sum + i.quantity, 0))
  const totalPrice = computed(() => items.value.reduce((sum, i) => sum + i.price * i.quantity, 0))

  const loadFromStorage = () => {
    if (import.meta.client) {
      const stored = localStorage.getItem('obra_cart')
      items.value = stored ? JSON.parse(stored) : []
    }
  }

  const saveToStorage = () => {
    if (import.meta.client) {
      localStorage.setItem('obra_cart', JSON.stringify(items.value))
    }
  }

  const addItem = (product: Omit<CartItem, 'id' | 'quantity'>) => {
    const existing = items.value.find(i => i.productId === product.productId)
    if (existing) {
      existing.quantity++
    } else {
      items.value.push({ ...product, id: Date.now(), quantity: 1 })
    }
    saveToStorage()
  }

  const removeItem = (id: number) => {
    items.value = items.value.filter(i => i.id !== id)
    saveToStorage()
  }

  const increaseQty = (id: number) => {
    const item = items.value.find(i => i.id === id)
    if (item) { item.quantity++; saveToStorage() }
  }

  const decreaseQty = (id: number) => {
    const item = items.value.find(i => i.id === id)
    if (item && item.quantity > 1) { item.quantity--; saveToStorage() }
    else if (item && item.quantity === 1) { removeItem(id) }
  }

  const clearCart = () => {
    items.value = []
    saveToStorage()
  }

  // Group items by seller for display
  const cartGroups = computed(() => {
    const groups: Record<number, { id: number; name: string; items: (CartItem & { selected: boolean })[] }> = {}
    items.value.forEach(item => {
      if (!groups[item.sellerId]) {
        groups[item.sellerId] = { id: item.sellerId, name: item.sellerName, items: [] }
      }
      groups[item.sellerId].items.push({ ...item, selected: true })
    })
    return Object.values(groups)
  })

  return {
    items,
    totalItems,
    totalPrice,
    cartGroups,
    loadFromStorage,
    addItem,
    removeItem,
    increaseQty,
    decreaseQty,
    clearCart,
  }
})