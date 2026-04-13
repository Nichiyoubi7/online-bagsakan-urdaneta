import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export interface CartItem {
  id: number
  productId: number
  name: string
  image: string
  price: number
  originalPrice?: number
  quantity: number
  category: string
  sellerId: number
  sellerName: string
  selected: boolean
}

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])

  const loadFromStorage = () => {
    if (import.meta.client) {
      const stored = localStorage.getItem('obra_cart')
      if (stored) {
        try { items.value = JSON.parse(stored) } catch { items.value = [] }
      }
    }
  }

  const saveToStorage = () => {
    if (import.meta.client) {
      localStorage.setItem('obra_cart', JSON.stringify(items.value))
    }
  }

  const addItem = (item: Omit<CartItem, 'id' | 'selected'>) => {
    const existing = items.value.find(i => i.productId === item.productId)
    if (existing) {
      existing.quantity += item.quantity
    } else {
      items.value.push({ ...item, id: Date.now(), selected: true })
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
  }

  const toggleSelected = (id: number) => {
    const item = items.value.find(i => i.id === id)
    if (item) { item.selected = !item.selected; saveToStorage() }
  }

  const toggleAll = (selected: boolean) => {
    items.value.forEach(i => i.selected = selected)
    saveToStorage()
  }

  const deleteSelected = () => {
    items.value = items.value.filter(i => !i.selected)
    saveToStorage()
  }

  const clearCart = () => {
    items.value = []
    saveToStorage()
  }

  const totalCount = computed(() =>
    items.value.reduce((sum, i) => sum + i.quantity, 0)
  )

  const totalPrice = computed(() =>
    items.value.reduce((sum, i) => sum + i.price * i.quantity, 0)
  )

  const selectedTotal = computed(() =>
    items.value.filter(i => i.selected).reduce((sum, i) => sum + i.price * i.quantity, 0)
  )

  const selectedCount = computed(() =>
    items.value.filter(i => i.selected).length
  )

  const allSelected = computed(() =>
    items.value.length > 0 && items.value.every(i => i.selected)
  )

  const groupedBySeller = computed(() => {
    const groups: Record<number, { id: number; name: string; items: CartItem[] }> = {}
    items.value.forEach(item => {
      if (!groups[item.sellerId]) {
        groups[item.sellerId] = { id: item.sellerId, name: item.sellerName, items: [] }
      }
      groups[item.sellerId].items.push(item)
    })
    return Object.values(groups)
  })

  return {
    items,
    addItem,
    removeItem,
    increaseQty,
    decreaseQty,
    toggleSelected,
    toggleAll,
    deleteSelected,
    clearCart,
    loadFromStorage,
    saveToStorage,
    totalCount,
    totalPrice,
    selectedTotal,
    selectedCount,
    allSelected,
    groupedBySeller,
  }
})