import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

// 定义购物车项类型
export interface CartItem {
  id: string
  name: string
  price: number
  originalPrice?: number
  image: string
  quantity: number
  restaurantId: string
  restaurantName: string
  options?: {
    name: string
    value: string
  }[]
}

export const useCartStore = defineStore('cart', () => {
  // 状态
  const items = ref<CartItem[]>([])

  // Getters
  const totalItems = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0)
  })

  const totalAmount = computed(() => {
    return items.value.reduce((total, item) => total + item.price * item.quantity, 0)
  })

  const isEmpty = computed(() => items.value.length === 0)

  // Actions
  function addItem(item: Omit<CartItem, 'quantity'> & { quantity?: number }) {
    const existingItem = items.value.find((i) => i.id === item.id)

    if (existingItem) {
      existingItem.quantity += item.quantity || 1
    } else {
      items.value.push({
        ...item,
        quantity: item.quantity || 1,
      })
    }
  }

  function updateItemQuantity(id: string, quantity: number) {
    const item = items.value.find((i) => i.id === id)
    if (item) {
      item.quantity = quantity
    }
  }

  function removeItem(id: string) {
    const index = items.value.findIndex((i) => i.id === id)
    if (index !== -1) {
      items.value.splice(index, 1)
    }
  }

  function clearCart() {
    items.value = []
  }

  return {
    items,
    totalItems,
    totalAmount,
    isEmpty,
    addItem,
    updateItemQuantity,
    removeItem,
    clearCart,
  }
})
