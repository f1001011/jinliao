<template>
  <PageContainer title="My Orders" :showBack="true" :pullRefresh="true" @refresh="handleRefresh">
    <div class="orders-list-page">
      <!-- 未登录状态 -->
      <div v-if="!isLoggedIn" class="orders-list-page__not-logged-in">
        <div class="orders-list-page__not-logged-in-icon">
          <i class="fas fa-receipt"></i>
        </div>
        <h2 class="orders-list-page__not-logged-in-title">You're not logged in</h2>
        <p class="orders-list-page__not-logged-in-text">
          Login to view your order history and track current orders.
        </p>
        <button class="orders-list-page__login-btn" @click="navigateToLogin">
          Login / Register
        </button>
      </div>

      <!-- 已登录状态 -->
      <template v-else>
        <!-- 订单列表 -->
        <div class="orders-list-page__orders">
          <div v-if="orders.length > 0">
            <div
              v-for="order in orders"
              :key="order.id"
              class="orders-list-page__order-card"
              @click="navigateToOrderDetail(order.id)"
            >
              <!-- 订单时间 -->
              <div class="orders-list-page__order-date">
                {{ formatDate(order.orderDate) }}
              </div>

              <!-- 订单内容摘要 -->
              <div class="orders-list-page__order-summary">
                <div class="orders-list-page__restaurant-info">
                  <span class="orders-list-page__restaurant-name">{{ order.restaurantName }}</span>
                </div>

                <!-- 订单项列表 -->
                <div class="orders-list-page__order-items">
                  <div
                    v-for="(item, index) in order.items.slice(0, 2)"
                    :key="index"
                    class="orders-list-page__order-item"
                  >
                    <span class="orders-list-page__item-quantity">{{ item.quantity }}x</span>
                    <span class="orders-list-page__item-name">{{ item.name }}</span>
                  </div>
                  <div v-if="order.items.length > 2" class="orders-list-page__more-items">
                    +{{ order.items.length - 2 }} more items
                  </div>
                </div>

                <!-- 代付标记 -->
                <div v-if="order.isPaidForSomeone" class="orders-list-page__paid-for-someone">
                  <i class="fas fa-heart"></i>
                  <span>Paid for {{ order.payerName || 'someone' }}</span>
                </div>
              </div>

              <!-- 订单底部（总金额与状态） -->
              <div class="orders-list-page__order-footer">
                <div class="orders-list-page__order-total">
                  <span class="orders-list-page__total-amount">${{ order.total.toFixed(2) }}</span>
                </div>
                <div
                  :class="['orders-list-page__status', `orders-list-page__status--${order.status}`]"
                >
                  {{ formatStatus(order.status) }}
                </div>
              </div>
            </div>

            <!-- 加载更多按钮 -->
            <div v-if="hasMore && !isLoading" class="orders-list-page__load-more">
              <button class="orders-list-page__load-more-btn" @click="loadMoreOrders">
                Load More Orders
              </button>
            </div>

            <!-- 加载中指示器 -->
            <div v-if="isLoading" class="orders-list-page__loading">
              <i class="fas fa-spinner fa-spin"></i>
              <span>Loading more orders...</span>
            </div>
          </div>

          <!-- 无订单状态 -->
          <div v-else class="orders-list-page__empty">
            <div class="orders-list-page__empty-icon">
              <i class="fas fa-receipt"></i>
            </div>
            <h3 class="orders-list-page__empty-title">No Orders Found</h3>
            <p class="orders-list-page__empty-text">You haven't placed any orders yet.</p>
            <button class="orders-list-page__browse-btn" @click="navigateToHome">
              Browse Restaurants
            </button>
          </div>
        </div>
      </template>
    </div>
  </PageContainer>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import PageContainer from '@/components/layout/PageContainer.vue'
import { useUserStore } from '@/stores/modules/user'
import type { Order } from '@/types/models'

// 路由
const router = useRouter()

// Store
const userStore = useUserStore()

// 用户登录状态
const isLoggedIn = computed(() => userStore.isLoggedIn)

// 加载状态
const isLoading = ref(false)
const hasMore = ref(true)
const page = ref(1)

