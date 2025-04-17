import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

// 定义用户类型
export interface User {
  id: string
  name: string
  email: string
  role: 'user' | 'distributor' | 'agent' | 'admin'
  avatar?: string
}

export const useUserStore = defineStore('user', () => {
  // 状态
  const currentUser = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))

  // Getters
  const isLoggedIn = computed(() => !!currentUser.value)
  const isDistributor = computed(() => currentUser.value?.role === 'distributor')
  const isAgent = computed(() => currentUser.value?.role === 'agent')
  const isAdmin = computed(() => currentUser.value?.role === 'admin')

  // Actions
  function setUser(user: User) {
    currentUser.value = user
  }

  function setToken(newToken: string) {
    token.value = newToken
    localStorage.setItem('token', newToken)
  }

  function logout() {
    currentUser.value = null
    token.value = null
    localStorage.removeItem('token')
  }

  function checkAuth() {
    const savedToken = localStorage.getItem('token')
    if (savedToken) {
      token.value = savedToken
      // 在实际应用中，这里会调用API验证token并获取用户数据
      // 为了演示，我们假设token有效，并设置一个默认用户
      if (!currentUser.value) {
        currentUser.value = {
          id: '123',
          name: 'Demo User',
          email: 'user@example.com',
          role: 'user',
          avatar: 'https://i.pravatar.cc/300',
        }
      }
    }
  }

  return {
    currentUser,
    token,
    isLoggedIn,
    isDistributor,
    isAgent,
    isAdmin,
    setUser,
    setToken,
    logout,
    checkAuth,
  }
})
