<template>
  <div :class="[
    'page-container',
    { 'page-container--has-header': hasHeader },
    { 'page-container--has-footer': hasFooter },
    { 'page-container--no-padding': noPadding },
  ]">
    <!-- 页面头部 -->
    <div v-if="hasHeader" class="page-container__header">
      <div class="page-container__back" @click="handleBack" v-if="showBack">
        <i class="fas fa-arrow-left"></i>
      </div>
      <h1 class="page-container__title">{{ title }}</h1>
      <div class="page-container__action">
        <slot name="action"></slot>
      </div>
    </div>

    <!-- 页面内容 -->
    <div :class="['page-container__content', { 'page-container__content--scrollable': scrollable }]" ref="contentRef"
      @scroll="handleScroll">
      <div class="page-container__pull-refresh" v-if="pullRefresh">
        <div class="page-container__pull-text" v-show="isPulling">
          {{ isPullingDown ? 'Release to refresh' : 'Pull down to refresh' }}
        </div>
        <div class="page-container__loading" v-show="isRefreshing">
          <i class="fas fa-spinner fa-spin"></i> Refreshing...
        </div>
      </div>

      <div class="page-container__body">
        <slot></slot>
      </div>

      <div class="page-container__load-more" v-if="loadMore && hasMore">
        <div v-if="isLoading" class="page-container__loading">
          <i class="fas fa-spinner fa-spin"></i> Loading more...
        </div>
        <div v-else class="page-container__load-more-text" @click="handleLoadMore">Load more</div>
      </div>

      <div class="page-container__no-more" v-if="loadMore && !hasMore && !noPadding">
        No more data
      </div>
    </div>

    <!-- 页面底部 -->
    <div v-if="hasFooter" class="page-container__footer">
      <slot name="footer"></slot>
    </div>

    <!-- 开发环境用户角色切换工具 -->
    <!-- <div v-if="isDev" class="dev-tools">
      <button @click="switchToRegularUser">模拟普通用户</button>
      <button @click="switchToDistributor">模拟经销商</button>
    </div> -->
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/modules/user'

// 检查开发环境
const isDev = computed(() => {
  try {
    return import.meta.env.DEV
  } catch (e) {
    return false
  }
})

// 定义属性
interface Props {
  title?: string
  showBack?: boolean
  hasHeader?: boolean
  hasFooter?: boolean
  noPadding?: boolean
  scrollable?: boolean
  pullRefresh?: boolean
  loadMore?: boolean
  hasMore?: boolean
  isLoading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  title: '',
  showBack: true,
  hasHeader: true,
  hasFooter: false,
  noPadding: false,
  scrollable: true,
  pullRefresh: false,
  loadMore: false,
  hasMore: true,
  isLoading: false,
})

// 定义事件
const emit = defineEmits<{
  (e: 'refresh'): void
  (e: 'loadMore'): void
  (e: 'scroll', position: { scrollTop: number; scrollHeight: number; clientHeight: number }): void
}>()

// 路由实例
const router = useRouter()
const userStore = useUserStore()

// 内容区域引用
const contentRef = ref<HTMLElement | null>(null)

// 下拉刷新状态
const isPulling = ref(false)
const isPullingDown = ref(false)
const isRefreshing = ref(false)
let startY = 0
let currentY = 0

// 返回上一页
const handleBack = () => {
  router.back()
}

// 处理滚动事件
const handleScroll = (event: Event) => {
  const target = event.target as HTMLElement
  const { scrollTop, scrollHeight, clientHeight } = target

  // 触发滚动事件
  emit('scroll', { scrollTop, scrollHeight, clientHeight })

  // 检测是否滚动到底部，触发加载更多
  if (props.loadMore && !props.isLoading && props.hasMore) {
    if (scrollTop + clientHeight >= scrollHeight - 50) {
      handleLoadMore()
    }
  }
}

// 处理加载更多
const handleLoadMore = () => {
  if (!props.isLoading && props.hasMore) {
    emit('loadMore')
  }
}

// 触摸事件处理
const handleTouchStart = (e: TouchEvent) => {
  if (!props.pullRefresh || isRefreshing.value) return

  const touch = e.touches[0]
  startY = touch.clientY
  currentY = startY
}