// 订单数据
const orders = ref<Order[]>([
  {
    id: 1001,
    restaurantName: 'Burger Palace',
    restaurantImage:
      'https://images.unsplash.com/photo-1561758033-d89a9ad46330?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-05-01T14:30:00',
    status: 'delivered',
    items: [
      { id: 1, name: 'Classic Cheeseburger', quantity: 2, price: 8.99 },
      { id: 2, name: 'French Fries', quantity: 1, price: 3.99 },
      { id: 3, name: 'Chocolate Milkshake', quantity: 1, price: 4.99 },
    ],
    total: 26.96,
    isPaidForSomeone: false,
  },
  {
    id: 1002,
    restaurantName: 'Pizza Heaven',
    restaurantImage:
      'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-04-25T15:45:00',
    status: 'delivered',
    items: [
      { id: 4, name: 'Margherita Pizza', quantity: 1, price: 12.99 },
      { id: 5, name: 'Garlic Bread', quantity: 1, price: 4.99 },
      { id: 6, name: 'Coke', quantity: 2, price: 1.99 },
    ],
    total: 21.96,
    isPaidForSomeone: true,
    payerName: 'John',
  },
  {
    id: 1003,
    restaurantName: 'Sushi World',
    restaurantImage:
      'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-04-15T19:20:00',
    status: 'delivered',
    items: [
      { id: 7, name: 'California Roll', quantity: 2, price: 10.99 },
      { id: 8, name: 'Miso Soup', quantity: 1, price: 3.99 },
      { id: 9, name: 'Green Tea', quantity: 1, price: 2.49 },
    ],
    total: 28.46,
    isPaidForSomeone: false,
  },
  {
    id: 1004,
    restaurantName: 'Taco Fiesta',
    restaurantImage:
      'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
    orderDate: '2023-04-05T12:15:00',
    status: 'cancelled',
    items: [
      { id: 10, name: 'Beef Taco', quantity: 3, price: 7.99 },
      { id: 11, name: 'Nachos', quantity: 1, price: 6.99 },
      { id: 12, name: 'Guacamole', quantity: 1, price: 3.99 },
    ],
    total: 34.95,
    isPaidForSomeone: true,
    payerName: 'Sarah',
  },
])

// 格式化日期
const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
  })
}

// 格式化订单状态
const formatStatus = (status: string): string => {
  switch (status) {
    case 'placed':
      return 'Order Placed'
    case 'preparing':
      return 'Preparing'
    case 'ready':
      return 'On the Way'
    case 'delivered':
      return 'Delivered'
    case 'cancelled':
      return 'Cancelled'
    default:
      return status
  }
}

// 导航到订单详情
const navigateToOrderDetail = (orderId: number) => {
  router.push(`/order/${orderId}`)
}

// 导航到登录页
const navigateToLogin = () => {
  router.push('/login')
}

// 导航到首页
const navigateToHome = () => {
  router.push('/')
}

// 加载更多订单
const loadMoreOrders = () => {
  if (isLoading.value || !hasMore.value) return

  isLoading.value = true

  // 模拟加载更多订单
  setTimeout(() => {
    // 演示用：当页码大于2时，不再加载更多
    if (page.value >= 2) {
      hasMore.value = false
    } else {
      // 模拟添加更多订单
      const moreOrders: Order[] = [
        {
          id: 1005,
          restaurantName: 'Panda Express',
          restaurantImage:
            'https://images.unsplash.com/photo-1525755662778-989d0524087e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
          orderDate: '2023-03-20T18:30:00',
          status: 'delivered',
          items: [
            { id: 13, name: 'Orange Chicken', quantity: 1, price: 9.99 },
            { id: 14, name: 'Fried Rice', quantity: 1, price: 4.99 },
            { id: 15, name: 'Spring Rolls', quantity: 2, price: 3.49 },
          ],
          total: 21.96,
          isPaidForSomeone: false,
        },
        {
          id: 1006,
          restaurantName: 'Salad Bar',
          restaurantImage:
            'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
          orderDate: '2023-03-15T12:45:00',
          status: 'delivered',
          items: [
            { id: 16, name: 'Caesar Salad', quantity: 1, price: 8.99 },
            { id: 17, name: 'Avocado Toast', quantity: 1, price: 6.99 },
          ],
          total: 15.98,
          isPaidForSomeone: true,
          payerName: 'Mike',
        },
      ]

      orders.value = [...orders.value, ...moreOrders]
      page.value++
    }

    isLoading.value = false
  }, 1000)
}

