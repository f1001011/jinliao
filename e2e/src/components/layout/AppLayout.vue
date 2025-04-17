<template>
  <div class="app-layout">
    <!-- 页面内容区域 -->
    <div class="app-layout__content">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <keep-alive :include="cachedViews">
            <component :is="Component" />
          </keep-alive>
        </transition>
      </router-view>
    </div>

    <!-- 底部导航栏 -->
    <div class="app-layout__footer" v-if="showFooter">
      <div
        class="app-layout__nav-item"
        v-for="item in navItems"
        :key="item.name"
        :class="{ active: isActive(item.route) }"
        @click="navigateTo(item.route)"
      >
        <div class="app-layout__nav-icon">
          <i :class="item.icon"></i>
        </div>
        <div class="app-layout__nav-text">{{ item.name }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/modules/user'

interface NavItem {
  name: string
  route: string
  icon: string
}

// 定义属性
interface Props {
  showFooter?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showFooter: true,
})

// 路由实例
const route = useRoute()
const router = useRouter()

// 用户状态
const userStore = useUserStore()
const isLoggedIn = computed(() => userStore.isLoggedIn)

// 缓存的视图
const cachedViews = ref<string[]>(['Home'])

// 导航项
const navItems = ref<NavItem[]>([
  { name: 'Home', route: '/', icon: 'fas fa-home' },
  { name: 'Categories', route: '/categories', icon: 'fas fa-th-large' },
  { name: 'Profile', route: '/profile', icon: 'fas fa-user' },
])

// 判断导航项是否激活
const isActive = (path: string): boolean => {
  if (path === '/') {
    return route.path === '/'
  }
  return route.path.startsWith(path)
}

// 导航方法
const navigateTo = (path: string): void => {
  // 如果用户点击了"我的"但未登录，则跳转到登录页面
  if (path === '/profile' && !isLoggedIn.value) {
    navigateToLogin()
    return
  }
  router.push(path)
}

// 登录/登出方法
const navigateToLogin = () => {
  router.push('/login')
}

const handleLogout = () => {
  userStore.logout()
  router.push('/')
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.app-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;

  &__content {
    flex: 1;
    padding-bottom: 60px; // 为底部导航预留空间
  }

  &__footer {
    @include fixed(auto, 0, 0, 0);
    height: 60px;
    background-color: $white;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-around;
    align-items: center;
    z-index: $z-index-fixed;
  }

  &__nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: $spacing-xs;
    width: 33.33%;
    cursor: pointer;
    color: $gray-600;

    &.active {
      color: $primary;
    }
  }

  &__nav-icon {
    font-size: $font-size-md;
    margin-bottom: $spacing-xxs;
  }

  &__nav-text {
    font-size: $font-size-xxs;
    font-weight: $font-weight-medium;
  }
}

// 页面过渡动画
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