const handleTouchMove = (e: TouchEvent) => {
  if (!props.pullRefresh || isRefreshing.value) return

  const touch = e.touches[0]
  currentY = touch.clientY

  // 只有在滚动到顶部时才处理下拉
  if (contentRef.value && contentRef.value.scrollTop === 0) {
    const distance = currentY - startY

    if (distance > 0) {
      isPulling.value = true
      isPullingDown.value = distance > 50
      e.preventDefault() // 阻止默认滚动
    }
  }
}

const handleTouchEnd = () => {
  if (!props.pullRefresh || isRefreshing.value) return

  if (isPulling.value && isPullingDown.value) {
    // 触发刷新
    isRefreshing.value = true
    emit('refresh')

    // 模拟刷新完成
    setTimeout(() => {
      isRefreshing.value = false
      isPulling.value = false
      isPullingDown.value = false
    }, 2000)
  }

  isPulling.value = false
  isPullingDown.value = false
}

// 仅用于开发环境的用户角色切换工具
const switchToRegularUser = () => {
  userStore.setUser({
    id: '456',
    name: '普通用户',
    email: 'user@example.com',
    role: 'user',
    avatar: 'https://i.pravatar.cc/300?img=2',
  })
  userStore.setToken('fake-jwt-token-user')
  console.log('已切换到普通用户角色')
}

const switchToDistributor = () => {
  userStore.setUser({
    id: '123',
    name: '经销商用户',
    email: 'distributor@example.com',
    role: 'distributor',
    avatar: 'https://i.pravatar.cc/300?img=1',
  })
  userStore.setToken('fake-jwt-token-distributor')
  console.log('已切换到经销商角色')
}

// 生命周期钩子
onMounted(() => {
  if (props.pullRefresh && contentRef.value) {
    contentRef.value.addEventListener('touchstart', handleTouchStart)
    contentRef.value.addEventListener('touchmove', handleTouchMove, { passive: false })
    contentRef.value.addEventListener('touchend', handleTouchEnd)
  }
})

onUnmounted(() => {
  if (contentRef.value) {
    contentRef.value.removeEventListener('touchstart', handleTouchStart)
    contentRef.value.removeEventListener('touchmove', handleTouchMove)
    contentRef.value.removeEventListener('touchend', handleTouchEnd)
  }
})
</script>

<style lang="scss" scoped>
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.page-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: $gray-100; // 使用$gray-100替代$background

  // 头部
  &__header {
    height: 56px;
    background-color: $white;
    display: flex;
    align-items: center;
    padding: 0 $spacing-md;
    position: relative;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    z-index: 10;
  }

  &__back {
    font-size: $font-size-md;
    padding: $spacing-xs;
    margin-left: -$spacing-xs;
    cursor: pointer;
    color: $secondary;
  }

  &__title {
    flex: 1;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    text-align: center;
    margin: 0;
    padding: 0 $spacing-md;
    @include text-truncate;
  }

  &__action {
    display: flex;
    align-items: center;
  }

  // 内容区域
  &__content {
    flex: 1;
    position: relative;

    &--scrollable {
      overflow-y: auto;
      -webkit-overflow-scrolling: touch; // 增强iOS滚动体验
    }
  }

  &__body {
    padding: $spacing-md;
  }

  // 下拉刷新
  &__pull-refresh {
    text-align: center;
    height: 50px;
    line-height: 50px;
    margin-top: -50px;
  }

  &__pull-text {
    color: $text-secondary;
    font-size: $font-size-xs;
  }

  &__loading {
    display: flex;
    align-items: center;
    justify-content: center;
    color: $text-secondary;
    font-size: $font-size-xs;
    padding: $spacing-md 0;

    i {
      margin-right: $spacing-xs;
    }
  }

  // 加载更多
  &__load-more {
    text-align: center;
    padding: $spacing-md 0;
  }

  &__load-more-text {
    color: $primary;
    font-size: $font-size-xs;
    cursor: pointer;
    display: inline-block;
    padding: $spacing-xs $spacing-md;

    &:hover {
      background-color: rgba($primary, 0.05);
      border-radius: $border-radius-full;
    }
  }

  &__no-more {
    text-align: center;
    color: $gray-500; // 使用$gray-500替代$text-hint
    font-size: $font-size-xs;
    padding: $spacing-md 0;
  }

  // 底部
  &__footer {
    background-color: $white;
    z-index: 10;
    box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.05);
  }

  // 修饰符样式
  &--has-header &__body {
    padding-top: $spacing-md;
  }

  &--has-footer &__body {
    padding-bottom: $spacing-md;
  }

  &--no-padding &__body {
    padding: 0;
  }
}
</style>