// 处理刷新
const handleRefresh = () => {
  // 重置分页和状态
  page.value = 1
  hasMore.value = true

  // 这里可以重新加载订单数据
  // 当前使用静态演示数据，所以只是简单地模拟刷新
  setTimeout(() => {
    // 重置为初始订单列表
    orders.value = [
      {
        id: 1001,
        restaurantName: 'Burger Palace',
        restaurantImage:
          'https://images.unsplash.com/photo-1561758033-d89a9ad46330?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-05-01T14:30:00',
        status: 'delivered',
        items: [
          { id: 1, name: 'Classic Cheeseburger', quantity: 2, price: 8.99 },
          { id: 2, name: 'French Fries', quantity: 1, price: 3.99 },
          { id: 3, name: 'Chocolate Milkshake', quantity: 1, price: 4.99 },
        ],
        total: 26.96,
        isPaidForSomeone: false,
      },
      {
        id: 1002,
        restaurantName: 'Pizza Heaven',
        restaurantImage:
          'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-04-25T15:45:00',
        status: 'delivered',
        items: [
          { id: 4, name: 'Margherita Pizza', quantity: 1, price: 12.99 },
          { id: 5, name: 'Garlic Bread', quantity: 1, price: 4.99 },
          { id: 6, name: 'Coke', quantity: 2, price: 1.99 },
        ],
        total: 21.96,
        isPaidForSomeone: true,
        payerName: 'John',
      },
      {
        id: 1003,
        restaurantName: 'Sushi World',
        restaurantImage:
          'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-04-15T19:20:00',
        status: 'delivered',
        items: [
          { id: 7, name: 'California Roll', quantity: 2, price: 10.99 },
          { id: 8, name: 'Miso Soup', quantity: 1, price: 3.99 },
          { id: 9, name: 'Green Tea', quantity: 1, price: 2.49 },
        ],
        total: 28.46,
        isPaidForSomeone: false,
      },
      {
        id: 1004,
        restaurantName: 'Taco Fiesta',
        restaurantImage:
          'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80',
        orderDate: '2023-04-05T12:15:00',
        status: 'cancelled',
        items: [
          { id: 10, name: 'Beef Taco', quantity: 3, price: 7.99 },
          { id: 11, name: 'Nachos', quantity: 1, price: 6.99 },
          { id: 12, name: 'Guacamole', quantity: 1, price: 3.99 },
        ],
        total: 34.95,
        isPaidForSomeone: true,
        payerName: 'Sarah',
      },
    ]
  }, 1000)
}

// 生命周期钩子
onMounted(() => {
  // 在实际应用中，这里会调用API获取订单数据
  // 现在使用模拟数据
})
</script>

<style lang="scss" scoped>
@use 'sass:color';
@import '@/assets/styles/variables.scss';
@import '@/assets/styles/mixins.scss';

