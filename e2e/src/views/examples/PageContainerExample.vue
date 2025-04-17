<template>
  <PageContainer
    title="Page Container Example"
    :pullRefresh="true"
    :loadMore="true"
    :hasMore="hasMore"
    :isLoading="isLoading"
    @refresh="handleRefresh"
    @loadMore="handleLoadMore"
  >
    <template #action>
      <i class="fas fa-ellipsis-v" style="padding: 8px"></i>
    </template>

    <div class="example-content">
      <h2>PageContainer Component</h2>
      <p>This is an example of the PageContainer component with various features:</p>

      <div class="feature-list">
        <div class="feature-item">
          <i class="fas fa-arrow-left"></i>
          <span>Back Button Navigation</span>
        </div>
        <div class="feature-item">
          <i class="fas fa-sync-alt"></i>
          <span>Pull to Refresh</span>
        </div>
        <div class="feature-item">
          <i class="fas fa-spinner"></i>
          <span>Load More Functionality</span>
        </div>
        <div class="feature-item">
          <i class="fas fa-heading"></i>
          <span>Customizable Header</span>
        </div>
      </div>

      <div class="card-list">
        <Card v-for="item in items" :key="item.id" :title="item.title" hoverable>
          <p>{{ item.content }}</p>
        </Card>
      </div>
    </div>

    <template #footer>
      <div class="footer-actions">
        <Button block>Action Button</Button>
      </div>
    </template>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import PageContainer from '@/components/layout/PageContainer.vue'
import Card from '@/components/common/Card.vue'
import Button from '@/components/common/Button.vue'

// 示例数据
interface Item {
  id: number
  title: string
  content: string
}

const items = ref<Item[]>([])
const hasMore = ref(true)
const isLoading = ref(false)
const page = ref(1)

// 加载初始数据
const loadItems = (pageNum: number) => {
  const newItems: Item[] = []
  const startIndex = (pageNum - 1) * 5

  for (let i = 1; i <= 5; i++) {
    const id = startIndex + i
    newItems.push({
      id,
      title: `Item ${id}`,
      content: `This is the content for item ${id}. It demonstrates the card component inside a page container.`,
    })
  }

  return newItems
}

// 初始加载
items.value = loadItems(page.value)

// 处理刷新
const handleRefresh = () => {
  console.log('Refreshing...')

  // 模拟API请求
  setTimeout(() => {
    page.value = 1
    items.value = loadItems(page.value)
    hasMore.value = true
    console.log('Refresh complete')
  }, 1500)
}

// 处理加载更多
const handleLoadMore = () => {
  if (isLoading.value || !hasMore.value) return

  console.log('Loading more...')
  isLoading.value = true

  // 模拟API请求
  setTimeout(() => {
    page.value++
    const newItems = loadItems(page.value)
    items.value = [...items.value, ...newItems]

    // 模拟数据结束
    if (page.value >= 3) {
      hasMore.value = false
    }

    isLoading.value = false
    console.log('Load more complete')
  }, 1500)
}
</script>

<style lang="scss" scoped>
.example-content {
  h2 {
    margin-bottom: 16px;
    font-size: 20px;
  }

  p {
    margin-bottom: 24px;
    color: #6c757d;
  }
}

.feature-list {
  margin-bottom: 24px;

  .feature-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #dee2e6;

    &:last-child {
      border-bottom: none;
    }

    i {
      width: 24px;
      margin-right: 12px;
      color: #ff4757;
    }
  }
}

.card-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.footer-actions {
  padding: 16px;
}
</style>
