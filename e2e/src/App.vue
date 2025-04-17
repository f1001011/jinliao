<script setup lang="ts">
import { onMounted } from 'vue'
import AppLayout from '@/components/layout/AppLayout.vue'
import { useUserStore } from '@/stores/modules/user'

const userStore = useUserStore()

onMounted(() => {
  // 检查用户是否已登录
  userStore.checkAuth()

  // 在控制台显示当前用户角色，便于调试
  if (userStore.isLoggedIn) {
    console.log('User logged in as:', userStore.currentUser?.role)
    console.log('Is distributor:', userStore.isDistributor)
  } else {
    console.log('User not logged in')
  }
})
</script>

<template>
  <AppLayout />
</template>

<style lang="scss">
// 全局样式
</style>