.orders-list-page {
  min-height: 100vh;
  background-color: $gray-100;
  padding-bottom: $spacing-xl;

  &__not-logged-in {
    background-color: $white;
    border-radius: $border-radius-lg;
    padding: $spacing-xl;
    text-align: center;
    box-shadow: $shadow-sm;
    margin: $spacing-md;
  }

  &__not-logged-in-icon {
    font-size: 64px;
    color: $gray-400;
    margin-bottom: $spacing-md;
  }

  &__not-logged-in-title {
    font-size: $font-size-xl;
    font-weight: $font-weight-bold;
    margin-bottom: $spacing-sm;
    color: $text-primary;
  }

  &__not-logged-in-text {
    font-size: $font-size-md;
    color: $text-secondary;
    margin-bottom: $spacing-lg;
    line-height: 1.5;
  }

  &__login-btn {
    width: 100%;
    height: 48px;
    background-color: $primary;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    cursor: pointer;
    transition: all $transition-base;

    &:hover {
      background-color: color.adjust($primary, $lightness: -5%);
    }
  }

  &__orders {
    padding: $spacing-sm;
  }

  &__order-card {
    background-color: $white;
    border-radius: $border-radius-lg;
    padding: $spacing-md;
    margin-bottom: $spacing-md;
    box-shadow: $shadow-sm;
    cursor: pointer;
    transition:
      transform 0.2s ease,
      box-shadow 0.2s ease;

    &:hover {
      transform: translateY(-2px);
      box-shadow: $shadow-md;
    }
  }

  &__order-date {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin-bottom: $spacing-xs;
    border-bottom: 1px solid $gray-200;
    padding-bottom: $spacing-xs;
  }

  &__order-summary {
    padding: $spacing-sm 0;
  }

  &__restaurant-info {
    margin-bottom: $spacing-xs;
  }

  &__restaurant-name {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    color: $text-primary;
  }

  &__order-items {
    margin-bottom: $spacing-sm;
  }

  &__order-item {
    font-size: $font-size-sm;
    color: $text-secondary;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
  }

  &__item-quantity {
    font-weight: $font-weight-semibold;
    margin-right: $spacing-xs;
    color: $text-primary;
  }

  &__item-name {
    @include text-truncate;
  }

  &__more-items {
    font-size: $font-size-xs;
    color: $text-secondary;
    font-style: italic;
    margin-top: 2px;
  }

  &__paid-for-someone {
    background-color: rgba($primary, 0.1);
    color: $primary;
    border-radius: $border-radius-pill;
    padding: $spacing-xs $spacing-sm;
    display: inline-flex;
    align-items: center;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    margin-top: $spacing-xs;
    transition: background-color 0.2s ease;

    i {
      margin-right: $spacing-xs;
      font-size: $font-size-xs;
    }

    &:hover {
      background-color: rgba($primary, 0.15);
    }
  }

  &__order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: $spacing-sm;
    padding-top: $spacing-sm;
    border-top: 1px solid $gray-200;
  }

  &__order-total {
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    color: $text-primary;
  }

  &__total-amount {
    color: $primary;
  }

  &__status {
    font-size: $font-size-xs;
    font-weight: $font-weight-medium;
    padding: 4px $spacing-sm;
    border-radius: $border-radius-pill;
    text-transform: capitalize;

    &--placed {
      background-color: rgba($info, 0.1);
      color: $info;
    }

    &--preparing {
      background-color: rgba($warning, 0.1);
      color: $warning;
    }

    &--ready {
      background-color: rgba($info, 0.1);
      color: $info;
    }

    &--delivered {
      background-color: rgba($success, 0.1);
      color: $success;
    }

    &--cancelled {
      background-color: rgba($danger, 0.1);
      color: $danger;
    }
  }

  &__empty {
    text-align: center;
    padding: $spacing-xl 0;
  }

  &__empty-icon {
    font-size: 64px;
    color: $gray-300;
    margin-bottom: $spacing-md;
  }

  &__empty-title {
    font-size: $font-size-lg;
    font-weight: $font-weight-bold;
    color: $text-primary;
    margin-bottom: $spacing-sm;
  }

  &__empty-text {
    font-size: $font-size-md;
    color: $text-secondary;
    margin-bottom: $spacing-lg;
  }

  &__browse-btn {
    height: 48px;
    padding: 0 $spacing-lg;
    background-color: $primary;
    color: $white;
    border: none;
    border-radius: $border-radius-md;
    font-size: $font-size-md;
    font-weight: $font-weight-semibold;
    cursor: pointer;
    transition: all $transition-base;

    &:hover {
      background-color: color.adjust($primary, $lightness: -5%);
    }
  }

  &__load-more {
    text-align: center;
    margin-top: $spacing-md;
    margin-bottom: $spacing-md;
  }

  &__load-more-btn {
    height: 40px;
    padding: 0 $spacing-md;
    background-color: $white;
    color: $primary;
    border: 1px solid $primary;
    border-radius: $border-radius-md;
    font-size: $font-size-sm;
    font-weight: $font-weight-medium;
    cursor: pointer;
    transition: all $transition-base;

    &:hover {
      background-color: rgba($primary, 0.05);
    }
  }

  &__loading {
    text-align: center;
    padding: $spacing-md 0;
    color: $text-secondary;
    font-size: $font-size-sm;

    i {
      margin-right: $spacing-xs;
    }
  }
}
</style>
